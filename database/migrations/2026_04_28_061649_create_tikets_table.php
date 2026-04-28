<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('kode_tiket')->unique();
            $table->string('judul_kendala');
            $table->enum('kategori', ['Software', 'Hardware', 'Jaringan', 'Printer', 'Aplikasi']);
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi']);
            $table->enum('status', ['Open', 'In Progress', 'Pending', 'Resolved', 'Closed'])->default('Open');
            $table->longText('deskripsi');
            $table->string('foto_kendala')->nullable();
            $table->text('solusi_teknis')->nullable();
            $table->integer('rating')->nullable()->min(1)->max(5);
            $table->text('komentar_balasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
