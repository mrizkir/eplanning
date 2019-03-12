<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpjmdkebijakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPrioritasKebijakanKab', function (Blueprint $table) {
            $table->string('PrioritasKebijakanKabID',19);
            $table->string('PrioritasStrategiKabID',19);            
            $table->string('Kd_Kebijakan',4);
            $table->string('Nm_Kebijakan');           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('PrioritasKebijakanKabID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);

            $table->primary('PrioritasKebijakanKabID');

            $table->index('PrioritasStrategiKabID');

            $table->foreign('PrioritasStrategiKabID')
                    ->references('PrioritasStrategiKabID')
                    ->on('tmPrioritasStrategiKab')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });

        Schema::create('trIndikatorKinerja', function (Blueprint $table) {
            $table->string('IndikatorKinerjaID',19);
            $table->string('PrioritasKebijakanKabID',19);            
            $table->string('UrsID',19); 
            $table->string('OrgID',19); 
            $table->string('OrgID2',19); 
            $table->string('OrgID3',19); 
                        
            $table->string('Nm_Indikator'); 
            
            $table->year('TA_N');

            $table->string('TargetN');
            $table->string('TargetN1');
            $table->string('TargetN2');
            $table->string('TargetN3');
            $table->string('TargetN4');
            $table->string('TargetN5');

            $table->decimal('PaguDanaN1',15,2);
            $table->decimal('PaguDanaN2',15,2);
            $table->decimal('PaguDanaN3',15,2);
            $table->decimal('PaguDanaN4',15,2);
            $table->decimal('PaguDanaN5',15,2); 
            
            $table->string('Descr')->nullable();
            $table->year('TA');            
            $table->boolean('Locked')->default(0);

            $table->primary('IndikatorKinerjaID');

            $table->index('PrioritasKebijakanKabID');

            $table->foreign('PrioritasKebijakanKabID')
                    ->references('PrioritasKebijakanKabID')
                    ->on('tmPrioritasKebijakanKab')
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
        Schema::dropIfExists('trIndikatorKinerja');
        Schema::dropIfExists('tmPrioritasKebijakanKab');
    }
}
