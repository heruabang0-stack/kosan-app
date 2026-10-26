<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('kos', function (Blueprint $table) {
        $table->id();
        $table->string('nama_kos');
        $table->text('alamat');
        $table->integer('harga');
        $table->string('tipe'); // Contoh: Putra, Putri, Campur
        $table->string('fasilitas'); // Contoh: AC, WiFi, KM Dalam
        $table->string('foto')->nullable(); // Untuk upload gambar
        $table->timestamps();
    });

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
