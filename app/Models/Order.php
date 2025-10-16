<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'shipping_name',
        'shipping_address',
        'shipping_phone',
        'payment_slip_path',
        'tracking_number',
        'shipping_carrier',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    protected $appends = ['payment_slip_url'];

    public function getPaymentSlipUrlAttribute()
    {
        if ($this->payment_slip_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->payment_slip_path)) {
            return asset('storage/'.$this->payment_slip_path);
        }
        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
