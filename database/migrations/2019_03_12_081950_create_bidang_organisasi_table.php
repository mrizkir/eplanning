<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidangOrganisasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmBidangOrgRPJMD', function (Blueprint $table) {
            $table->string('OrgBidangIDRPJMD',19);
            $table->string('OrgIDRPJMD',19);
            $table->string('OrgBidangCd',4);
            $table->string('OrgBidangNm');
            $table->string('OrgBidangAlias');            
            $table->string('Descr')->nullable();
            $table->year('TA');                   
            $table->timestamps();

            $table->primary('OrgBidangIDRPJMD');
            $table->index('OrgIDRPJMD');

            $table->foreign('OrgIDRPJMD')
                ->references('OrgIDRPJMD')
                ->on('tmOrgRPJMD')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
        });       

        Schema::create('tmBidangOrg', function (Blueprint $table) {
            $table->string('OrgBidangID',19);
            $table->string('OrgID',19);
            $table->string('OrgBidangCd',4);
            $table->string('OrgBidangNm');
            $table->string('OrgBidangAlias');
            $table->string('Alamat');
            $table->string('NamaKepalaBidang');
            $table->string('NIPKepalaBidang');
            $table->string('Descr')->nullable();
            $table->year('TA');       
            $table->timestamps();

            $table->primary('OrgBidangID');
            $table->index('OrgID');

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
        Schema::dropIfExists('tmBidangOrg');
        Schema::dropIfExists('tmBidangOrgRPJMD');
    }
}
