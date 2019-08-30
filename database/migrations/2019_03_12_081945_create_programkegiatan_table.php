<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateProgramkegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmKgt', function (Blueprint $table) {
            $table->string('KgtID',19);
            $table->string('PrgID',19);
            $table->string('Kd_Keg',4);
            $table->string('KgtNm');
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('KgtID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);
            $table->timestamps();
            $table->primary('KgtID');
            $table->index('PrgID');
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
        Schema::dropIfExists('tmKgt');
    }
}