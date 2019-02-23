<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrusanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmUrs', function (Blueprint $table) {
            $table->string('UrsID',16);
            $table->string('KUrsID',16);
            $table->string('Kd_Bidang',4);
            $table->string('Nm_Bidang',100);
            $table->year('TA');
            $table->string('Descr')->nullable();
            $table->string('UrsID_Src',16)->nullable();

            $table->timestamps();

            $table->index('KUrsID');

            $table->foreign('KUrsID')
                ->references('KUrsID')
                ->on('tmKUrs')
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
        Schema::dropIfExists('urusan');
    }
}
