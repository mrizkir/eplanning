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
        Schema::create('tmKgt', function (Blueprint $table) {
            $table->string('KgtID',19);
            $table->string('RenstraSasaranID',19)->nullable();            
            $table->string('UrsID',19)->nullable();
            $table->string('PrgID',19);
            $table->string('OrgIDRPJMD',19);
            $table->string('OrgBidangIDRPJMD',19)->nullable();

            $table->string('Kd_Keg',4);
            $table->text('KgtNm');
        
            $table->string('KeluaranKegiatan_KondisiAwal');
            $table->string('KeluaranKegiatan_TolakUkur');
            $table->string('KeluaranKegiatan_Satuan');
            $table->string('HasilKegiatan_TolakUkur');
            $table->string('HasilKegiatan_Satuan');
            $table->decimal('TargetN1_Keluaran',6,2)->default(0);
            $table->decimal('TargetN1_Hasil',6,2)->default(0);
            $table->decimal('TargetN2_Keluaran',6,2)->default(0);
            $table->decimal('TargetN2_Hasil',6,2)->default(0);
            $table->decimal('TargetN3_Keluaran',6,2)->default(0);
            $table->decimal('TargetN3_Hasil',6,2)->default(0);
            $table->decimal('TargetN4_Keluaran',6,2)->default(0);
            $table->decimal('TargetN4_Hasil',6,2)->default(0);
			$table->decimal('TargetN5_Keluaran',6,2)->default(0);
			$table->decimal('TargetN5_Hasil',6,2)->default(0);
			$table->decimal('PaguDanaN1',15,2)->default(0);
            $table->decimal('PaguDanaN2',15,2)->default(0);
            $table->decimal('PaguDanaN3',15,2)->default(0);
            $table->decimal('PaguDanaN4',15,2)->default(0);
			$table->decimal('PaguDanaN5',15,2)->default(0);           
			
            $table->decimal('KondisiAkhirTarget_Keluaran',6,2)->default(0);            
            $table->decimal('KondisiAkhirTarget_Hasil',6,2)->default(0);            
            $table->decimal('KondisiAkhirPaguDana',15,2)->default(0);            
			$table->string('Lokasi');

            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);
            $table->string('KgtID_Src',19)->nullable();

            $table->timestamps();

            $table->primary('KgtID');
            $table->index('RenstraSasaranID');
            $table->index('UrsID');
            $table->index('PrgID');
            $table->index('KgtID');
            $table->index('OrgIDRPJMD');
            $table->index('OrgBidangIDRPJMD');
            $table->index('KgtID_Src');

            $table->foreign('RenstraSasaranID')
                    ->references('RenstraSasaranID')
                    ->on('tmRenstraSasaran')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');                       

            $table->foreign('OrgIDRPJMD')
                    ->references('OrgIDRPJMD')
                    ->on('tmOrgRPJMD')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('OrgBidangIDRPJMD')
                    ->references('OrgBidangIDRPJMD')
                    ->on('tmBidangOrgRPJMD')
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
        Schema::dropIfExists('tmKgt');
    }
}
