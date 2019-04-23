<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVUsulanPraRenjaOPDView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_usulan_pra_renja_opd AS
            SELECT 
                A."RenjaID",
                B."RenjaRincID",
                H."Kd_Urusan",
                H."Nm_Urusan",
                G."Kd_Bidang",
                G."Nm_Bidang",
                A."OrgID",
	            A."SOrgID",
                C."OrgNm",
                D."SOrgNm",
                CONCAT(H."Kd_Urusan", \'.\', G."Kd_Bidang", \'.\', F."Kd_Prog", \'.\', E."Kd_Keg") AS kode_kegiatan,
                F."PrgNm",
                E."KgtNm",
                B."No",
                B."Uraian",
                B."Sasaran_Angka1",
                B."Sasaran_Uraian1",
                B."Target1",
                B."Jumlah1",
                B."Prioritas",
                CASE WHEN B."Status"=0 OR B."Status" IS NULL THEN
                    \'DUM\'
                ELSE
                    \'ACC\'
                END AS Status,
                B."Privilege",
                A."EntryLvl",
                A."TA"
            FROM "trRenja" A
                INNER JOIN "trRenjaRinc" B ON A."RenjaID"=B."RenjaID" AND A."TA"=B."TA"
                INNER JOIN "tmOrg" C ON A."OrgID"=C."OrgID" AND A."TA"=C."TA"
                INNER JOIN "tmSOrg" D ON A."SOrgID"=D."SOrgID" AND A."TA"=D."TA"
                INNER JOIN "tmKgt" E ON A."KgtID"=E."KgtID" AND A."TA"=E."TA"
                INNER JOIN "tmPrg" F ON E."PrgID"=F."PrgID" AND E."TA"=F."TA"
                INNER JOIN "tmUrs" G ON C."UrsID"=G."UrsID" AND A."TA"=G."TA"
	            INNER JOIN "tmKUrs" H ON G."KUrsID"=H."KUrsID" AND G."TA"=H."TA"
            WHERE
                A."EntryLvl"=\'0\'
        ');				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_usulan_pra_renja_opd');
    }
}
