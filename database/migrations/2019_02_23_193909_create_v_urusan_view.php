<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVUrusanView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement(
            'CREATE VIEW v_urusan AS SELECT 
                ku."KUrsID", 
                tu."UrsID",
                ku."Kd_Urusan", 
                tu."Kd_Bidang", 
                pg_catalog.concat(ku."Kd_Urusan", \'.\', tu."Kd_Bidang") AS "Kode_Bidang", 
                ku."Nm_Urusan", 
                tu."Nm_Bidang", 
                ku."TA" FROM "tmUrs" tu INNER JOIN "tmKUrs" ku 
                ON (ku."KUrsID"=tu."KUrsID")'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('v_urusan');
    }
}
