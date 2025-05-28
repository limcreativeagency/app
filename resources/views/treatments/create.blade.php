@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Yeni Tedavi Ekle</h4>
            </div>

            <x-treatment-form :patients="$patients" :presetPatient="$presetPatient" />
        </div>
    </div>
</div>
@endsection 