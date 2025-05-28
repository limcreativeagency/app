@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('medications.medication_plans') }}</h1>
        <a href="{{ route('medication-plans.create') }}" class="btn btn-primary">
            {{ __('medications.create_new_plan') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('medications.plan_name') }}</th>
                            <th>{{ __('medications.patient') }}</th>
                            <th>{{ __('medications.treatment') }}</th>
                            <th>{{ __('medications.type') }}</th>
                            <th>{{ __('medications.start_date') }}</th>
                            <th>{{ __('medications.end_date') }}</th>
                            <th>{{ __('medications.status') }}</th>
                            <th>{{ __('general.general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>{{ $plan->name }}</td>
                                <td>{{ $plan->treatment->patient->name }}</td>
                                <td>{{ $plan->treatment->title }}</td>
                                <td>{{ __('medications.types.' . $plan->type) }}</td>
                                <td>{{ $plan->start_date->format('d.m.Y') }}</td>
                                <td>{{ $plan->end_date->format('d.m.Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $plan->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ __('medications.statuses.' . $plan->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('medication-plans.edit', $plan) }}" class="btn btn-sm btn-primary">
                                            {{ __('general.general.edit') }}
                                        </a>
                                        <form action="{{ route('medication-plans.destroy', $plan) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('medications.confirm_delete') }}')">
                                                {{ __('general.general.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('medications.no_plans_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $plans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 