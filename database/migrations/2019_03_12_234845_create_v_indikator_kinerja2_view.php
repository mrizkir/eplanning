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
        // \DB::statement('CREATE VIEW v_indikator_kinerja2 AS
        //     SELECT 
        //         E."Kd_Urusan",
        //         E."Nm_Urusan",
        //         D."Kd_Bidang",
        //         CONCAT(E."Kd_Urusan",\'.\',D."Kd_Bidang") AS kode_urusan,
        //         D."Nm_Bidang",		
        //         C."Kd_Prog",
        //         CONCAT(E."Kd_Urusan",\'.\',D."Kd_Bidang",\'.\',C."Kd_Prog") AS kode_program,
        //         C."PrgNm",
        //         H."Kd_PrioritasKab",
        //         H."Nm_PrioritasKab",
        //         G."Kd_Tujuan",
        //         CONCAT(H."Kd_PrioritasKab",\'.\',G."Kd_Tujuan") AS kode_tujuan,
        //         G."Nm_Tujuan",
        //         F."Kd_Sasaran",
        //         CONCAT(H."Kd_PrioritasKab",\'.\',G."Kd_Tujuan",\'.\',F."Kd_Sasaran") AS kode_sasaran,
        //         F."Nm_Sasaran",
        //         B."NamaIndikator",		
        //         B."Satuan",		
        //         B."KondisiAwal",
        //         B."N1" AS "TargetN1",
        //         A."PaguDanaN1",
        //         B."N2" AS "TargetN2",
        //         A."PaguDanaN2",
        //         B."N3" AS "TargetN3",
        //         A."PaguDanaN3",
        //         B."N4" AS "TargetN4",
        //         A."PaguDanaN4",
        //         B."N5" AS "TargetN5",
        //         A."PaguDanaN5",
        //         B."KondisiAkhir" AS "KondisiAkhirTarget",
        //         A."KondisiAkhirPaguDana",
        //         A."Descr",
        //         A."TA",
        //         C."Jns",
        //         H."PrioritasKabID",
        //         G."PrioritasTujuanKabID",
        //         A."PrioritasSasaranKabID",
        //         A."IndikatorKinerjaID",                
        //         A."UrsID",
        //         A."PrgID",
        //         A.created_at,
        //         A.updated_at
        //     FROM "trIndikatorKinerja" A 
        //         JOIN "tmPrioritasIndikatorSasaran" B ON B."PrioritasSasaranKabID" = A."PrioritasSasaranKabID"
        //         JOIN "tmPrg" C ON C."PrgID" = A."PrgID"
        //         JOIN "tmUrs" D ON D."UrsID" = A."UrsID"
        //         JOIN "tmKUrs" E ON E."KUrsID" = D."KUrsID"
        //         JOIN "tmPrioritasSasaranKab" F ON F."PrioritasSasaranKabID"=A."PrioritasSasaranKabID"
        //         JOIN "tmPrioritasTujuanKab" G ON G."PrioritasTujuanKabID"=F."PrioritasTujuanKabID"
        //         JOIN "tmPrioritasKab" H ON H."PrioritasKabID"=G."PrioritasKabID"
        // ');			 
				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // \DB::statement('DROP VIEW v_indikator_kinerja2');
    }
}
