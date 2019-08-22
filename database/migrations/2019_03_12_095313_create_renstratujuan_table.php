<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenstratujuanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmRenstraTujuan', function (Blueprint $table) {
            $table->string('RenstraTujuanID',19);
            $table->string('PrioritasSasaranKabID',19);
            $table->string('OrgIDRPJMD',19);
            $table->string('Kd_RenstraTujuan',4);
            $table->text('Nm_RenstraTujuan');           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);
            $table->string('RenstraTujuanID_Src',19)->nullable();

            $table->timestamps();

            $table->primary('RenstraTujuanID');

            $table->index('PrioritasSasaranKabID');
            $table->index('OrgIDRPJMD');
            $table->index('RenstraTujuanID_Src');
            
            $table->foreign('PrioritasSasaranKabID')
                    ->references('PrioritasSasaranKabID')
                    ->on('tmPrioritasSasaranKab')
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
        Schema::dropIfExists('tmRenstraTujuan');
    }
}
