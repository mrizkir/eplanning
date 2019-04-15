<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaguanggaranopdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPaguAnggaranOPD', function (Blueprint $table) {
            $table->string('PaguAnggaranOPDID',19);
            $table->string('OrgID',19);
            $table->decimal('Jumlah1',15,2)->nullable();
            $table->decimal('Jumlah2',15,2)->nullable();

            $table->text('Descr')->nullable();
            $table->year('TA'); 

            $table->timestamps();

            $table->primary('PaguAnggaranOPDID');
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
        Schema::dropIfExists('paguanggaranopd');
    }
}