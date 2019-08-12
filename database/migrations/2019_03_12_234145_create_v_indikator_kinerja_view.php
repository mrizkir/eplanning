<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVIndikatorKinerjaView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_indikator_kinerja AS
            SELECT 
                A."IndikatorKinerjaID",
                A."PrioritasSasaranKabID",
                B."Kd_Sasaran",
                B."Nm_Sasaran",
                D."KUrsID",
                D."Kd_Urusan",
                D."Nm_Urusan",
                C."UrsID",
                C."Kd_Bidang",
                C."Nm_Bidang",
                A."PrgID",
                E."Kd_Prog",
                E."PrgNm",
                A."OrgIDRPJMD",                          
                A."NamaIndikator",
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
                A."Satuan",
                A."Operator",
                A."Descr",
                A."TA",
                A."Locked",
                A."created_at",
                A."updated_at"
            FROM "trIndikatorKinerja" A
                JOIN "tmPrioritasSasaranKab" B ON B."PrioritasSasaranKabID"=A."PrioritasSasaranKabID"
                JOIN "tmUrs" C ON C."UrsID"=A."UrsID"
                JOIN "tmKUrs" D ON D."KUrsID"=C."KUrsID"
                JOIN "tmPrg" E ON E."PrgID"=A."PrgID"
        ');			 
				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_indikator_kinerja');
    }
}
