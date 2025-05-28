<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index($type)
    {
        $roleId = $type === 'doctor' ? 3 : 4;
        $users = User::where('role_id', $roleId)->get();
        return view('users.index', compact('users', 'type'));
    }

    public function create($type)
    {
        return view('users.create', compact('type'));
    }

    public function store(Request $request, $type)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index.' . $type)
            ->with('success', __('users.created_success'));
    }

    public function edit($type, User $user)
    {
        return view('users.edit', compact('user', 'type'));
    }

    public function update(Request $request, $type, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index.' . $type)
            ->with('success', __('users.updated_success'));
    }

    public function destroy($type, User $user)
    {
        $user->delete();

        return redirect()->route('users.index.' . $type)
            ->with('success', __('users.deleted_success'));
    }
} 