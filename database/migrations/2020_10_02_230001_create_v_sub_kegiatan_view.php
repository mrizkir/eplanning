<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateVSubKegiatanView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_sub_kegiatan AS
            SELECT 
                M."SubKgtID",
                A."KgtID",
                A."PrgID",
                D."UrsID",
                E."KUrsID",
                E."Kd_Urusan",
                CASE 
                        WHEN D."UrsID" IS NOT NULL OR  E."KUrsID" IS NOT NULL THEN
                                E."Nm_Urusan"
                        ELSE
                                \'SEMUA URUSAN\'
                END AS "Nm_Urusan",
                D."Kd_Bidang",
                CASE 
                        WHEN D."UrsID" IS NOT NULL OR  E."KUrsID" IS NOT NULL THEN
                                D."Nm_Bidang"
                        ELSE
                                \'SEMUA URUSAN\'
                END AS "Nm_Bidang",
                B."Kd_Prog",
                CASE 
                        WHEN D."UrsID" IS NOT NULL OR  E."KUrsID" IS NOT NULL THEN
                                CONCAT(E."Kd_Urusan", \'.\',D."Kd_Bidang", \'.\',B."Kd_Prog")
                        ELSE
                                CONCAT(\'0.00.\',B."Kd_Prog")
                END AS kode_program,
                B."PrgNm", 
                B."Jns", 
                A."Kd_Keg",
                CASE 
                        WHEN D."UrsID" IS NOT NULL OR  E."KUrsID" IS NOT NULL THEN
                                CONCAT(E."Kd_Urusan", \'.\',D."Kd_Bidang", \'.\',B."Kd_Prog", \'.\',A."Kd_Keg")
                        ELSE
                                CONCAT(\'0.00.\',B."Kd_Prog", \'.\',A."Kd_Keg")
                END AS kode_kegiatan,
                A."KgtNm",
                M."Kd_SubKeg",
                CASE 
                        WHEN D."UrsID" IS NOT NULL OR  E."KUrsID" IS NOT NULL THEN
                                CONCAT(E."Kd_Urusan", \'.\',D."Kd_Bidang", \'.\',B."Kd_Prog", \'.\',A."Kd_Keg", \'.\',M."Kd_SubKeg")
                        ELSE
                                CONCAT(\'0.00.\',B."Kd_Prog", \'.\',A."Kd_Keg", \'.\',M."Kd_SubKeg")
                END AS kode_subkegiatan,
                M."SubKgtNm",
                M."Descr",
                M."TA",
                M.created_at,
                M.updated_at	
            FROM "tmSubKgt" M
            JOIN "tmKgt" A ON M."KgtID"=A."KgtID"
            JOIN "tmPrg" B ON A."PrgID"=B."PrgID"
            LEFT JOIN "trUrsPrg" C ON A."PrgID"=C."PrgID"
            LEFT JOIN "tmUrs" D ON C."UrsID"=D."UrsID"
            LEFT JOIN "tmKUrs" E ON D."KUrsID"=E."KUrsID"	
            ORDER BY
                E."Kd_Urusan"::int ASC NULLS FIRST,
                D."Kd_Bidang"::int ASC NULLS FIRST,
                B."Kd_Prog"::int ASC, 
                A."Kd_Keg"::int ASC, 
                M."Kd_SubKeg"::int ASC
        ');			 				
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_sub_kegiatan');
    }
}