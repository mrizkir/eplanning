<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubkegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmSubKgt', function (Blueprint $table) {
            $table->string('SubKgtID',19);
            $table->string('KgtID',19);
            $table->string('Kd_SubKeg',4);
            $table->text('SubKgtNm');
            $table->text('Descr')->nullable();
            $table->year('TA');
            $table->string('SubKgtID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);
            $table->timestamps();
            $table->primary('SubKgtID');
            $table->index('KgtID');
            $table->foreign('KgtID')
                ->references('KgtID')
                ->on('tmKgt')
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
        Schema::dropIfExists('tmSubKgt');
    }
}