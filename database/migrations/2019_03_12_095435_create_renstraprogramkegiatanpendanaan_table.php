<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenstraprogramkegiatanpendanaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('trRenstraProgramKegiatanPendanaan', function (Blueprint $table) {
            $table->string('RenstraProgramKegiatanPendanaanID',19);
            $table->string('RenstraSasaranID',19);            
            $table->string('UrsID',19);
            $table->string('PrgID',19);
            $table->string('KgtID',19);
            $table->string('OrgIDRPJMD',19);

            $table->text('output');
            $table->string('Satuan'); 
        
            $table->string('KondisiAwal',6,2);
            $table->string('TargetN1',6,2);
            $table->string('TargetN2',6,2);
            $table->string('TargetN3',6,2);
            $table->string('TargetN4',6,2);
			$table->string('TargetN5',6,2);
			$table->decimal('PaguDanaN1',15,2)->default(0);
            $table->decimal('PaguDanaN2',15,2)->default(0);
            $table->decimal('PaguDanaN3',15,2)->default(0);
            $table->decimal('PaguDanaN4',15,2)->default(0);
			$table->decimal('PaguDanaN5',15,2)->default(0);           
			$table->string('KondisiAkhirTarget',6,2);  
            $table->decimal('KondisiAkhirPaguDana',15,2)->default(0);            
			$table->string('Lokasi');

            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);
            $table->string('RenstraProgramKegiatanPendanaanID_Src',19)->nullable();

            $table->timestamps();

            $table->primary('RenstraProgramKegiatanPendanaanID');

            $table->index('RenstraSasaranID');
            $table->index('UrsID');
            $table->index('PrgID');
            $table->index('KgtID');
            $table->index('OrgIDRPJMD');
            $table->index('RenstraProgramKegiatanPendanaanID_Src');

            $table->foreign('RenstraSasaranID')
                    ->references('RenstraSasaranID')
                    ->on('tmRenstraSasaran')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            
            $table->foreign('KgtID')
                    ->references('KgtID')
                    ->on('tmKgt')
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
        Schema::dropIfExists('trRenstraProgramKegiatanPendanaan');
    }
}
