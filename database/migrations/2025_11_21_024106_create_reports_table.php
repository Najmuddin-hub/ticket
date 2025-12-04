<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_type_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('report_type_id')
                    ->references('id')
                    ->on('report_types')
                    ->onDelete('cascade');
    });
}

    /*
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->insignedBigInteger('report_type_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('report_type_id')
                ->reference('id')
                ->on('report_types')
                ->onDelete('cascade');
        });
    }
    */
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
