<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('source', [
                'website',
                'referral',
                'social_media',
                'cold_call',
                'email_campaign',
                'event',
                'other'
            ])->default('other');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pipeline_stage_id')->constrained()->onDelete('restrict');
            $table->text('needs_summary')->nullable();
            $table->date('first_contact_date');
            $table->timestamps();
            
            $table->index(['user_id', 'pipeline_stage_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
