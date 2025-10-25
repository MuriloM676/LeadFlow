<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity')->default(1);
            $table->decimal('estimated_value', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('lead_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
