<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('account.addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account.addresses.create');
    }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
        ], [
            'name.required' => 'กรุณากรอกชื่อที่ใช้ในการจัดส่ง',
            'address.required' => 'กรุณากรอกที่อยู่',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
        ]);

        Auth::user()->addresses()->create($validatedData);

        return redirect()->route('account.addresses.index')->with('success', 'เพิ่มที่อยู่ใหม่เรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        if (Auth::id() !== $address->user_id) {
            abort(403);
        }

        return view('account.addresses.edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        if (Auth::id() !== $address->user_id) {
            abort(403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
        ], [
            'name.required' => 'กรุณากรอกชื่อที่ใช้ในการจัดส่ง',
            'address.required' => 'กรุณากรอกที่อยู่',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
        ]);

        $address->update($validatedData);

        return redirect()->route('account.addresses.index')->with('success', 'อัปเดตที่อยู่เรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        if (Auth::id() !== $address->user_id) {
            abort(403);
        }

        $address->delete();

        return redirect()->route('account.addresses.index')->with('success', 'ลบที่อยู่เรียบร้อยแล้ว');
    }
}
