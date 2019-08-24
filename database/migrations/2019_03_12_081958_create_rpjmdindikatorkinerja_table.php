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
            $table->string('UrsID',19)->nullable();  //bila yang dipilih seluruh urusan maka isinya null
            $table->string('PrgID',19);            
            $table->string('OrgIDRPJMD',19);         
            $table->text('NamaIndikator');
            $table->string('Satuan'); 
            $table->string('KondisiAwal');
            $table->string('TargetN1');
            $table->string('TargetN2');
            $table->string('TargetN3');
            $table->string('TargetN4');
            $table->string('TargetN5');   
            $table->decimal('PaguDanaN1',15,2)->default(0);
            $table->decimal('PaguDanaN2',15,2)->default(0);
            $table->decimal('PaguDanaN3',15,2)->default(0);
            $table->decimal('PaguDanaN4',15,2)->default(0);
            $table->decimal('PaguDanaN5',15,2)->default(0);   
            $table->string('KondisiAkhirTarget');     
            $table->decimal('KondisiAkhirPaguDana',15,2)->default(0); 
            $table->string('Descr')->nullable();
            $table->year('TA');            
            $table->boolean('Locked')->default(0);

            $table->timestamps();
            
            $table->primary('IndikatorKinerjaID');
            $table->index('UrsID');
            $table->index('PrgID'); 

            $table->foreign('PrgID')
                    ->references('PrgID')
                    ->on('tmPrg')
                    ->onDelete('cascade')
                    ->onUpdate('cascade'); 
                    
            $table->foreign('OrgIDRPJMD')
                    ->references('OrgIDRPJMD')
                    ->on('tmOrgRPJMD')
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
