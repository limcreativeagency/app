<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class DoctorRepresentativeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role_id != 2) {
                abort(403, 'Yetkisiz erişim');
            }
            return $next($request);
        });
    }

    public function index(Request $request, $role)
    {
        $user = Auth::user();
        $roleId = Role::where('slug', $role)->value('id');
        $users = User::where('role_id', $roleId)
            ->where('hospital_id', $user->hospital_id)
            ->get();
        return view('users.index', compact('users', 'role'));
    }

    public function create($role)
    {
        return view('users.create', compact('role'));
    }

    public function store(Request $request, $role)
    {
        $user = Auth::user();
        $roleId = Role::where('slug', $role)->value('id');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $validated['password'] = \Hash::make($validated['password']);
        $validated['role_id'] = $roleId;
        $validated['hospital_id'] = $user->hospital_id;
        User::create($validated);
        return redirect()->route('users.index', $role)->with('success', ucfirst($role).' başarıyla eklendi.');
    }

    public function edit($role, User $user)
    {
        $this->authorizeUser($user);
        return view('users.edit', compact('user', 'role'));
    }

    public function update(Request $request, $role, User $user)
    {
        $this->authorizeUser($user);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:20|unique:users,phone,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        if ($validated['password']) {
            $validated['password'] = \Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);
        return redirect()->route('users.index', $role)->with('success', ucfirst($role).' güncellendi.');
    }

    public function destroy($role, User $user)
    {
        $this->authorizeUser($user);
        $user->delete();
        return redirect()->route('users.index', $role)->with('success', ucfirst($role).' silindi.');
    }

    private function authorizeUser(User $user)
    {
        if ($user->hospital_id !== Auth::user()->hospital_id) {
            abort(403, 'Yetkisiz erişim');
        }
    }
} 