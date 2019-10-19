<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVOrgSasaranRPjmdView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_opd_sasaran_rpjmd AS
            SELECT 
                DISTINCT A."OrgIDRPJMD",B."PrioritasSasaranKabID",	
                C."Kd_Sasaran",
                C."Nm_Sasaran",
                C."TA"
            FROM "trOrgProgram" A
            JOIN "trRpjmdProgramPembangunan" B ON A."PrgID"=B."PrgID"
            JOIN "tmPrioritasSasaranKab" C ON C."PrioritasSasaranKabID"=B."PrioritasSasaranKabID"
        ');				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_opd_sasaran_rpjmd');
    }
}
