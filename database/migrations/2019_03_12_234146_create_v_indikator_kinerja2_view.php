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
                A."PrioritasKebijakanKabID",
                B."Kd_Kebijakan",
                B."Nm_Kebijakan",
                D."KUrsID",
                D."Kd_Urusan",
                D."Nm_Urusan",
                C."UrsID",
                C."Kd_Bidang",
                C."Nm_Bidang",
                A."PrgID",
                E."Kd_Prog",
                E."PrgNm",
                A."OrgID",
                F."OrgCd",
                F."OrgNm",              
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
                JOIN "tmPrioritasKebijakanKab" B ON B."PrioritasKebijakanKabID"=A."PrioritasKebijakanKabID"
                JOIN "tmUrs" C ON C."UrsID"=A."UrsID"
                JOIN "tmKUrs" D ON D."KUrsID"=C."KUrsID"
                JOIN "tmPrg" E ON E."PrgID"=A."PrgID"
                JOIN "tmOrg" F ON F."OrgID"=A."OrgID"
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
