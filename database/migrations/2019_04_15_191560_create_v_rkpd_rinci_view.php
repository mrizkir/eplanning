<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVRkpdRinciView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_rkpd_rinci AS
            SELECT 
                A."RKPDID",		
                K."RKPDRincID",
                A."OrgID",
                A."SOrgID",
                CONCAT(E."Kd_Urusan",  \'.\', D."Kd_Bidang",\'.\', B."OrgCd") AS kode_organisasi,
                B."OrgNm",
                CONCAT(E."Kd_Urusan",\'.\',D."Kd_Bidang",\'.\',B."OrgCd",\'.\',C."SOrgCd") kode_suborganisasi,
                C."SOrgNm",
                K."UsulanKecID",
                K."PMProvID",
                L."Nm_Prov",
                K."PmKotaID",
                M."Nm_Kota",
                K."PmKecamatanID",	
                N."Nm_Kecamatan",
                K."PmDesaID",
                O."Nm_Desa",
                K."Lokasi",
                K."Latitude",
                K."Longitude",
                K."PokPirID",
                J."KUrsID",
                J."Kd_Urusan",
                J."Nm_Urusan",
                I."UrsID",
                I."Kd_Bidang",
                CASE 
                    WHEN I."UrsID" IS NOT NULL OR  J."KUrsID" IS NOT NULL THEN
                        CONCAT(J."Kd_Urusan",\'.\',I."Kd_Bidang")
                    ELSE
                        \'SEMUA URUSAN\'
                END AS kode_urusan,
                I."Nm_Bidang",
                G."PrgID",
                G."Kd_Prog",
                CASE 
                                WHEN I."UrsID" IS NOT NULL OR  J."KUrsID" IS NOT NULL THEN
                                    CONCAT(J."Kd_Urusan",\'.\',I."Kd_Bidang",\'.\',G."Kd_Prog")
                                ELSE
                                    CONCAT(\'0.\',\'00.\',G."Kd_Prog")
                END AS kode_program,
                G."PrgNm",
                G."Jns",
                A."KgtID",
                F."Kd_Keg",
                CASE 
                        WHEN I."UrsID" IS NOT NULL OR  J."KUrsID" IS NOT NULL THEN
                                CONCAT(J."Kd_Urusan", \'.\',I."Kd_Bidang", \'.\',G."Kd_Prog", \'.\',F."Kd_Keg")
                        ELSE
                                CONCAT(\'0.00.\',G."Kd_Prog", \'.\',F."Kd_Keg")
                END AS kode_kegiatan,
                F."KgtNm",
                P."Nm_SumberDana",
                K."No",
                K."Uraian",
                K."Sasaran_Angka1",
                K."Sasaran_Angka2",
                K."Sasaran_Angka3",
                K."Sasaran_Angka4",
                K."Sasaran_Uraian1",
                K."Sasaran_Uraian2",
                K."Sasaran_Uraian3",
                K."Sasaran_Uraian4",
                K."Target1",
                K."Target2",
                K."Target3",
                K."Target4",
                K."NilaiUsulan1",		
                K."NilaiUsulan2",		
                K."NilaiUsulan3",		
                K."NilaiUsulan4",		
                K."Status",
                A."Status_Indikator",
                A."EntryLvl",
                K."Privilege",
                A."Locked",
                K."isReses",
                K."isReses_Uraian",
                K."isSKPD",
                K."Descr",
                A."TA",
                K.created_at,
                K.updated_at
            FROM "trRKPD" A
            JOIN "tmOrg" B ON A."OrgID"=B."OrgID"
            JOIN "tmSOrg" C ON A."SOrgID"=C."SOrgID"
            JOIN "tmUrs" D ON B."UrsID"=D."UrsID"
            JOIN "tmKUrs" E ON D."KUrsID"=E."KUrsID"
            JOIN "tmKgt" F ON A."KgtID"=F."KgtID"
            JOIN "tmPrg" G ON F."PrgID"=G."PrgID"
            JOIN "tmSumberDana" P ON P."SumberDanaID"=A."SumberDanaID"

            LEFT JOIN "trUrsPrg" H ON G."PrgID"=H."PrgID"
            LEFT JOIN "tmUrs" I ON H."UrsID"=I."UrsID"
            LEFT JOIN "tmKUrs" J ON J."KUrsID"=I."KUrsID"

            LEFT JOIN "trRKPDRinc" K ON A."RKPDID"=K."RKPDID"
            LEFT JOIN "tmPMProv" L ON K."PMProvID"=L."PMProvID"
            LEFT JOIN "tmPmKota" M ON K."PmKotaID"=M."PmKotaID"
            LEFT JOIN "tmPmKecamatan" N ON K."PmKecamatanID"=N."PmKecamatanID"
            LEFT JOIN "tmPmDesa" O ON K."PmDesaID"=O."PmDesaID"

            ORDER BY
                J."Kd_Urusan"::int ASC NULLS FIRST,
                I."Kd_Bidang"::int ASC NULLS FIRST,
                G."Kd_Prog"::int ASC,
                F."Kd_Keg"::int ASC
        ');				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_rkpd_rinci');
    }
}
