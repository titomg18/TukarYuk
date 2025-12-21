<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('swaps', function (Blueprint $table) {
            $table->string('offered_item_name')->nullable()->after('offered_item_id');
        });
    }

    public function down()
    {
        Schema::table('swaps', function (Blueprint $table) {
            $table->dropColumn('offered_item_name');
        });
    }
};
