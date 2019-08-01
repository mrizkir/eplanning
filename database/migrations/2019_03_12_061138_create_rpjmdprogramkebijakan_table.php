<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpjmdprogramkebijakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPrioritasProgramKebijakan', function (Blueprint $table) {
            $table->string('ProgramKebijakanID',19);
            $table->string('PrioritasKebijakanKabID',19);            
            $table->string('UrsID',19); 
            $table->string('PrgID',19);
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('ProgramKebijakanID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);

            $table->timestamps();
            
            $table->primary('ProgramKebijakanID');
            $table->index('PrioritasKebijakanKabID');
            $table->index('UrsID');
            $table->index('PrgID');

            $table->foreign('PrioritasKebijakanKabID')
                    ->references('PrioritasKebijakanKabID')
                    ->on('tmPrioritasKebijakanKab')
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
        Schema::dropIfExists('tmPrioritasIndikatorSasaran');
    }
}
