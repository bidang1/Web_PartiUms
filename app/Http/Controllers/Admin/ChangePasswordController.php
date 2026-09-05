<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\AuditLog;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        return view('admin.change-password');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Mengubah kata sandi akun sendiri',
            'entity_type' => 'User',
            'entity_id' => $user->id,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Kata sandi berhasil diperbarui.');
    }
}

