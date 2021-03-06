<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRekeningsubrincianobyekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmSubROby', function (Blueprint $table) {
            $table->string('SubRObyID',19);   
            $table->string('RObyID',19);                     
            $table->string('Kd_Sub',4);
            $table->string('Nm_Sub');
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('SubRObyID_Src',19)->nullable();
            $table->timestamps();

            $table->primary('SubRObyID');
            $table->index('RObyID');

            $table->foreign('RObyID')
                            ->references('RObyID')
                            ->on('tmROby')
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
        Schema::dropIfExists('tmSubROby');
    }
}
