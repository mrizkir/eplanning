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

            $table->decimal('Target1',15,2);
            $table->decimal('Target2',15,2);

            $table->decimal('Sasaran_AngkaSetelah',15,2);
            $table->string('Sasaran_UraianSetelah');

            $table->date('Tgl_Posting');

            $table->string('Descr')->nullable();
            $table->year('TA'); 
            $table->boolean('status')->default(0);
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
            
            $table->decimal('Target_Angka',15,2);
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

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trRKPDIndikator');
        Schema::dropIfExists('trRKPD');
    }
}
