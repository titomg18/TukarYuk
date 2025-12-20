<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('swaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Barang yang diminta
            $table->foreignId('requested_item_id')->constrained('items')->onDelete('cascade'); // Barang yang diminta (alias)
            $table->foreignId('offered_item_id')->constrained('items')->onDelete('cascade'); // Barang yang ditawarkan
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade'); // Pengaju swap
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); // Pemilik barang
            $table->enum('type', ['swap', 'free'])->default('swap');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('message')->nullable();
            $table->string('meeting_location')->nullable();
            $table->timestamp('meeting_time')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['requester_id', 'status']);
            $table->index(['owner_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('swaps');
    }
};