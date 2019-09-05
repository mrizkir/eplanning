<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVUrusanProgramView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_urusan_program AS 
                        SELECT program."PrgID",
                            urusan."UrsID",
                            kelompok_urusan."KUrsID",
                            kelompok_urusan."Kd_Urusan",
                            urusan."Kd_Bidang",			 
                            program."Kd_Prog",
                            CASE 
                                WHEN urusan."UrsID" IS NOT NULL OR  kelompok_urusan."KUrsID" IS NOT NULL THEN
                                    CONCAT(kelompok_urusan."Kd_Urusan",\'.\',urusan."Kd_Bidang",\'.\',program."Kd_Prog")
                                ELSE
                                    CONCAT(\'0.\',\'00.\',program."Kd_Prog")
                            END AS Kode_Program,
                            kelompok_urusan."Nm_Urusan",
                            urusan."Nm_Bidang",
                            program."PrgNm",
                            program."Jns",
                            program."TA",
                            program."Locked",
                            program."created_at",
                            program."updated_at"
                        
                        FROM "tmPrg" AS program
                            LEFT JOIN "trUrsPrg" AS urs_program ON program."PrgID"=urs_program."PrgID"
                            LEFT JOIN "tmUrs" AS urusan ON urs_program."UrsID"=urusan."UrsID"
                            LEFT JOIN "tmKUrs" AS kelompok_urusan ON kelompok_urusan."KUrsID"=urusan."KUrsID"
                        
                        ORDER BY
                            kelompok_urusan."Kd_Urusan"::int ASC NULLS FIRST,
                            urusan."Kd_Bidang"::int ASC NULLS FIRST,
                            program."Kd_Prog"::int ASC 
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_urusan_program');
    }
}
