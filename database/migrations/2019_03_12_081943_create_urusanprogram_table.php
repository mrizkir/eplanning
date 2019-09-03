<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrusanprogramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trUrsPrg', function (Blueprint $table) {
            $table->string('UrsPrgID',19);
            $table->string('UrsID',19);
            $table->string('PrgID',19);
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);

            $table->timestamps();

            $table->primary('UrsPrgID');
            $table->index('UrsID');
            $table->unique('PrgID');

            $table->foreign('UrsID')
                ->references('UrsID')
                ->on('tmUrs')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('PrgID')
                ->references('PrgID')
                ->on('tmPrg')
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
        Schema::dropIfExists('urusanprogram');
    }
}

