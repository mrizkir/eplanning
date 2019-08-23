<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpjmdprogrampembangunanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trRpjmdProgramPembangunan', function (Blueprint $table) {
            $table->string('RPJMDProgramPembangunanID',19);
            $table->string('PrioritasSasaranKabID',19);            
            $table->string('UrsID',19)->nullable();  
            $table->string('PrgID',19);            
            $table->decimal('PaguDanaN1',15,2)->default(0);
            $table->decimal('PaguDanaN2',15,2)->default(0);
            $table->decimal('PaguDanaN3',15,2)->default(0);
            $table->decimal('PaguDanaN4',15,2)->default(0);
            $table->decimal('PaguDanaN5',15,2)->default(0);        
            $table->decimal('KondisiAkhirPaguDana',15,2)->default(0); 
            $table->string('Descr')->nullable();
            $table->year('TA');            
            $table->boolean('Locked')->default(0);

            $table->timestamps();
            
            $table->primary('RPJMDProgramPembangunanID');
            $table->index('PrioritasSasaranKabID');
            $table->index('UrsID');

            $table->foreign('PrioritasSasaranKabID')
                    ->references('PrioritasSasaranKabID')
                    ->on('tmPrioritasSasaranKab')
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
