<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaguanggarandewanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPaguAnggaranDewan', function (Blueprint $table) {
            $table->string('PaguAnggaranDewanID',19);
            $table->string('PemilikPokokID',19);
            $table->decimal('Jumlah1',15,2)->default(0);
            $table->decimal('Jumlah2',15,2)->default(0);

            $table->text('Descr')->nullable();
            $table->year('TA'); 

            $table->timestamps();

            $table->primary('PaguAnggaranDewanID');
            $table->index('PemilikPokokID');

            $table->foreign('PemilikPokokID')
                    ->references('PemilikPokokID')
                    ->on('tmPemilikPokok')
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
        Schema::dropIfExists('tmPaguAnggaranDewan');
    }
}