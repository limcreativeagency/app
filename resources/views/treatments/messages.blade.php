@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Mesajlar - {{ $treatment->patient->user->name }}</h4>
        <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-outline-secondary btn-sm">Tedavi Detayına Geri Dön</a>
    </div>
    <div class="card">
        <div class="card-body" style="min-height:400px; position:relative;">
            <div class="chat-area" id="chat-area">
                @foreach($notes as $note)
                    @php
                        $isMine = auth()->id() === $note->user_id;
                        $bubbleClass = $isMine ? 'chat-bubble chat-bubble-right' : 'chat-bubble chat-bubble-left';
                        $nameClass = $isMine ? 'chat-name chat-name-right' : 'chat-name chat-name-left';
                        // Okundu işaretleme
                        if (!$isMine && is_null($note->read_at)) {
                            $note->read_at = now();
                            $note->read_by = auth()->id();
                            $note->save();
                        }
                    @endphp
                    <div class="{{ $bubbleClass }}">
                        <span class="{{ $nameClass }}">
                            {{ $note->user->name }} <span style="color:#888">({{ $note->user->role->slug }})</span>
                        </span>
                        <span>{{ $note->note }}</span>
                        <span class="chat-date">
                            {{ $note->created_at->format('d.m.Y H:i') }}
                            @if($isMine)
                                @if($note->read_at)
                                    <span class="text-success ms-2">✔✔ Okundu {{ $note->read_at->format('H:i') }}</span>
                                @else
                                    <span class="text-secondary ms-2">✓ Gönderildi</span>
                                @endif
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        @auth
        <div class="card-footer bg-white border-0 pt-0">
            <form action="{{ route('treatments.notes.store', $treatment) }}" method="POST" class="w-100 px-2 py-2 d-flex align-items-center gap-2">
                @csrf
                <input type="hidden" name="is_visible_to_patient" value="1">
                <textarea name="note" rows="1" class="flex-grow-1 form-control me-2" placeholder="Mesajınızı yazın..." required></textarea>
                <button type="submit" class="btn btn-primary px-4 py-2">Gönder</button>
            </form>
        </div>
        @endauth
    </div>
</div>
@push('styles')
<style>
    .chat-area { max-height: 500px; overflow-y: auto; background: #fff; padding: 1rem; }
    .chat-bubble {
        max-width: 420px;
        border-radius: 1.25rem;
        padding: 0.5rem 1rem;
        font-size: 0.95rem;
        box-shadow: 0 1px 4px 0 rgba(0,0,0,0.04);
        margin-bottom: 0.5rem;
        display: flex;
        flex-direction: column;
    }
    .chat-bubble-right {
        background: #d1fae5;
        margin-left: auto;
        margin-right: 0;
        align-items: flex-end;
    }
    .chat-bubble-left {
        background: #f3f4f6;
        margin-right: auto;
        margin-left: 0;
        align-items: flex-start;
    }
    .chat-name {
        font-size: 0.85em;
        font-weight: 600;
        margin-bottom: 0.15em;
    }
    .chat-name-right { color: #059669; }
    .chat-name-left { color: #6d28d9; }
    .chat-date {
        font-size: 0.75em;
        color: #9ca3af;
        margin-top: 0.2em;
        align-self: flex-end;
    }
</style>
@endpush
@endsection 