<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenstraindikatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('trRenstraIndikator', function (Blueprint $table) {
            $table->string('RenstraIndikatorID',19);
            $table->string('RenstraKebijakanID',19);            
            $table->string('IndikatorKinerjaID',19); 
            $table->string('UrsID',19);
            $table->string('PrgID',19);
            $table->string('OrgID',19);

            $table->string('NamaIndikator'); 

            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->boolean('Locked')->default(0);

            $table->timestamps();

            $table->primary('RenstraIndikatorID');

            $table->index('RenstraKebijakanID');
            $table->index('IndikatorKinerjaID');
            $table->index('UrsID');
            $table->index('PrgID');
            $table->index('OrgID');

            $table->foreign('RenstraKebijakanID')
                    ->references('RenstraKebijakanID')
                    ->on('tmRenstraKebijakan')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');


            $table->foreign('IndikatorKinerjaID')
                    ->references('IndikatorKinerjaID')
                    ->on('trIndikatorKinerja')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            
            $table->foreign('UrsID')
                    ->references('UrsID')
                    ->on('tmUrs')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('PrgID')
                    ->references('PrgID')
                    ->on('tmPrg')
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
        Schema::dropIfExists('trRenstraIndikator');
    }
}
