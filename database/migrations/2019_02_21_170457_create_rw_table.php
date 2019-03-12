<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRwTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPmRW', function (Blueprint $table) {
            $table->string('PmRWID',19);
            $table->string('PmDesaID',19);
            $table->tinyInteger('Kd_RW');
            $table->string('Nm_RW');            
            $table->string('Kepala_RW');            
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('PmRWID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);
            
            $table->timestamps();

            $table->primary('PmRWID');
            $table->index('PmDesaID');

            $table->foreign('PmDesaID')
                ->references('PmDesaID')
                ->on('tmPmDesa')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rw');
    }
}
