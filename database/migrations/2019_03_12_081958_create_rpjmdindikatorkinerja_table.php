<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpjmdindikatorkinerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trIndikatorKinerja', function (Blueprint $table) {
            $table->string('IndikatorKinerjaID',19);
            $table->string('PrioritasKebijakanKabID',19);            
            $table->string('ProgramKebijakanID',19);            
            $table->string('UrsID',19); 
            $table->string('PrgID',19);
            $table->json('OrgID',19);                    
            $table->text('NamaIndikator')->nullable();             
            $table->decimal('KondisiAwal',6,2)->default(0);
            $table->string('TargetN1')->default(0);
            $table->string('TargetN2')->default(0);
            $table->string('TargetN3')->default(0);
            $table->string('TargetN4')->default(0);
            $table->string('TargetN5')->default();
            $table->decimal('PaguDanaN1',15,2)->default(0);
            $table->decimal('PaguDanaN2',15,2)->default(0);
            $table->decimal('PaguDanaN3',15,2)->default(0);
            $table->decimal('PaguDanaN4',15,2)->default(0);
            $table->decimal('PaguDanaN5',15,2)->default(0);             
            $table->decimal('KondisiAkhirTarget',6,2)->default(0);
            $table->decimal('KondisiAkhirPaguDana',15,2)->default(0); 
            $table->string('Satuan',10);           
            $table->string('Operator',10);                  
            $table->string('Descr')->nullable();
            $table->year('TA');            
            $table->boolean('Locked')->default(0);

            $table->timestamps();
            
            $table->primary('IndikatorKinerjaID');
            $table->index('PrioritasKebijakanKabID');
            $table->index('ProgramKebijakanID');
            $table->index('UrsID');

            $table->foreign('PrioritasKebijakanKabID')
                    ->references('PrioritasKebijakanKabID')
                    ->on('tmPrioritasKebijakanKab')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');                    

            $table->foreign('ProgramKebijakanID')
                    ->references('ProgramKebijakanID')
                    ->on('tmPrioritasProgramKebijakan')
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
        Schema::dropIfExists('trIndikatorKinerja');
    }
}
