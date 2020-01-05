<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganisasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmOrgRPJMD', function (Blueprint $table) {
            $table->string('OrgIDRPJMD',19);
            $table->string('UrsID',19);
            $table->string('OrgCd',4);
            $table->string('OrgNm');
            $table->string('OrgAlias');            
            $table->string('Descr')->nullable();
            $table->year('TA');      
            $table->string('OrgIDRPJMD_Src')->nullable();
            $table->timestamps();

            $table->primary('OrgIDRPJMD');
            $table->index('UrsID');

            $table->foreign('UrsID')
                ->references('UrsID')
                ->on('tmUrs')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
        });       

        Schema::create('tmOrg', function (Blueprint $table) {
            $table->string('OrgID',19);
            $table->string('OrgIDRPJMD',19);
            $table->string('UrsID',19);
            $table->string('OrgCd',4);
            $table->string('OrgNm');
            $table->string('OrgAlias');
            $table->string('Alamat');
            $table->string('NamaKepalaSKPD');
            $table->string('NIPKepalaSKPD');
            $table->string('Descr')->nullable();
            $table->year('TA');       
            $table->string('OrgID_Src',19)->nullable();       
            $table->timestamps();

            $table->primary('OrgID');
            $table->index('OrgIDRPJMD');
            $table->index('UrsID');            

            $table->foreign('UrsID')
                ->references('UrsID')
                ->on('tmUrs')
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
        Schema::dropIfExists('tmOrg');
        Schema::dropIfExists('tmOrgRPJMD');
    }
}
