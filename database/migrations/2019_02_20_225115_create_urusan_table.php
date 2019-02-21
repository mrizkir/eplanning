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
            $table->tinyInteger('Kd_Urusan');
            $table->string('Nm_Urusan',100);
            $table->year('TA');
            $table->string('Descr');
            $table->string('KUrsID_Src',16);

            $table->timestamps();
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
