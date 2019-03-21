<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMappingurusantofungsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trUrsFungsi', function (Blueprint $table) {
            $table->string('UrsFungsiID',19);
            $table->string('FungsiID',19);
            $table->string('UrsID',19);        
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);

            $table->timestamps();
            $table->primary('UrsFungsiID');
            $table->index('FungsiID');
            $table->index('UrsID');

            $table->foreign('FungsiID')
                ->references('FungsiID')
                ->on('tmFungsi')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('UrsID')
                ->references('UrsID')
                ->on('tmUrs')
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
        Schema::dropIfExists('trUrsFungsi');
    }
}
