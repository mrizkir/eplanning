<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPrg', function (Blueprint $table) {
            $table->string('PrgID',16);
            $table->string('Kd_Prog',4);
            $table->string('PrgNm');
            $table->boolean('Jns')->default(1);            
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('PrgID_Src',16)->nullable();
            $table->boolean('Locked')->default(0);
            
            $table->timestamps();

            $table->primary('PrgID');
            $table->index('Kd_Prog');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmPrg');
    }
}
