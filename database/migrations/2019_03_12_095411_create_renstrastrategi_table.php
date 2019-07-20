<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenstrastrategiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmRenstraStrategi', function (Blueprint $table) {
            $table->string('RenstraStrategiID',19);
            $table->string('RenstraSasaranID',19);
            $table->string('OrgID',19);
            $table->string('Kd_RenstraStrategi',4);
            $table->string('Nm_RenstraStrategi');           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);
            $table->string('RenstraStrategiID_Src',19)->nullable();

            $table->timestamps();

            $table->primary('RenstraStrategiID');

            $table->index('RenstraSasaranID');
            $table->index('OrgID');
            $table->index('RenstraStrategiID_Src');

            $table->foreign('RenstraSasaranID')
                    ->references('RenstraSasaranID')
                    ->on('tmRenstraSasaran')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                    
            $table->foreign('OrgID')
                    ->references('OrgID')
                    ->on('tmOrg')
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
        Schema::dropIfExists('tmRenstraStrategi');
    }
}
