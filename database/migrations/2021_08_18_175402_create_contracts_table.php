<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {												
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idcontrato'); // 2265302
            $table->string('nAnuncio'); // 2574/2010
            $table->string('tipoContrato'); // Empreitadas de obras públicas
            $table->string('tipoprocedimento'); // Concurso público
            $table->string('objectoContrato'); // 09/2016/EMP/DGR _ EN256 Variante à ponte do Albardão, incluindo nova ponte sobre o rio Degébe
            $table->string('adjudicantes'); // 504598686 - EP - ESTRADAS DE PORTUGAL, S.A
            $table->string('adjudicatarios'); // 502496878 - CONSTRUÇÕES PRAGOSA, S.A.
            $table->date('dataPublicacao'); // 29/06/2016
            $table->date('dataCelebracaoContrato'); // 31/05/2016
            $table->decimal('precoContratual'); // 2605654.08
            $table->string('cpv'); // 45233142-6 - Reparação de estradas
            $table->integer('prazoExecucao'); // 300
            $table->string('localExecucao'); // Portugal, Portugal Continental
            $table->string('fundamentacao'); // Artigo 19.º, alínea b) do Código dos Contratos Públicos
            $table->integer('upload_id')->nullable();
            $table->integer('batch_id')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
