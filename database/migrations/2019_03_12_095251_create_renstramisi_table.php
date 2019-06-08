<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenstramisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmRenstraMisi', function (Blueprint $table) {
            $table->string('RenstraMisiID',19);
            $table->string('RenstraVisiID',19);
            $table->string('OrgID',19);
            $table->string('Kd_RenstraMisi',4);
            $table->string('Nm_RenstraMisi');           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);

            $table->timestamps();

            $table->primary('RenstraMisiID');

            $table->index('RenstraVisiID');
            $table->index('OrgID');

            $table->foreign('RenstraVisiID')
                    ->references('RenstraVisiID')
                    ->on('tmRenstraVisi')
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
        Schema::dropIfExists('tmRenstraMisi');
    }
}
