<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVPlafon4View extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_plafon4 AS
            SELECT 
		        a."RenjaID",
                c3."Kd_Urusan" as "Kd_UrusanUnit", 
                c2."Kd_Bidang" as "Kd_BidangUnit", 
                c."OrgCd", 
                c."OrgNm", 
                d."SOrgCd", 
                d."SOrgNm", 
                f3."Kd_Urusan" as "Kd_UrusanPrg", 
                f2."Kd_Bidang" as "Kd_BidangPrg", 
                f."Kd_Prog", 
                f."PrgNm", 
                e."Kd_Keg", 
                e."KgtNm", 
                SUM(
                    CASE WHEN b."Jumlah1" IS NULL THEN
                        0
                    ELSE	
                        b."Jumlah1"
                    END                        
                )	
                AS "Jumlah1", 
                a."Descr", 
                a."TA",		 		
                a."Status"
                FROM 
                "trRenjaRinc" WHERE "trRenjaRinc"."RenjaID"=a."RenjaID" LIMIT 1) as Status,
	            a."EntryLvl" 	
            FROM "trRenja" a  
                JOIN "trRenjaRinc" b ON b."RenjaID" = a."RenjaID" AND b."TA" = a."TA"   
                JOIN "tmOrg" c ON c."OrgID" = a."OrgID" AND c."TA" = a."TA"   
                LEFT JOIN "tmUrs" c2 ON c2."UrsID" = c."UrsID"  
                LEFT JOIN "tmKUrs" c3 ON c3."KUrsID" = c2."KUrsID" 
                JOIN "tmSOrg" d ON d."SOrgID" = a."SOrgID" AND d."TA" = a."TA" 
                JOIN "tmKgt" e ON e."KgtID" = a."KgtID" AND e."TA" = a."TA" 
                JOIN "tmPrg" f ON f."PrgID" = e."PrgID" AND f."TA" = e."TA" 
                LEFT JOIN "trUrsPrg" f1 ON f1."PrgID" = f."PrgID"  
                LEFT JOIN "tmUrs" f2 ON f2."UrsID" = f1."UrsID"  
                LEFT JOIN "tmKUrs" f3 ON f3."KUrsID" = f2."KUrsID" 
                WHERE a."EntryLvl"=4		
                GROUP BY 
                    a."RenjaID", 
                    c3."Kd_Urusan", 
                    c2."Kd_Bidang", 
                    c."OrgCd", 
                    c."OrgNm", 
                    d."SOrgCd", 
                    d."SOrgNm", 
                    f3."Kd_Urusan", 
                    f2."Kd_Bidang", 
                    f."Kd_Prog", 
                    f."PrgNm", 
                    e."Kd_Keg", 
                    e."KgtNm",  
                    a."TA",
                    a."Descr",
                    a."EntryLvl"');			 
				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_plafon4');
    }
}
