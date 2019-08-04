<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubOrganisasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmSOrg', function (Blueprint $table) {
            $table->string('SOrgID',19);
            $table->string('OrgID',19);
            $table->string('SOrgCd',4);
            $table->string('SOrgNm');
            $table->string('SOrgAlias');
            $table->string('Alamat');
            $table->string('NamaKepalaSKPD');
            $table->string('NIPKepalaSKPD');
            $table->string('Descr')->nullable();
            $table->year('TA');       
            $table->string('SOrgID_Src',19)->nullable();     
            $table->timestamps();

            $table->primary('SOrgID');
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
        Schema::dropIfExists('tmSOrg');
    }
}
