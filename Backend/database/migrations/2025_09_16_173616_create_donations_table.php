<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name')->nullable();
            $table->string('email')->nullable();       // إضافة البريد الإلكتروني
            $table->string('phone')->nullable();       // إضافة رقم الهاتف
            $table->string('type');
            $table->integer('quantity');
            $table->enum('status', ['pending', 'received', 'distributed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
