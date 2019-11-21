<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLongListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmLongList', function (Blueprint $table) {
            $table->string('LongListID',19);
            $table->string('OrgID',19);
            $table->string('KgtNm');
            $table->decimal('NilaiUsulan',15,2)->nullable();
            $table->decimal('Sasaran_Angka',15,2)->nullable();
            $table->string('Sasaran_Uraian')->nullable();
            $table->string('Lokasi');
            $table->string('Output')->nullable();
            $table->string('Descr')->nullable();            
            $table->year('TA');
            $table->timestamps();            

            $table->primary('LongListID');  
            $table->index('OrgID');

            $table->foreign('OrgID')
                    ->references('OrgID')
                    ->on('tmOrg')
                    ->onDelete('set null')
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
        Schema::dropIfExists('tmLongList');
    }
}
