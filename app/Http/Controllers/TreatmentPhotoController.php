<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TreatmentPhoto;
use App\Models\Treatment;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Carbon\Carbon;

class TreatmentPhotoController extends Controller
{
    public function create(Treatment $treatment)
    {
        return view('treatments.photos.create', compact('treatment'));
    }

    public function showDayPhotos($treatmentId, $day)
    {
        $treatment = \App\Models\Treatment::with('photos')->findOrFail($treatmentId);
        $photos = $treatment->photos->where('day_after_operation', (int)$day);
        return view('treatments.photos.day', compact('treatment', 'photos', 'day'));
    }

    public function showPreopPhotos($treatmentId)
    {
        $treatment = \App\Models\Treatment::with('photos')->findOrFail($treatmentId);
        $photos = $treatment->photos->where('day_after_operation', '<', 0);
        return view('treatments.photos.preop', compact('treatment', 'photos'));
    }

    public function showMonthPhotos($treatmentId, $month)
    {
        $treatment = \App\Models\Treatment::with('photos')->findOrFail($treatmentId);
        $start = $month * 30;
        $end = ($month + 1) * 30 - 1;
        $photos = $treatment->photos->where('day_after_operation', '>=', $start)->where('day_after_operation', '<', $end);
        return view('treatments.photos.month', compact('treatment', 'photos', 'month'));
    }

    public function showLongTermPhotos($treatmentId)
    {
        $treatment = \App\Models\Treatment::with('photos')->findOrFail($treatmentId);
        $photos = $treatment->photos->where('day_after_operation', '>=', 365);
        return view('treatments.photos.longterm', compact('treatment', 'photos'));
    }

    public function downloadZip(Request $request, Treatment $treatment)
    {
        $ids = $request->input('photo_ids', []);
        if (!is_array($ids) || count($ids) < 2) {
            return abort(400, 'En az 2 fotoğraf seçmelisiniz.');
        }
        $photos = $treatment->photos()->whereIn('id', $ids)->get();
        if ($photos->isEmpty()) {
            return abort(404, 'Fotoğraf bulunamadı.');
        }
        $zipFileName = 'treatment_photos_' . now()->format('Ymd_His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($photos as $photo) {
                $filePath = storage_path('app/public/' . $photo->image_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
        } else {
            return abort(500, 'Zip dosyası oluşturulamadı.');
        }
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function store(Request $request, Treatment $treatment)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
                'photo_date' => 'required|date',
            ], [
                'photo.required' => 'Fotoğraf seçilmedi.',
                'photo.image' => 'Yüklenen dosya bir resim olmalı.',
                'photo.mimes' => 'Sadece jpeg, png, jpg, gif formatları desteklenir.',
                'photo.max' => 'Fotoğraf en fazla 8MB olmalı.',
                'photo_date.required' => 'Fotoğraf çekim tarihi zorunludur.',
                'photo_date.date' => 'Geçerli bir tarih giriniz.',
            ]);

            if (!$request->hasFile('photo')) {
                throw new \Exception('Fotoğraf dosyası bulunamadı.');
            }

            $file = $request->file('photo');
            if (!$file->isValid()) {
                throw new \Exception('Dosya yükleme hatası: ' . $file->getErrorMessage());
            }

            $path = $file->store('treatment_photos', 'public');
            if (!$path) {
                throw new \Exception('Dosya kaydedilemedi.');
            }

            // Fotoğraf tarihi ve operasyon tarihi hesaplamaları
            $photoDate = Carbon::parse($request->photo_date);
            $operationDate = Carbon::parse($treatment->operation_date);

            if (!$operationDate) {
                throw new \Exception("Tedaviye ait operasyon tarihi bulunamadı.");
            }

            // Gün farkını hesapla (negatif değerler operasyon öncesini gösterir)
            $diff = $photoDate->diff($operationDate);
            $dayAfter = $photoDate->isBefore($operationDate) ? -$diff->days : $diff->days;

            // Fotoğraf evresi belirleniyor
            if ($dayAfter < 0) {
                $photoStage = 'preop';
                $stageType = 'Operasyon Öncesi';
            } elseif ($dayAfter == 0) {
                $photoStage = 'op';
                $stageType = 'Operasyon Günü';
            } else {
                $photoStage = 'postop';
                $stageType = "{$dayAfter}. Gün";
            }

            $photo = new TreatmentPhoto();
            $photo->treatment_id = $treatment->id;
            $photo->image_path = $path;
            $photo->photo_date = $photoDate;
            $photo->day_after_operation = $dayAfter;
            $photo->photo_stage = $photoStage;
            $photo->stage_type = $stageType;
            $photo->save();

            return redirect()->route('treatments.show', $treatment)
                ->with('success', 'Fotoğraf başarıyla yüklendi.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Fotoğraf yükleme validation hatası:', $e->errors());
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors());
        } catch (\Exception $e) {
            if (isset($path) && \Storage::disk('public')->exists($path)) {
                \Storage::disk('public')->delete($path);
            }
            \Log::error('Fotoğraf yükleme hatası: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fotoğraf yüklenirken hata oluştu: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Treatment $treatment, TreatmentPhoto $photo)
    {
        $request->validate([
            'photo_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            if (!$file->isValid()) {
                return back()->with('error', 'Dosya yükleme hatası: ' . $file->getErrorMessage());
            }
            // Eski fotoğrafı sil
            if ($photo->image_path && \Storage::disk('public')->exists($photo->image_path)) {
                \Storage::disk('public')->delete($photo->image_path);
            }
            $photo->image_path = $file->store('treatment_photos', 'public');
        }

        $photo->photo_date = $request->photo_date;
        $operationDate = \Carbon\Carbon::parse($treatment->operation_date);
        $photoDate = \Carbon\Carbon::parse($request->photo_date);
        $dayAfter = $photoDate->diffInDays($operationDate, false);
        if ($dayAfter < 0) {
            $photoStage = 'preop';
        } elseif ($dayAfter == 0) {
            $photoStage = 'op';
        } else {
            $photoStage = 'postop';
        }
        $photo->day_after_operation = $dayAfter;
        $photo->photo_stage = $photoStage;
        if ($dayAfter < 0) {
            $photo->stage_type = 'Operasyon Öncesi';
        } elseif ($dayAfter === 0) {
            $photo->stage_type = 'Operasyon Günü';
        } elseif ($dayAfter < 30) {
            $photo->stage_type = $dayAfter . '. Gün';
        } else {
            $photo->stage_type = floor($dayAfter / 30) . '. Ay';
        }
        $photo->save();

        return redirect()->route('treatments.show', $treatment)
            ->with('success', 'Fotoğraf başarıyla güncellendi.');
    }
}
