<?php

use DeepCopy\TypeFilter\ShallowCopyFilter;
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
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->foreignId('criador_id')->constrained('users');   
            $table->timestamps();
        });
        Schema::create('projetos_equipes', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->foreignId('projeto_id')->constrained('projetos')->cascadeOnDelete(); 
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('projetos_membros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->cascadeOnDelete();   
            $table->foreignId('equipe_id')->nullable()->constrained('projetos_equipes')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('lider_equipe')->default(false);
            $table->boolean('gerente_projeto')->default(false);   
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('projetos_atividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_membro_id')->constrained('projetos_membros');    
            $table->text('anterior');
            $table->text('atual');
            $table->text('problemas');
            $table->enum('status', ['pendente', 'verificada'])->default('pendente');
            $table->float('avaliacao',2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projetos_atividades');
        Schema::dropIfExists('projetos_membros');
        Schema::dropIfExists('projetos_equipes');
        Schema::dropIfExists('projetos');
    }
};
