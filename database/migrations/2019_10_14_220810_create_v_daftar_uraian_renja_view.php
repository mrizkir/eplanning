<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateVDaftarUraianRenjaView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_daftar_uraian_renja AS
            SELECT 
				A."RenjaID",
				A."OrgID",
				A."SOrgID",
				F."KgtID",
				K."RenjaRincID",
				K."UsulanKecID",
				N."Nm_Kecamatan",		
				CASE 
				WHEN I."UrsID" IS NOT NULL OR  J."KUrsID" IS NOT NULL THEN
								CONCAT(J."Kd_Urusan", \'.\',I."Kd_Bidang", \'.\',G."Kd_Prog", \'.\',F."Kd_Keg")
				ELSE
								CONCAT(\'0.00.\',G."Kd_Prog", \'.\',F."Kd_Keg")
				END AS kode_kegiatan,
				K."No",
				F."KgtNm",
				A."NamaIndikator",
				A."Sasaran_AngkaSetelah",
				A."Sasaran_UraianSetelah",
				A."NilaiSebelum",
				A."NilaiSetelah",
				K."Uraian",
				CASE 
						WHEN K."EntryLvl"=0 THEN K."Sasaran_Angka1"
						WHEN K."EntryLvl"=1 THEN K."Sasaran_Angka2"
						WHEN K."EntryLvl"=2 THEN K."Sasaran_Angka3"
						WHEN K."EntryLvl"=3 THEN K."Sasaran_Angka4"
						WHEN K."EntryLvl"=4 THEN K."Sasaran_Angka5"
				END AS "Sasaran_Angka",
				CASE 
						WHEN K."EntryLvl"=0 THEN K."Sasaran_Uraian1"
						WHEN K."EntryLvl"=1 THEN K."Sasaran_Uraian2"
						WHEN K."EntryLvl"=2 THEN K."Sasaran_Uraian3"
						WHEN K."EntryLvl"=3 THEN K."Sasaran_Uraian4"
						WHEN K."EntryLvl"=4 THEN K."Sasaran_Uraian5"
				END AS "Sasaran_Uraian",
				CASE 
						WHEN K."EntryLvl"=0 THEN K."Target1"
						WHEN K."EntryLvl"=1 THEN K."Target1"
						WHEN K."EntryLvl"=2 THEN K."Target1"
						WHEN K."EntryLvl"=3 THEN K."Target1"
						WHEN K."EntryLvl"=4 THEN K."Target1"
				END AS "Target",
				CASE 
						WHEN K."EntryLvl"=0 THEN K."Jumlah1"
						WHEN K."EntryLvl"=1 THEN K."Jumlah2"
						WHEN K."EntryLvl"=2 THEN K."Jumlah3"
						WHEN K."EntryLvl"=3 THEN K."Jumlah4"
						WHEN K."EntryLvl"=4 THEN K."Jumlah5"
				END AS "Jumlah",		
				K."Prioritas",
				K."Status",
				A."Status_Indikator",
				P."Nm_SumberDana",
				K."EntryLvl",
				CASE 
					WHEN K."EntryLvl"=0 THEN \'PRA-RENJA\'
					WHEN K."EntryLvl"=1 THEN \'RAKOR BIDANG\'
					WHEN K."EntryLvl"=2 THEN \'FORUM OPD\'
					WHEN K."EntryLvl"=3 THEN \'MUSRENBANG KABUPATEN\'
					WHEN K."EntryLvl"=4 THEN \'VERIFIKASI TAPD\'
				END AS nama_level,
				K."Privilege",
				A."Locked",
				K."isReses",
				K."isReses_Uraian",
				K."isSKPD",				
				A."TA",
				K."Descr",
				K.created_at,
				K.updated_at
			FROM "trRenja" A
			JOIN "tmOrg" B ON A."OrgID"=B."OrgID"
			JOIN "tmSOrg" C ON A."SOrgID"=C."SOrgID"
			JOIN "tmUrs" D ON B."UrsID"=D."UrsID"
			JOIN "tmKUrs" E ON D."KUrsID"=E."KUrsID"

			JOIN "tmKgt" F ON A."KgtID"=F."KgtID"
			JOIN "tmPrg" G ON F."PrgID"=G."PrgID"
			JOIN "trRenjaRinc" K ON A."RenjaID"=K."RenjaID"

			LEFT JOIN "trUrsPrg" H ON G."PrgID"=H."PrgID"
			LEFT JOIN "tmUrs" I ON H."UrsID"=I."UrsID"
			LEFT JOIN "tmKUrs" J ON J."KUrsID"=I."KUrsID"

			LEFT JOIN "tmPMProv" L ON K."PMProvID"=L."PMProvID"
			LEFT JOIN "tmPmKota" M ON K."PmKotaID"=M."PmKotaID"
			LEFT JOIN "tmPmKecamatan" N ON K."PmKecamatanID"=N."PmKecamatanID"
			LEFT JOIN "tmPmDesa" O ON K."PmDesaID"=O."PmDesaID"
			LEFT JOIN "tmSumberDana" P ON A."SumberDanaID"=P."SumberDanaID"

			ORDER BY
			K."EntryLvl" ASC,
			J."Kd_Urusan"::int ASC NULLS FIRST,
			I."Kd_Bidang"::int ASC NULLS FIRST,
			G."Kd_Prog"::int ASC,
			F."Kd_Keg"::int ASC,
			K."Uraian" ASC			
        ');			 				
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_daftar_uraian_renja');
    }
}