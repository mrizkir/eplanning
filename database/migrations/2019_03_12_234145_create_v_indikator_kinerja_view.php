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
                ku."Kd_Urusan",
                b."Kd_Bidang",               
                c."Kd_Prog",		
                CASE WHEN c3."Kd_Urusan" IS NULL THEN 
                    \'0\'
                ELSE
                    c3."Kd_Urusan"
                END AS "Kd_UrusanPrg", 

                CASE WHEN c2."Kd_Bidang" IS NULL THEN 
                    \'0\'
                ELSE
                    c2."Kd_Bidang"
                END AS "Kd_BidangPrg", 

                CASE WHEN d3."Kd_Urusan" IS NULL THEN 
                    \'0\'
                ELSE
                    d3."Kd_Urusan"
                END AS "Kd_UrusanUnit", 

                CASE WHEN d2."Kd_Bidang" IS NULL THEN 
                    \'0\'
                ELSE
                    d2."Kd_Bidang"
                END AS "Kd_BidangUnit", 

                d."OrgCd",
                CASE WHEN e."SOrgCd"=\'\' THEN 
                    \'0\'
                ELSE
                    e."SOrgCd"
                END AS "SOrgCd", 
                a.*

            FROM "trIndikatorKinerja" a 
                JOIN "tmUrs" b ON b."UrsID" = a."UrsID"
                JOIN "tmKUrs" ku ON ku."KUrsID" = b."KUrsID"
                    JOIN "tmPrg" c ON c."PrgID" = a."PrgID"    
                LEFT JOIN "trUrsPrg" c1 ON c1."PrgID" = c."PrgID" 
                LEFT JOIN "tmUrs" c2 ON c2."UrsID" = c1."UrsID"
                LEFT JOIN "tmKUrs" c3 ON c3."KUrsID" = c2."KUrsID"
                JOIN "tmOrg" d ON d."OrgID" = a."OrgID"
                LEFT JOIN "tmUrs" d2 ON d2."UrsID" = d."UrsID" 
                LEFT JOIN "tmKUrs" d3 ON d3."KUrsID" = d2."KUrsID" 
                LEFT JOIN "tmSOrg" e ON e."SOrgID" = a."OrgID2"
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
