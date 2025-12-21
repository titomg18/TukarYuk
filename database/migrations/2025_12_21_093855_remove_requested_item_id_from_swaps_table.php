<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('swaps', function (Blueprint $table) {
            $table->dropForeign(['requested_item_id']);
            $table->dropColumn('requested_item_id');
        });
    }

    public function down()
    {
        Schema::table('swaps', function (Blueprint $table) {
            $table->foreignId('requested_item_id')->constrained('items')->onDelete('cascade');
        });
    }
};