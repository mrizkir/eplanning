<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVUsulanRakorBidangView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_usulan_rakor_bidang AS
            SELECT 
                A."RenjaID",
                B1."RenjaIndikatorID",
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
                B."Sasaran_Angka2",
                B."Sasaran_Uraian2",
                B."Target2",
                B."Jumlah2",
                B."Prioritas",
                CASE WHEN B."Status"=0 OR B."Status" IS NULL THEN
                    \'DUM\'
                ELSE
                    \'ACC\'
                END AS Status,
                B."Privilege",
                B."isReses",
                B."isReses_Uraian",
                B."isSKPD",
                A."TA"
            FROM "trRenja" A
                LEFT JOIN "trRenjaRinc" B ON A."RenjaID"=B."RenjaID" AND A."TA"=B."TA"
                LEFT JOIN "trRenjaIndikator" B1 ON A."RenjaID"=B1."RenjaID" AND A."TA"=B1."TA"
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
                A."EntryLvl"=\'1\'
        ');				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_usulan_rakor_bidang');
    }
}
