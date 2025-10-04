<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTugasKumpulsTable extends Migration
{
    public function up()
    {
        Schema::create('tugas_kumpuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained()->onDelete('cascade');  // relasi ke tabel tugas
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   // relasi ke tabel users
            $table->string('file');  // file hasil tugas yang diupload user
            $table->string('nilai')->nullable();  // nilai yang diberikan admin, nullable karena awalnya kosong
            $table->text('komentar')->nullable(); // komentar admin untuk nilai
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tugas_kumpuls');
    }
}
