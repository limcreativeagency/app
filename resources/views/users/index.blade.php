@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">{{ __('users.list_title', ['role' => __('general.roles.' . $role)]) }}</h2>
        <a href="{{ route('users.create', $role) }}" class="btn btn-primary">{{ __('users.create_title', ['role' => __('general.roles.' . $role)]) }}</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>{{ __('users.name') }}</th>
                        <th>{{ __('users.email') }}</th>
                        <th>{{ __('users.phone') }}</th>
                        <th>{{ __('general.general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            <a href="{{ route('users.edit', [$role, $user->id]) }}" class="btn btn-sm btn-warning">{{ __('general.general.edit') }}</a>
                            <form action="{{ route('users.destroy', [$role, $user->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('general.general.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">{{ __('users.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 