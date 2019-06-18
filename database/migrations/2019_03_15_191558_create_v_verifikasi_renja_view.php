<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVVerifikasiRenjaView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_verifikasi_renja AS
            SELECT 
                A."RenjaID",                
                B."RenjaRincID",
                B."UsulanKecID",
                B."PMProvID",
                I."Nm_Prov",
                B."PmKotaID",
                J."Nm_Kota",
                B."PmKecamatanID",
                K."Nm_Kecamatan",
                B."PmDesaID",
                L."Nm_Desa",
                B."PokPirID",
                H."Kd_Urusan",
                H."Nm_Urusan",
                G."Kd_Bidang",
                G."Nm_Bidang",
                A."OrgID",
	            A."SOrgID",
                C."OrgNm",
                D."SOrgNm",
                CONCAT(H."Kd_Urusan", \'.\', G."Kd_Bidang", \'.\', F."Kd_Prog", \'.\', E."Kd_Keg") AS kode_kegiatan,
                F."PrgNm",
                E."KgtNm",
                B."No",
                B."Uraian",
                B."Sasaran_Angka1",
                B."Sasaran_Angka2",
                B."Sasaran_Angka3",
                B."Sasaran_Angka4",
                B."Sasaran_Angka5",
                B."Sasaran_Uraian1",
                B."Sasaran_Uraian2",
                B."Sasaran_Uraian3",
                B."Sasaran_Uraian4",
                B."Sasaran_Uraian5",
                B."Target1",
                B."Target2",
                B."Target3",
                B."Target4",
                B."Target5",
                B."Jumlah1",
                B."Jumlah2",
                B."Jumlah3",
                B."Jumlah4",
                B."Jumlah5",
                B."Prioritas",
                B."Status",                
                A."Status_Indikator",
                B."Privilege",
                A."Locked",
                B."isReses",
                B."isReses_Uraian",
                B."isSKPD",
                B."Descr",
                A."TA"
            FROM "trRenja" A
                LEFT JOIN "trRenjaRinc" B ON A."RenjaID"=B."RenjaID" AND A."TA"=B."TA"
                INNER JOIN "tmOrg" C ON A."OrgID"=C."OrgID" AND A."TA"=C."TA"
                INNER JOIN "tmSOrg" D ON A."SOrgID"=D."SOrgID" AND A."TA"=D."TA"
                INNER JOIN "tmKgt" E ON A."KgtID"=E."KgtID" AND A."TA"=E."TA"
                INNER JOIN "tmPrg" F ON E."PrgID"=F."PrgID" AND E."TA"=F."TA"
                INNER JOIN "tmUrs" G ON C."UrsID"=G."UrsID" AND A."TA"=G."TA"
	            INNER JOIN "tmKUrs" H ON G."KUrsID"=H."KUrsID" AND G."TA"=H."TA"
                LEFT JOIN "tmPMProv" I ON B."PMProvID"=I."PMProvID" AND B."TA"=I."TA"
                LEFT JOIN "tmPmKota" J ON B."PmKotaID"=J."PmKotaID" AND B."TA"=J."TA"                
                LEFT JOIN "tmPmKecamatan" K ON B."PmKecamatanID"=K."PmKecamatanID" AND B."TA"=K."TA"
                LEFT JOIN "tmPmDesa" L ON B."PmDesaID"=L."PmDesaID" AND B."TA"=L."TA"
            WHERE
                A."EntryLvl"=\'4\'
        ');				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_verifikasi_renja');
    }
}
