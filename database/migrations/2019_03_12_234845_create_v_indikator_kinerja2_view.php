<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVIndikatorKinerja2View extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_indikator_kinerja2 AS
            SELECT 
                A."IndikatorKinerjaID",
                A."UrsID",
                A."PrgID",
                A."OrgIDRPJMD",
                CONCAT(D."Kd_Urusan",\'.\',C."Kd_Bidang",\'.\',"OrgCd") AS kode_organisasi,
                B."OrgNm",
                B."OrgAlias",
                H."Kd_Urusan",                
                CASE 
                    WHEN G."UrsID" IS NOT NULL OR  H."KUrsID" IS NOT NULL THEN
                        H."Nm_Urusan"
                    ELSE
                        \'SEMUA URUSAN\'
                END AS "Nm_Urusan",
                G."Kd_Bidang",
                CASE 
                    WHEN G."UrsID" IS NOT NULL OR  H."KUrsID" IS NOT NULL THEN
                        G."Nm_Bidang"
                    ELSE
                        \'SEMUA URUSAN\'
                END AS "Nm_Bidang",
                E."Kd_Prog",
                CASE 
                    WHEN G."UrsID" IS NOT NULL OR  H."KUrsID" IS NOT NULL THEN
                        CONCAT(H."Kd_Urusan", \'.\',G."Kd_Bidang", \'.\',E."Kd_Prog")
                    ELSE
                        CONCAT(\'n.nn.\',E."Kd_Prog")
                END AS kode_program,
                E."PrgNm", 
                E."Jns", 
                A."NamaIndikator",
                A."Satuan",
                A."KondisiAwal",
                A."TargetN1",
                A."TargetN2",
                A."TargetN3",
                A."TargetN4",
                A."TargetN5",
                A."PaguDanaN1",
                A."PaguDanaN2",
                A."PaguDanaN3",
                A."PaguDanaN4",
                A."PaguDanaN5",
                A."KondisiAkhirTarget",
                A."KondisiAkhirPaguDana",
                A."Descr", 
                A."TA", 
                E."Locked",
                A."created_at",
                A."updated_at"
            FROM "trIndikatorKinerja" A
            JOIN "tmOrgRPJMD" B ON A."OrgIDRPJMD"=B."OrgIDRPJMD"
            JOIN "tmUrs" C ON B."UrsID"=C."UrsID"
            JOIN "tmKUrs" D ON C."KUrsID"=D."KUrsID"
            JOIN "tmPrg" E ON A."PrgID"=E."PrgID"
            LEFT JOIN "trUrsPrg" F ON A."PrgID"=F."PrgID"
            LEFT JOIN "tmUrs" G ON F."UrsID"=G."UrsID"
            LEFT JOIN "tmKUrs" H ON G."KUrsID"=H."KUrsID"
        ');			 
				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_indikator_kinerja2');
    }
}
