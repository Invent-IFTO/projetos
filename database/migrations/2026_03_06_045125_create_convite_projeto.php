<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->string('label')->nullable();
            $table->string('chave');
            $table->string('valor');
            $table->timestamps();
        });
        Schema::create('projetos_convites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('equipe_id')->constrained('projetos_equipes');
            $table->foreignId('responsavel_convite_id')->constrained('users');
            $table->string('email');
            $table->string('token')->unique();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
        Schema::dropIfExists('projetos_convites');
    }
};
