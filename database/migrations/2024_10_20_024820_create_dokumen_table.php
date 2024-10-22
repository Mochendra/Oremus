<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenTable extends Migration
{
    public function up()
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi');
            $table->string('nama_perusahaan');
            $table->string('jenis_dokumen');
            $table->string('no_dokumen');
            $table->string('wilayah_kerja');
            $table->string('status_dokumen');
            $table->date('tanggal_terbit');
            $table->date('expired');
            $table->string('pdf_upload');
            $table->timestamps();
            $table->boolean('notified')->default(false);
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('dokumens'); // Use 'dokumens' instead of 'dokumen'
    }
}