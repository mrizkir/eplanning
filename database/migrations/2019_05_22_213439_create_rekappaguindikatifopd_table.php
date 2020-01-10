<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRekappaguindikatifopdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trRekapPaguIndikatifOPD', function (Blueprint $table) {
            $table->string('OrgID',19);            
            $table->string('Kode_Organisasi');           
            $table->string('OrgNm');           
            $table->decimal('Jumlah1',15,2)->default(0);    
            $table->decimal('Jumlah2',15,2)->default(0);   
            
            $table->decimal('prarenja1',15,2)->default(0);    
            $table->integer('jumlah_program1')->default(0); 
            $table->integer('jumlah_kegiatan1')->default(0); 

            $table->decimal('rakorbidang1',15,2)->default(0);    
            $table->integer('jumlah_program2')->default(0);  
            $table->integer('jumlah_kegiatan2')->default(0);  

            $table->decimal('forumopd1',15,2)->default(0);    
            $table->integer('jumlah_program3')->default(0); 
            $table->integer('jumlah_kegiatan3')->default(0); 

            $table->decimal('musrenkab1',15,2)->default(0);    
            $table->integer('jumlah_program4')->default(0); 
            $table->integer('jumlah_kegiatan4')->default(0); 

            $table->decimal('renjafinal1',15,2)->default(0);    
            $table->integer('jumlah_program5')->default(0); 
            $table->integer('jumlah_kegiatan5')->default(0); 

            $table->decimal('rkpd1',15,2)->default(0);    
            $table->integer('jumlah_program6')->default(0); 
            $table->integer('jumlah_kegiatan6')->default(0); 
            $table->decimal('rkpd2',15,2)->default(0); 
            $table->integer('jumlah_program7')->default(0); 
            $table->integer('jumlah_kegiatan7')->default(0); 
            
            $table->string('Descr')->nullable();
            $table->year('TA');

            $table->timestamps();
            $table->primary('OrgID');

            $table->foreign('OrgID')
                ->references('OrgID')
                ->on('tmOrg')
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
        Schema::dropIfExists('trRekapPaguIndikatifOPD');
    }
}
