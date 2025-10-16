<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankAccountController extends Controller
{
    /**
     * Show the form for editing the single bank account.
     */
    public function index()
    {
        $this->authorize('viewAny', BankAccount::class);

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
        $bankAccount = BankAccount::firstOrNew([]); // Get the instance to pass to policy
        $this->authorize('update', $bankAccount);

        $rules = [
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'remove_qr_code' => 'nullable|boolean',
        ];

        $messages = [
            'bank_name.required' => 'กรุณากรอกชื่อธนาคาร',
            'account_name.required' => 'กรุณากรอกชื่อบัญชี',
            'account_number.required' => 'กรุณากรอกเลขที่บัญชี',
            'qr_code.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'qr_code.mimes' => 'รองรับไฟล์รูปภาพนามสกุล: jpg, jpeg, png, webp เท่านั้น',
            'qr_code.max' => 'ขนาดของไฟล์ต้องไม่เกิน 1MB',
        ];

        if ($request->hasFile('qr_code')) {
            $rules['qr_code'] = 'image|mimes:jpg,jpeg,png,webp|max:1024';
        }

        $data = $request->validate($rules, $messages);
        $data['is_active'] = $request->has('is_active');

        // Find the single record, or prepare to create it.
        $bankAccount = BankAccount::firstOrNew([]);

        // Handle file upload before saving data
        $oldQrCode = $bankAccount->qr_code_path;
        $removeQrCode = $request->has('remove_qr_code');

        if ($request->hasFile('qr_code')) {
            $data['qr_code_path'] = $request->file('qr_code')->store('qrcodes', 'public');
        } elseif ($removeQrCode) {
            $data['qr_code_path'] = null;
        }

        // Update or Create the record with all data at once.
        $bankAccount->fill($data);
        $bankAccount->save();

        // Clean up old file if needed
        if (($request->hasFile('qr_code') || $removeQrCode) && $oldQrCode && Storage::disk('public')->exists($oldQrCode)) {
            Storage::disk('public')->delete($oldQrCode);
        }

        return redirect()->route('admin.bank-account.index')->with('success', 'บันทึกข้อมูลบัญชีธนาคารสำเร็จ');
    }
}
