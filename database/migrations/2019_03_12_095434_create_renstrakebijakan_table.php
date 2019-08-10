<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenstrakebijakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmRenstraKebijakan', function (Blueprint $table) {
            $table->string('RenstraKebijakanID',19);
            $table->string('RenstraStrategiID',19);
            $table->string('OrgIDRPJMD',19);
            $table->string('Kd_RenstraKebijakan',4);
            $table->string('Nm_RenstraKebijakan');           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);
            $table->string('RenstraKebijakanID_Src',19)->nullable();
            $table->timestamps();

            $table->primary('RenstraKebijakanID');

            $table->index('RenstraStrategiID');
            $table->index('OrgIDRPJMD');
            $table->index('RenstraKebijakanID_Src');

            $table->foreign('RenstraStrategiID')
                    ->references('RenstraStrategiID')
                    ->on('tmRenstraStrategi')
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
        Schema::dropIfExists('tmRenstraKebijakan');
    }
}
