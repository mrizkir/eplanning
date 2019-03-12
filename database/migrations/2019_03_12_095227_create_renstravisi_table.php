<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenstravisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmRenstraVisi', function (Blueprint $table) {
            $table->string('RenstraVisiID',19);
            $table->string('OrgID',19);
            $table->string('Kd_RenstraVisi',4);
            $table->string('Nm_RenstraVisi');           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);

            $table->timestamps();

            $table->primary('RenstraVisiID');

            $table->index('OrgID');

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
        Schema::dropIfExists('tmRenstraVisi');
    }
}
