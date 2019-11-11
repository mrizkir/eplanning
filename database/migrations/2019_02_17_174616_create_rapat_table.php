<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRapatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapat', function (Blueprint $table) {
            $table->string('RapatID',19);
            $table->string('Judul');
            $table->text('Isi');
            $table->string('anggota');
            $table->date('Tanggal_Rapat');
            $table->year('TA');
            $table->timestamps();

            $table->primary('RapatID');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rapat');
    }
}
