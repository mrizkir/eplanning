<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVSuborganisasiView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_suborganisasi AS
                        SELECT sub."SOrgID", 
                            organisasi."OrgID",
                            organisasi."OrgIDRPJMD",
                            urusan."UrsID",
                            kelompok_urusan."KUrsID",
                            kelompok_urusan."Kd_Urusan",
                            urusan."Kd_Bidang",			 
                            organisasi."OrgCd",                           
                            CONCAT(kelompok_urusan."Kd_Urusan",\'.\',urusan."Kd_Bidang",\'.\',organisasi."OrgCd") kode_organisasi,                            
                            sub."SOrgCd",              
                            CONCAT(kelompok_urusan."Kd_Urusan",\'.\',urusan."Kd_Bidang",\'.\',organisasi."OrgCd",\'.\',sub."SOrgCd") kode_suborganisasi,
                            kelompok_urusan."Nm_Urusan",
                            urusan."Nm_Bidang",
                            organisasi."OrgNm",
                            organisasi."OrgAlias",
                            sub."SOrgNm",
                            sub."SOrgAlias",
                            sub."Alamat",
                            sub."NamaKepalaSKPD",
                            sub."NIPKepalaSKPD",
                            sub."TA",                            
                            sub."created_at",                            
                            sub."updated_at"                            
                            FROM "tmSOrg" AS sub
                            JOIN "tmOrg" AS organisasi ON organisasi."OrgID"=sub."OrgID"
                            JOIN "tmUrs" AS urusan ON organisasi."UrsID"=urusan."UrsID"
                            JOIN "tmKUrs" AS kelompok_urusan ON kelompok_urusan."KUrsID"=urusan."KUrsID"
                            ORDER BY
                                kelompok_urusan."Kd_Urusan"::int ASC,
                                urusan."Kd_Bidang"::int ASC,
                                organisasi."OrgCd"::int ASC,                                
                                sub."SOrgCd"::int ASC                                
                        ');
                            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_suborganisasi');
    }
}
