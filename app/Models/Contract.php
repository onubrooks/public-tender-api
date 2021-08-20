<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idcontrato',
        'nAnuncio',
        'tipoContrato',
        'tipoprocedimento',
        'objectoContrato',
        'adjudicantes',
        'adjudicatarios',
        'dataPublicacao',
        'dataCelebracaoContrato',
        'precoContratual',
        'cpv',
        'prazoExecucao',
        'localExecucao',
        'fundamentacao',
        'upload_id',
        'batch_id',
        'read_at',
    ];

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array
    //  */
    // protected $casts = [
    //     'dataPublicacao' => 'date',
    //     'dataCelebracaoContrato' => 'date',
    // ];
}
