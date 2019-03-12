<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVOrganisasiProgramView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_organisasi_program AS
            SELECT 
				orgprg."orgProgramID",
				orgprg."OrgID",	
				orgprg."PrgID",			
				CONCAT(kelompok_urusan."Kd_Urusan", \'.\', urusan."Kd_Bidang") AS "Kode_Bidang",
				kelompok_urusan."Nm_Urusan", 
				urusan."Nm_Bidang", 
				CASE 
					WHEN urusan."UrsID" IS NOT NULL OR  kelompok_urusan."KUrsID" IS NOT NULL THEN
						CONCAT(kelompok_urusan."Kd_Urusan",\'.\',urusan."Kd_Bidang",\'.\',organisasi."OrgCd")
				END AS kode_organisasi,
				organisasi."OrgNm", 
				organisasi."Alamat", 
				organisasi."NamaKepalaSKPD", 
				organisasi."NIPKepalaSKPD",
				program."Kd_Prog",
				CASE 
					WHEN urusan."UrsID" IS NOT NULL OR  kelompok_urusan."KUrsID" IS NOT NULL THEN
						CONCAT(kelompok_urusan."Kd_Urusan",\'.\',urusan."Kd_Bidang",\'.\',program."Kd_Prog")
				END AS kode_program,
				program."PrgNm", 
				program."Jns", 
				program."TA", 
				program."Locked"
				 FROM "trOrgProgram" orgprg
				 LEFT JOIN "tmOrg" AS organisasi ON organisasi."OrgID"=orgprg."OrgID"
				 LEFT JOIN "tmPrg" AS program ON program."PrgID"=orgprg."PrgID"
				 LEFT JOIN "trUrsPrg" AS urs_program ON program."PrgID"=urs_program."PrgID"
				 LEFT JOIN "tmUrs" AS urusan ON urs_program."UrsID"=urusan."UrsID"
				 LEFT JOIN "tmKUrs" AS kelompok_urusan ON kelompok_urusan."KUrsID"=urusan."KUrsID"			 
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_organisasi_program');
    }
}
