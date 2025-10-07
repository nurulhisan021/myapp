<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = ['name','category','price','description','image'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/'.$this->image);
        }
        // รูปสำรอง (สร้างไฟล์นี้ใน public/images ถ้าอยากใช้ไฟล์ในเครื่อง)
        return asset('images/product-placeholder.png');
    }
}
