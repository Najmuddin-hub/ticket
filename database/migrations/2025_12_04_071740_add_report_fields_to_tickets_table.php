<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'report_type_id')) {
                $table->unsignedBigInteger('report_type_id')->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('tickets', 'report_id')) {
                $table->unsignedBigInteger('report_id')->nullable()->after('report_type_id');
            }
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['report_type_id', 'report_id']);
        });
    }

};
