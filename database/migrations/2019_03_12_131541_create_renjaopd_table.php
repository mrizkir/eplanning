<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenjaopdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trRenja', function (Blueprint $table) {
            $table->string('RenjaID',19);
            $table->string('OrgID',19);
            $table->string('SOrgID',19);
            $table->string('KgtID',19);
            $table->string('SumberDanaID',19);
            $table->text('NamaIndikator');

            $table->text('Sasaran_Uraian1')->nullable();
            $table->text('Sasaran_Uraian2')->nullable();
            $table->text('Sasaran_Uraian3')->nullable();
            $table->text('Sasaran_Uraian4')->nullable();
            $table->text('Sasaran_Uraian5')->nullable();
            $table->text('Sasaran_Uraian6')->nullable();

            $table->decimal('Sasaran_Angka1',15,2)->nullable();
            $table->decimal('Sasaran_Angka2',15,2)->nullable();
            $table->decimal('Sasaran_Angka3',15,2)->nullable();
            $table->decimal('Sasaran_Angka4',15,2)->nullable();
            $table->decimal('Sasaran_Angka5',15,2)->nullable();            
            $table->decimal('Sasaran_Angka6',15,2)->nullable();            
            
            $table->decimal('Target1',10,2)->nullable();
            $table->decimal('Target2',10,2)->nullable();
            $table->decimal('Target3',10,2)->nullable();
            $table->decimal('Target4',10,2)->nullable();
            $table->decimal('Target5',10,2)->nullable();
            $table->decimal('Target6',10,2)->nullable();

            $table->decimal('NilaiUsulan1',15,2)->nullable();
            $table->decimal('NilaiUsulan2',15,2)->nullable();
            $table->decimal('NilaiUsulan3',15,2)->nullable();   
            $table->decimal('NilaiUsulan4',15,2)->nullable();  
            $table->decimal('NilaiUsulan5',15,2)->nullable();  
            $table->decimal('NilaiUsulan6',15,2)->nullable();  

            $table->decimal('Sasaran_AngkaSetelah',15,2)->nullable();
            $table->text('Sasaran_UraianSetelah')->nullable();

            $table->decimal('NilaiSebelum',15,2)->nullable();
            $table->decimal('NilaiSetelah',15,2)->nullable();

            $table->text('Descr')->nullable();
            $table->year('TA');            
            $table->tinyInteger('Status')->default(0);
            $table->tinyInteger('EntryLvl')->default(0);
            $table->tinyInteger('Privilege')->default(0);                        
            $table->boolean('Locked')->default(0);
            $table->string('RenjaID_Src',19)->nullable();
            $table->timestamps();

            $table->primary('RenjaID');
            $table->index('OrgID');
            $table->index('SOrgID');
            $table->index('KgtID');
            $table->index('SumberDanaID');
            $table->index('RenjaID_Src');

            $table->foreign('RenjaID_Src')
                    ->references('RenjaID')
                    ->on('trRenja')
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

        Schema::create('trRenjaIndikator', function (Blueprint $table) {
            $table->string('RenjaIndikatorID',19);            
            $table->string('IndikatorKinerjaID',19);
            $table->string('RenjaID',19);
            $table->decimal('Target_Angka',10,2);
            $table->string('Target_Uraian');
            $table->year('Tahun');

            $table->string('Descr')->nullable();
            $table->tinyInteger('Privilege')->default(0); 
            $table->year('TA');
            $table->string('RenjaIndikatorID_Src',19)->nullable();
            $table->timestamps();

            $table->primary('RenjaIndikatorID');
            $table->index('IndikatorKinerjaID');
            $table->index('RenjaID');
            $table->index('RenjaIndikatorID_Src');

            $table->foreign('RenjaIndikatorID_Src')
                    ->references('RenjaIndikatorID')
                    ->on('trRenjaIndikator')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

            $table->foreign('IndikatorKinerjaID')
                    ->references('IndikatorKinerjaID')
                    ->on('trIndikatorKinerja')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('RenjaID')
                    ->references('RenjaID')
                    ->on('trRenja')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });

        Schema::create('trRenjaRinc', function (Blueprint $table) {
                $table->string('RenjaRincID',19);
                $table->string('RenjaID',19);
                $table->string('UsulanKecID',19)->nullable();
                $table->string('PMProvID',19);
                $table->string('PmKotaID',19)->nullable();
                $table->string('PmKecamatanID',19)->nullable();
                $table->string('PmDesaID',19)->nullable();
                $table->string('PokPirID',19)->nullable();
            
                $table->text('Uraian');
                $table->string('No',4);
               
                $table->text('Sasaran_Uraian1')->nullable();
                $table->text('Sasaran_Uraian2')->nullable();
                $table->text('Sasaran_Uraian3')->nullable();
                $table->text('Sasaran_Uraian4')->nullable();
                $table->text('Sasaran_Uraian5')->nullable();
                $table->text('Sasaran_Uraian6')->nullable();
    
                $table->decimal('Sasaran_Angka1',15,2)->nullable();
                $table->decimal('Sasaran_Angka2',15,2)->nullable();
                $table->decimal('Sasaran_Angka3',15,2)->nullable();
                $table->decimal('Sasaran_Angka4',15,2)->nullable();
                $table->decimal('Sasaran_Angka5',15,2)->nullable();            
                $table->decimal('Sasaran_Angka6',15,2)->nullable();            
                
                $table->decimal('Target1',10,2)->nullable();
                $table->decimal('Target2',10,2)->nullable();
                $table->decimal('Target3',10,2)->nullable();
                $table->decimal('Target4',10,2)->nullable();
                $table->decimal('Target5',10,2)->nullable();
                $table->decimal('Target6',10,2)->nullable();

                $table->decimal('Jumlah1',15,2)->nullable();
                $table->decimal('Jumlah2',15,2)->nullable();
                $table->decimal('Jumlah3',15,2)->nullable();
                $table->decimal('Jumlah4',15,2)->nullable();
                $table->decimal('Jumlah5',15,2)->nullable();
                $table->decimal('Jumlah6',15,2)->nullable();

                $table->boolean('isReses')->nullable();
                $table->string('isReses_Uraian',5)->nullable();
                $table->boolean('isSKPD')->nullable();
                $table->tinyInteger('Status');
                $table->tinyInteger('EntryLvl')->default(0);
                $table->tinyInteger('Prioritas');            
                $table->text('Descr')->nullable();
                $table->tinyInteger('Privilege')->default(0);
                $table->year('TA');                
                $table->string('RenjaRincID_Src',19)->nullable();
                $table->timestamps();

                $table->primary('RenjaRincID');
                $table->index('RenjaID');
                $table->index('UsulanKecID');
                $table->index('PmKecamatanID');
                $table->index('PmDesaID');
                $table->index('PokPirID');
                $table->index('RenjaRincID_Src');

                $table->foreign('RenjaRincID_Src')
                    ->references('RenjaRincID')
                    ->on('trRenjaRinc')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->foreign('RenjaID')
                    ->references('RenjaID')
                    ->on('trRenja')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('UsulanKecID')
                    ->references('UsulanKecID')
                    ->on('trUsulanKec')
                    ->onDelete('set null')
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
                    ->onDelete('set null')
                    ->onUpdate('cascade');
                    
                $table->foreign('PmDesaID')
                    ->references('PmDesaID')
                    ->on('tmPmDesa')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

                $table->foreign('PokPirID')
                    ->references('PokPirID')
                    ->on('trPokPir')
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
        Schema::dropIfExists('trRenjaRinc');
        Schema::dropIfExists('trRenjaIndikator');
        Schema::dropIfExists('trRenja');
    }
}