<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_quantity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('old_quantity');
            $table->integer('new_quantity');
            $table->integer('change_amount');
            $table->enum('action', ['created', 'updated', 'order_placed', 'import', 'manual']);
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_quantity_logs');
    }
};
