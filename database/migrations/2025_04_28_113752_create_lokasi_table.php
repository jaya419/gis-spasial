<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLokasiTable extends Migration
{
    public function up()
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lokasi');
    }
}
