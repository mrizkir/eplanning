<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVProgramKegiatanView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_program_kegiatan AS
            SELECT 
            kegiatan."KgtID",
            program."PrgID",
            urusan."UrsID",
            kelompok_urusan."KUrsID",            
            kelompok_urusan."Kd_Urusan",
            urusan."Kd_Bidang",			              
            program."Kd_Prog",
            kegiatan."Kd_Keg",
		    CASE 
                WHEN urusan."UrsID" IS NOT NULL OR  kelompok_urusan."KUrsID" IS NOT NULL THEN
                    CONCAT(kelompok_urusan."Kd_Urusan",\'.\',urusan."Kd_Bidang",\'.\',program."Kd_Prog",\'.\',kegiatan."Kd_Keg")
            END AS kode_kegiatan,
            kelompok_urusan."Nm_Urusan",
            urusan."Nm_Bidang",			            
            program."PrgNm",
            kegiatan."KgtNm",
            program."Jns",
            kegiatan."TA",
            kegiatan."Locked" 			 
                FROM "tmKgt" AS kegiatan
                LEFT JOIN "tmPrg" AS program ON program."PrgID"=kegiatan."PrgID"
                LEFT JOIN "trUrsPrg" AS urs_program ON program."PrgID"=urs_program."PrgID"
                LEFT JOIN "tmUrs" AS urusan ON urs_program."UrsID"=urusan."UrsID"
                LEFT JOIN "tmKUrs" AS kelompok_urusan ON kelompok_urusan."KUrsID"=urusan."KUrsID"');			 
				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_program_kegiatan');
    }
}
