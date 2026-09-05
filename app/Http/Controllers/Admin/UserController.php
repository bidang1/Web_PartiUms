<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'KESEKRETARIATAN')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'KESEKRETARIATAN',
            'is_active' => true,
            'must_change_password' => true,
            'created_by' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Membuat akun kesekretariatan baru: ' . $newUser->name . ' (' . $newUser->email . ')',
            'entity_type' => 'User',
            'entity_id' => $newUser->id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun kesekretariatan berhasil dibuat.');
    }

    public function deactivate(User $user)
    {
        // Avoid deactivating self or admin
        if ($user->role === 'SUPERADMIN') {
            return back()->with('error', 'Tidak dapat menonaktifkan akun Superadmin.');
        }

        $user->update(['is_active' => false]);

        // Revoke all active sessions for this user
        try {
            \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $user->id)->delete();
        } catch (\Throwable $e) {
            // Graceful fallback if database session driver is not active
        }

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Menonaktifkan akun kesekretariatan: ' . $user->name,
            'entity_type' => 'User',
            'entity_id' => $user->id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun kesekretariatan berhasil dinonaktifkan.');
    }

    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Mengaktifkan kembali akun kesekretariatan: ' . $user->name,
            'entity_type' => 'User',
            'entity_id' => $user->id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun kesekretariatan berhasil diaktifkan.');
    }

    public function resetPassword(Request $request, User $user)
    {
        if ($user->role === 'SUPERADMIN') {
            return back()->with('error', 'Tidak dapat mereset akun Superadmin melalui menu ini.');
        }

        $validated = $request->validate([
            'password' => ['required', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => true,
        ]);

        // Revoke all active sessions for this user so they must log in with new password
        try {
            \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $user->id)->delete();
        } catch (\Throwable $e) {
            // Graceful fallback if database session driver is not active
        }

        // Audit Log
        AuditLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Mereset kata sandi akun kesekretariatan: ' . $user->name,
            'entity_type' => 'User',
            'entity_id' => $user->id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Kata sandi akun ' . $user->name . ' berhasil di-reset.');
    }
}
