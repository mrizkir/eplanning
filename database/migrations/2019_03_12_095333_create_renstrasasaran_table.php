<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenstrasasaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmRenstraSasaran', function (Blueprint $table) {
            $table->string('RenstraSasaranID',19);
            $table->string('RenstraTujuanID',19);
            $table->string('OrgIDRPJMD',19);
            $table->string('Kd_RenstraSasaran',4);
            $table->string('Nm_RenstraSasaran');           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);
            $table->string('RenstraSasaranID_Src',19)->nullable();

            $table->timestamps();

            $table->primary('RenstraSasaranID');

            $table->index('RenstraTujuanID');
            $table->index('OrgIDRPJMD');
            $table->index('RenstraSasaranID_Src');

            $table->foreign('RenstraTujuanID')
                    ->references('RenstraTujuanID')
                    ->on('tmRenstraTujuan')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('OrgIDRPJMD')
                    ->references('OrgIDRPJMD')
                    ->on('tmOrgRPJMD')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmRenstraSasaran');
    }
}
