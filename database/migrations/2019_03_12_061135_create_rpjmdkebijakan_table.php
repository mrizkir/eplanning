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
            $table->string('PrioritasKebijakanKabID',19)->nullabble();            
            $table->string('UrsID',19); 
            $table->string('PrgID',19);
            $table->string('OrgID',19)->nullabble(); 
            $table->string('OrgID2',19)->nullabble(); 
            $table->string('OrgID3',19)->nullabble(); 
                        
            $table->string('Nm_Indikator'); 
            
            $table->year('TA_N');

            $table->string('TargetN')->nullable();
            $table->string('TargetN1')->nullable();
            $table->string('TargetN2')->nullable();
            $table->string('TargetN3')->nullable();
            $table->string('TargetN4')->nullable();
            $table->string('TargetN5')->nullable();

            $table->decimal('PaguDanaN1',15,2)->default(0);
            $table->decimal('PaguDanaN2',15,2)->default(0);
            $table->decimal('PaguDanaN3',15,2)->default(0);
            $table->decimal('PaguDanaN4',15,2)->default(0);
            $table->decimal('PaguDanaN5',15,2)->default(0); 
            
            $table->string('Descr')->nullable();
            $table->year('TA');            
            $table->boolean('Locked')->default(0);

            $table->timestamps();
            
            $table->primary('IndikatorKinerjaID');

            $table->index('PrioritasKebijakanKabID');

            $table->foreign('PrioritasKebijakanKabID')
                    ->references('PrioritasKebijakanKabID')
                    ->on('tmPrioritasKebijakanKab')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

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
            
            $table->foreign('OrgID')
                    ->references('OrgID')
                    ->on('tmOrg')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

            $table->foreign('OrgID2')
                    ->references('OrgID')
                    ->on('tmOrg')
                    ->onDelete('set null')
                    ->onUpdate('cascade');


            $table->foreign('OrgID3')
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
        Schema::dropIfExists('trIndikatorKinerja');
        Schema::dropIfExists('tmPrioritasKebijakanKab');
    }
}
