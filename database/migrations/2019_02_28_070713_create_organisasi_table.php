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
        Schema::create('tmOrg', function (Blueprint $table) {
            $table->string('OrgID',19);
            $table->string('UrsID',19);
            $table->string('OrgCd',4);
            $table->string('OrgNm');
            $table->string('Alamat');
            $table->string('NamaKepalaSKPD');
            $table->string('NIPKepalaSKPD');
            $table->string('Descr')->nullable();
            $table->year('TA');       
            $table->string('OrgID_Src',19)->nullable();     
            $table->timestamps();

            $table->primary('OrgID');
            $table->index('UrsID');

            $table->foreign('UrsID')
                ->references('UrsID')
                ->on('tmUrs')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('OrgID')
                ->references('OrgID')
                ->on('tmOrg')
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
        Schema::dropIfExists('tmOrg');
    }
}
