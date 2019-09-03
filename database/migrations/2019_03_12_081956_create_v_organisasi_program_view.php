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
				A."orgProgramID",
				A."OrgIDRPJMD",
				A."PrgID",
				CONCAT(D."Kd_Urusan",  \'.\', C."Kd_Bidang",\'.\', B."OrgCd") AS kode_organisasi,
				B."OrgCd",
				B."OrgNm",
				H."Kd_Urusan",
				H."Nm_Urusan", 
				G."Kd_Bidang",
				CASE 
					WHEN G."UrsID" IS NOT NULL OR  H."KUrsID" IS NOT NULL THEN
						CONCAT(H."Kd_Urusan", \'.\',G."Kd_Bidang")		
				END AS "Kode_Bidang",
				G."Nm_Bidang",
				E."Kd_Prog",
				CASE 
					WHEN G."UrsID" IS NOT NULL OR  H."KUrsID" IS NOT NULL THEN
						CONCAT(H."Kd_Urusan", \'.\',G."Kd_Bidang", \'.\',E."Kd_Prog")
					ELSE
						CONCAT(\'0.00.\',E."Kd_Prog")
				END AS kode_program,
				E."PrgNm", 
				E."Jns", 
				A."TA", 
				E."Locked",
				A."created_at",
				A."updated_at"
			FROM "trOrgProgram" A
			JOIN "tmOrgRPJMD" B ON A."OrgIDRPJMD"=B."OrgIDRPJMD"
			JOIN "tmUrs" C ON B."UrsID"=C."UrsID"
			JOIN "tmKUrs" D ON D."KUrsID"=C."KUrsID"
			JOIN "tmPrg" E ON E."PrgID"=A."PrgID"
			LEFT JOIN "trUrsPrg" AS F ON F."PrgID"=E."PrgID"
			LEFT JOIN "tmUrs" AS G ON G."UrsID"=F."UrsID"
			LEFT JOIN "tmKUrs" AS H ON H."KUrsID"=G."KUrsID"	
			ORDER BY
				D."Kd_Urusan"::int ASC,
                C."Kd_Bidang"::int ASC,
                B."OrgCd"::int ASC,
                H."Kd_Urusan"::int ASC NULLS FIRST,
                G."Kd_Bidang"::int ASC NULLS FIRST,
                E."Kd_Prog"::int ASC
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
