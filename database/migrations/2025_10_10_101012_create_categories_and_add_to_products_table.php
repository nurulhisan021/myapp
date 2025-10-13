<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        // 2. Add foreign key to products table
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('name')->constrained('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Reverse in opposite order
        // 1. Drop foreign key from products
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        // 2. Drop categories table
        Schema::dropIfExists('categories');
    }
};
