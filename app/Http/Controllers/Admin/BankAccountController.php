<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Storage;

class BankAccountController extends Controller
{
    /**
     * Show the form for editing the single bank account.
     */
    public function index()
    {
        $bankAccount = BankAccount::firstOrNew([], [
            'is_active' => false
        ]);
        return view('admin.bank_accounts.edit', compact('bankAccount'));
    }

    /**
     * Update the single bank account in storage.
     */
    public function update(Request $request)
    {
        $bankAccount = BankAccount::firstOrCreate([]);

        $data = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'qr_code' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'remove_qr_code' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->has('is_active');

        $oldQrCode = $bankAccount->qr_code_path;
        $removeQrCode = $request->has('remove_qr_code');

        if ($request->hasFile('qr_code')) {
            $data['qr_code_path'] = $request->file('qr_code')->store('qrcodes', 'public');
        } elseif ($removeQrCode) {
            $data['qr_code_path'] = null;
        }

        $bankAccount->update($data);

        if (($request->hasFile('qr_code') || $removeQrCode) && $oldQrCode && Storage::disk('public')->exists($oldQrCode)) {
            Storage::disk('public')->delete($oldQrCode);
        }

        return redirect()->route('admin.bank-account.index')->with('success', 'บันทึกข้อมูลบัญชีธนาคารสำเร็จ');
    }
}
