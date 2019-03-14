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
            $table->string('NamaIndikator');

            $table->string('Sasaran_Uraian1');
            $table->string('Sasaran_Uraian2');
            $table->string('Sasaran_Uraian3');
            $table->string('Sasaran_Uraian4');
            $table->string('Sasaran_Uraian5');
            $table->string('Sasaran_Uraian6');

            $table->decimal('Sasaran_Angka1',15,2);
            $table->decimal('Sasaran_Angka2',15,2);
            $table->decimal('Sasaran_Angka3',15,2);
            $table->decimal('Sasaran_Angka4',15,2);
            $table->decimal('Sasaran_Angka5',15,2);            
            $table->decimal('Sasaran_Angka6',15,2);            
            
            $table->decimal('Target1',15,2);
            $table->decimal('Target2',15,2);
            $table->decimal('Target3',15,2);
            $table->decimal('Target4',15,2);
            $table->decimal('Target5',15,2);
            $table->decimal('Target6',15,2);

            $table->decimal('NilaiUsulan1',15,2);
            $table->decimal('NilaiUsulan2',15,2);
            $table->decimal('NilaiUsulan3',15,2);   
            $table->decimal('NilaiUsulan4',15,2);  
            $table->decimal('NilaiUsulan5',15,2);  
            $table->decimal('NilaiUsulan6',15,2);  

            $table->decimal('Sasaran_AngkaSetelah',15,2);
            $table->string('Sasaran_UraianSetelah');

            $table->decimal('NilaiSebelum',15,2);
            $table->decimal('NilaiSetelah',15,2);

            $table->string('Descr')->nullable();
            $table->year('TA');            
            $table->boolean('status')->default(0);
            $table->tinyInteger('Privilege')->default(0);
            $table->boolean('Locked')->default(0);
           
            $table->timestamps();

            $table->primary('RenjaID');
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

        Schema::create('trRenjaIndikator', function (Blueprint $table) {
            $table->string('RenjaIndikatorID',19);
            $table->string('IndikatorKinerjaID',19);
            $table->string('RenjaID',19);
            $table->decimal('Target_Angka',15,2);
            $table->string('Target_Uraian');
            $table->year('Tahun');

            $table->string('Descr')->nullable();
            $table->tinyInteger('Privilege')->default(0); 
            $table->year('TA');

            $table->timestamps();

            $table->primary('RenjaIndikatorID');
            $table->index('IndikatorKinerjaID');
            $table->index('RenjaID');


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
                $table->string('UsulanKecID',19);
                $table->string('PmKecamatanID',19);
                $table->string('PmDesaID',19);
                $table->string('PokPirID',19);
            
                $table->string('Uraian');
                $table->tinyInteger('No');
               
                $table->string('Sasaran_Uraian1');
                $table->string('Sasaran_Uraian2');
                $table->string('Sasaran_Uraian3');
                $table->string('Sasaran_Uraian4');
                $table->string('Sasaran_Uraian5');
                $table->string('Sasaran_Uraian6');
    
                $table->decimal('Sasaran_Angka1',15,2);
                $table->decimal('Sasaran_Angka2',15,2);
                $table->decimal('Sasaran_Angka3',15,2);
                $table->decimal('Sasaran_Angka4',15,2);
                $table->decimal('Sasaran_Angka5',15,2);            
                $table->decimal('Sasaran_Angka6',15,2);            
                
                $table->decimal('Target1',15,2);
                $table->decimal('Target2',15,2);
                $table->decimal('Target3',15,2);
                $table->decimal('Target4',15,2);
                $table->decimal('Target5',15,2);
                $table->decimal('Target6',15,2);

                $table->decimal('Jumlah1',15,2);
                $table->decimal('Jumlah2',15,2);
                $table->decimal('Jumlah3',15,2);
                $table->decimal('Jumlah4',15,2);
                $table->decimal('Jumlah5',15,2);
                $table->decimal('Jumlah6',15,2);

                $table->boolean('isReses')->default(0);
                $table->string('isReses_Uraian');
                $table->boolean('isSKPD')->default(0);
                $table->tinyInteger('Status');
                $table->tinyInteger('Prioritas');            
                $table->string('Descr')->nullable();
                $table->tinyInteger('Privilege')->default(0);
                $table->year('TA');

                $table->timestamps();

                $table->primary('RenjaRincID');
                $table->index('RenjaID');
                $table->index('UsulanKecID');
                $table->index('PmKecamatanID');
                $table->index('PmDesaID');
                $table->index('PokPirID');


                $table->foreign('RenjaID')
                    ->references('RenjaID')
                    ->on('trRenja')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('UsulanKecID')
                    ->references('UsulanKecID')
                    ->on('trUsulanKec')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');                

                $table->foreign('PmDesaID')
                    ->references('PmDesaID')
                    ->on('tmPmDesa')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('PmKecamatanID')
                    ->references('PmKecamatanID')
                    ->on('tmPmKecamatan')
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
        Schema::dropIfExists('trRenjaRinc');
        Schema::dropIfExists('trRenjaIndikator');
        Schema::dropIfExists('trRenja');
    }
}