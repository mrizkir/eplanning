<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsulanrkpdabpdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trRKPD', function (Blueprint $table) {
        
            $table->string('RKPDID',19);
            $table->string('OrgID',19);
            $table->string('SOrgID',19);
            $table->string('KgtID',19);
            $table->string('SumberDanaID',19);
            $table->string('NamaIndikator');

            $table->string('Sasaran_Uraian1');
            $table->string('Sasaran_Uraian2');
            $table->decimal('Sasaran_Angka1',15,2);
            $table->decimal('Sasaran_Angka2',15,2);

            $table->decimal('NilaiUsulan1',15,2);
            $table->decimal('NilaiUsulan2',15,2);

            $table->decimal('Target1',3,2);
            $table->decimal('Target2',3,2);

            $table->decimal('Sasaran_AngkaSetelah',15,2);
            $table->string('Sasaran_UraianSetelah');

            $table->date('Tgl_Posting');

            $table->string('Descr')->nullable();
            $table->year('TA'); 
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('EntryLvl')->default(0);
            $table->tinyInteger('Privilege')->default(0);

            $table->timestamps();

            $table->primary('RKPDID');
            $table->index('OrgID');
            $table->index('SOrgID');
            $table->index('KgtID');
            $table->index('SumberDanaID');

            $table->foreign('OrgID')
                    ->references('OrgID')
                    ->on('tmOrg')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('SOrgID')
                    ->references('SOrgID')
                    ->on('tmSOrg')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('KgtID')
                    ->references('KgtID')
                    ->on('tmKgt')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('SumberDanaID')
                    ->references('SumberDanaID')
                    ->on('tmSumberDana')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

        });

        Schema::create('trRKPDIndikator', function (Blueprint $table) {
            $table->string('RKPDIndikatorID',19);
            $table->string('RKPDID',19);
            $table->string('IndikatorKinerjaID',19);
            
            $table->decimal('Target_Angka',3,2);
            $table->string('Target_Uraian');
            $table->year('Tahun');
            $table->string('Descr')->nullable();

            $table->tinyInteger('Privilege')->default(0); 

            $table->primary('RKPDIndikatorID');
            $table->index('RKPDID');
            $table->index('IndikatorKinerjaID');

            $table->foreign('RKPDID')
                    ->references('RKPDID')
                    ->on('trRKPD')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('IndikatorKinerjaID')
                    ->references('IndikatorKinerjaID')
                    ->on('trIndikatorKinerja')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            

        });

        Schema::create('trRKPDRinc', function (Blueprint $table) {
                $table->string('RKPDRincID',19);
                $table->string('RKPDID',19);
                $table->string('RenjaRincID',19);
                $table->string('PMProvID',19)->nullable();
                $table->string('PmKotaID',19)->nullable();
                $table->string('PmKecamatanID',19);
                $table->string('PmDesaID',19);
                $table->string('PokPirID',19);

                $table->text('Uraian');
                $table->string('No',4);
                $table->string('Sasaran_Uraian1');
                $table->string('Sasaran_Uraian2');
                $table->decimal('Sasaran_Angka1',15,2);
                $table->decimal('Sasaran_Angka2',15,2);

                $table->decimal('NilaiUsulan1',15,2);
                $table->decimal('NilaiUsulan2',15,2);

                $table->decimal('Target1',3,2);
                $table->decimal('Target2',3,2);

                $table->date('Tgl_Posting');
                $table->boolean('isReses')->default(0);
                $table->string('isReses_Uraian',255);
                $table->boolean('isSKPD')->default(0);
                $table->string('Descr')->nullable();
                $table->year('TA'); 
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('EntryLvl')->default(0);
                $table->tinyInteger('Privilege')->default(0);

                $table->timestamps();

                $table->primary('RKPDRincID');
                $table->index('RKPDID');
                $table->index('RenjaRincID');
                $table->index('PmKecamatanID');
                $table->index('PmDesaID');
                $table->index('PokPirID');

                $table->foreign('RKPDID')
                        ->references('RKPDID')
                        ->on('trRKPD')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

                $table->foreign('RenjaRincID')
                        ->references('RenjaRincID')
                        ->on('trRenjaRinc')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

                $table->foreign('PMProvID')
                        ->references('PMProvID')
                        ->on('tmPMProv')
                        ->onDelete('set null')
                        ->onUpdate('cascade');

                $table->foreign('PmKotaID')
                        ->references('PmKotaID')
                        ->on('tmPmKota')
                        ->onDelete('set null')
                        ->onUpdate('cascade');

                $table->foreign('PmKecamatanID')
                        ->references('PmKecamatanID')
                        ->on('tmPmKecamatan')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

                $table->foreign('PmDesaID')
                        ->references('PmDesaID')
                        ->on('tmPmDesa')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

                $table->foreign('PokPirID')
                        ->references('PokPirID')
                        ->on('trPokPir')
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
        Schema::dropIfExists('trRKPDRinc');
        Schema::dropIfExists('trRKPDIndikator');
        Schema::dropIfExists('trRKPD');
    }
}
