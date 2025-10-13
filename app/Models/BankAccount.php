<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'qr_code_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['qr_code_url'];

    public function getQrCodeUrlAttribute()
    {
        if ($this->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->qr_code_path)) {
            return asset('storage/'.$this->qr_code_path);
        }
        return null;
    }
}
