<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersopdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usersopd', function (Blueprint $table) {
            $table->increments('useropd');            
            $table->integer('id');            
            $table->year('ta');          
            $table->string('OrgID',19);  
            $table->string('OrgNm');
            $table->string('SOrgID',19)->nullable();  
            $table->string('SOrgNm')->nullable();              
            $table->boolean('locked')->default(0);              
            $table->timestamps();
            
            $table->index('id');
            $table->index('OrgID');  
            $table->index('SOrgID');

            $table->foreign('id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usersopd');
    }
}
