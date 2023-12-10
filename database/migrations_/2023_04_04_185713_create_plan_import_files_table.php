<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_import_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_type_id');
            $table->string('filename', 100);
            $table->string('path');
            $table->string('status');
            $table->date('date_upload')->useCurrent();
            $table->date('date_last_import')->nullable();
            $table->timestamps();

            $table->foreign('import_type_id')->references('id')->on('plan_import_types')
                ->onUpdate('cascade')->onDelete('cascade');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_import_files');
    }
};
