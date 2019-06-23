<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVRkpdView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_rkpd AS
            SELECT 
                A."RKPDID",
                H."Kd_Urusan",
                H."Nm_Urusan",
                G."Kd_Bidang",
                G."Nm_Bidang",
                A."OrgID",
                A."SOrgID",
                C."OrgCd",
                C."OrgNm",
                D."SOrgCd",
                D."SOrgNm",
                E."PrgID",
                CONCAT(H."Kd_Urusan", \'.\', G."Kd_Bidang", \'.\', C."OrgCd", \'.\', F."Kd_Prog") AS kode_program,                                
                F."Kd_Prog",
                F."PrgNm",
                E."Kd_Keg",
                CONCAT(H."Kd_Urusan", \'.\', G."Kd_Bidang", \'.\', C."OrgCd", \'.\', F."Kd_Prog", \'.\', E."Kd_Keg") AS kode_kegiatan,
                E."KgtNm",
                I."Nm_SumberDana",
                A."NamaIndikator",
                A."Sasaran_Angka1",
                A."Sasaran_Angka2",
                A."Sasaran_Uraian1",
                A."Sasaran_Uraian2",
                A."Target1",
                A."Target2",
                A."NilaiUsulan1",
                A."NilaiUsulan2",
                A."Sasaran_AngkaSetelah",
                A."Sasaran_UraianSetelah",
                A."NilaiSebelum",
                A."NilaiSetelah",
                A."Tgl_Posting",               
                A."Descr",
                A."TA",
                A."Status",
                A."Status_Indikator",
                A."EntryLvl",
                A."Privilege",
                A."RKPDID_Src"
            FROM "trRKPD" A                
                INNER JOIN "tmOrg" C ON A."OrgID"=C."OrgID" AND A."TA"=C."TA"
                INNER JOIN "tmSOrg" D ON A."SOrgID"=D."SOrgID" AND A."TA"=D."TA"
                INNER JOIN "tmKgt" E ON A."KgtID"=E."KgtID" AND A."TA"=E."TA"
                INNER JOIN "tmPrg" F ON E."PrgID"=F."PrgID" AND E."TA"=F."TA"
                INNER JOIN "tmUrs" G ON C."UrsID"=G."UrsID" AND A."TA"=G."TA"
                INNER JOIN "tmKUrs" H ON G."KUrsID"=H."KUrsID" AND G."TA"=H."TA"
                INNER JOIN "tmSumberDana" I ON I."SumberDanaID"=A."SumberDanaID" AND A."TA"=I."TA"                
        ');				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_rkpd');
    }
}
