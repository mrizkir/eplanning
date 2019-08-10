<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVUrusanOrganisasiMasterView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_urusan_organisasi_master AS
                            SELECT organisasi."OrgIDRPJMD",
                            urusan."UrsID",
                            kelompok_urusan."KUrsID",
                            kelompok_urusan."Kd_Urusan",
                            urusan."Kd_Bidang",			 
                            organisasi."OrgCd",
                            CASE 
                                    WHEN urusan."UrsID" IS NOT NULL OR  kelompok_urusan."KUrsID" IS NOT NULL THEN
                                            CONCAT(kelompok_urusan."Kd_Urusan",\'.\',urusan."Kd_Bidang",\'.\',organisasi."OrgCd")
                            END AS Kode_Organisasi,
                            kelompok_urusan."Nm_Urusan",
                            urusan."Nm_Bidang",
                            organisasi."OrgNm",
                            organisasi."TA",                            
                            organisasi."created_at",                            
                            organisasi."updated_at"                            
                            FROM "tmOrgRPJMD" AS organisasi
                                    JOIN "tmUrs" AS urusan ON organisasi."UrsID"=urusan."UrsID"
                                    JOIN "tmKUrs" AS kelompok_urusan ON kelompok_urusan."KUrsID"=urusan."KUrsID"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_urusan_organisasi');
    }
}
