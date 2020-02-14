<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsulanrkpdabpd90Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trRKPD90', function (Blueprint $table) {
            $table->string('RKPDID',19);
            $table->string('RenjaID',19)->nullable();
            $table->string('OrgID',19);
            $table->string('SOrgID',19);
            $table->string('SubKgtID',19);
            $table->string('SumberDanaID',19);                
            $table->text('NamaIndikator');

            $table->text('Sasaran_Uraian1');
            $table->text('Sasaran_Uraian2')->nullable();
            $table->text('Sasaran_Uraian3')->nullable();
            $table->text('Sasaran_Uraian4')->nullable();

            $table->decimal('Sasaran_Angka1',15,2); //RKPD Murni
            $table->decimal('Sasaran_Angka2',15,2)->nullable(); //PEMBAHASAN RKPD
            $table->decimal('Sasaran_Angka3',15,2)->nullable(); // RKPD PERUBAHAN
            $table->decimal('Sasaran_Angka4',15,2)->nullable(); // PEMBAHASAN RKPD PERUBAHAN

            $table->decimal('NilaiUsulan1',15,2); //RKPD Murni
            $table->decimal('NilaiUsulan2',15,2)->nullable();//PEMBAHASAN RKPD
            $table->decimal('NilaiUsulan3',15,2)->nullable(); // RKPD PERUBAHAN
            $table->decimal('NilaiUsulan4',15,2)->nullable(); // PEMBAHASAN RKPD PERUBAHAN

            $table->decimal('Target1',15,2); //RKPD Murni
            $table->decimal('Target2',15,2)->nullable(); //PEMBAHASAN RKPD
            $table->decimal('Target3',15,2)->nullable(); // RKPD PERUBAHAN
            $table->decimal('Target4',15,2)->nullable(); // PEMBAHASAN RKPD PERUBAHAN

            $table->decimal('Sasaran_AngkaSetelah',15,2);
            $table->text('Sasaran_UraianSetelah');

            $table->decimal('NilaiSebelum',15,2)->nullable();
            $table->decimal('NilaiSetelah',15,2)->nullable();

            $table->date('Tgl_Posting');

            $table->text('Descr')->nullable();
            $table->year('TA'); 
            $table->tinyInteger('Status')->default(0);
            $table->tinyInteger('Status_Indikator')->default(0);
            $table->tinyInteger('EntryLvl')->default(0);
            $table->tinyInteger('Privilege')->default(0);
            $table->boolean('Locked')->default(0);
            $table->string('RKPDID_Src',19)->nullable();                    
            $table->timestamps();

            $table->primary('RKPDID');
            $table->index('RenjaID');
            $table->index('OrgID');
            $table->index('SOrgID');
            $table->index('SubKgtID');
            $table->index('SumberDanaID');
            $table->index('RKPDID_Src');

            $table->foreign('RenjaID')
                        ->references('RenjaID')
                        ->on('trRenja90')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

            $table->foreign('RKPDID_Src')
                        ->references('RKPDID')
                        ->on('trRKPD90')
                        ->onDelete('set null')
                        ->onUpdate('cascade');        

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

            $table->foreign('SubKgtID')
                    ->references('SubKgtID')
                    ->on('tmSubKgt')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('SumberDanaID')
                    ->references('SumberDanaID')
                    ->on('tmSumberDana')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

        });

        Schema::create('trRKPDIndikator90', function (Blueprint $table) {
            $table->string('RKPDIndikatorID',19);            
            $table->string('RKPDID',19);
            $table->string('IndikatorKinerjaID',19);
            
            $table->decimal('Target_Angka',15,2);
            $table->text('Target_Uraian');
            $table->string('Descr')->nullable();
            $table->year('TA');

            $table->tinyInteger('Privilege')->default(0);                        
            $table->boolean('Locked')->default(0);
            $table->string('RKPDIndikatorID_Src',19)->nullable(); 
            $table->timestamps();

            $table->primary('RKPDIndikatorID');
            $table->index('RKPDID');
            $table->index('IndikatorKinerjaID');
            $table->index('RKPDIndikatorID_Src');

            $table->foreign('RKPDIndikatorID_Src')
                        ->references('RKPDIndikatorID')
                        ->on('trRKPDIndikator90')
                        ->onDelete('set null')
                        ->onUpdate('cascade');
                
            $table->foreign('RKPDID')
                    ->references('RKPDID')
                    ->on('trRKPD90')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('IndikatorKinerjaID')
                    ->references('IndikatorKinerjaID')
                    ->on('trIndikatorKinerja')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            

        });

        Schema::create('trRKPDRinc90', function (Blueprint $table) {
                $table->string('RKPDRincID',19);
                $table->string('RKPDID',19);
                $table->string('RenjaRincID',19)->nullable();
                $table->string('PMProvID',19)->nullable();
                $table->string('PmKotaID',19)->nullable();
                $table->string('PmKecamatanID',19)->nullable();
                $table->string('PmDesaID',19)->nullable();
                $table->string('UsulanKecID',19)->nullable();
                $table->string('PokPirID',19)->nullable();

                $table->text('Uraian');
                $table->string('No',4);
                $table->text('Sasaran_Uraian1');
                $table->text('Sasaran_Uraian2')->nullable();
                $table->text('Sasaran_Uraian3')->nullable();
                $table->text('Sasaran_Uraian4')->nullable();

                $table->decimal('Sasaran_Angka1',15,2);
                $table->decimal('Sasaran_Angka2',15,2)->nullable();
                $table->decimal('Sasaran_Angka3',15,2)->nullable();
                $table->decimal('Sasaran_Angka4',15,2)->nullable();

                $table->decimal('NilaiUsulan1',15,2);
                $table->decimal('NilaiUsulan2',15,2)->nullable();
                $table->decimal('NilaiUsulan3',15,2)->nullable();
                $table->decimal('NilaiUsulan4',15,2)->nullable();

                $table->decimal('Target1',15,2);
                $table->decimal('Target2',15,2)->nullable();
                $table->decimal('Target3',15,2)->nullable();
                $table->decimal('Target4',15,2)->nullable();

                $table->string('Lokasi')->nullable();
                $table->string('Latitude')->nullable();
                $table->string('Longitude')->nullable();
                
                $table->date('Tgl_Posting');
                $table->boolean('isReses')->nullable();
                $table->string('isReses_Uraian',255)->nullable();
                $table->boolean('isSKPD')->nullable();
                $table->text('Descr')->nullable();
                $table->year('TA'); 
                $table->tinyInteger('Status')->default(0);
                $table->tinyInteger('EntryLvl')->default(0);
                $table->tinyInteger('Privilege')->default(0);                
                $table->boolean('Locked')->default(0);
                $table->string('RKPDRincID_Src',19)->nullable();
                $table->timestamps();

                $table->primary('RKPDRincID');
                $table->index('RKPDID');
                $table->index('RenjaRincID');
                $table->index('PmKecamatanID');
                $table->index('PmDesaID');
                $table->index('UsulanKecID');
                $table->index('PokPirID');
                $table->index('RKPDRincID_Src');

                $table->foreign('RKPDRincID_Src')
                        ->references('RKPDRincID')
                        ->on('trRKPDRinc90')
                        ->onDelete('set null')
                        ->onUpdate('cascade');

                $table->foreign('RKPDID')
                        ->references('RKPDID')
                        ->on('trRKPD90')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

                $table->foreign('RenjaRincID')
                        ->references('RenjaRincID')
                        ->on('trRenjaRinc90')
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

                $table->foreign('UsulanKecID')
                        ->references('UsulanKecID')
                        ->on('trUsulanKec')
                        ->onDelete('set null')
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
        Schema::dropIfExists('trRKPDRinc90');
        Schema::dropIfExists('trRKPDIndikator90');
        Schema::dropIfExists('trRKPD90');
    }
}
