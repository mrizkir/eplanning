<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMappingprogramtoopdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trOrgProgram', function (Blueprint $table) {
            $table->string('orgProgramID',19);
            $table->string('OrgID',19);
            $table->string('PrgID',19);
            $table->string('Descr')->nullable();
            $table->year('TA');         
            $table->timestamps();

            $table->primary('orgProgramID');
            $table->index('OrgID');
            $table->index('PrgID');

            $table->foreign('OrgID')
                ->references('OrgID')
                ->on('tmOrg')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('PrgID')
                ->references('PrgID')
                ->on('tmPrg')
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
        Schema::dropIfExists('trOrgProgram');
    }
}
