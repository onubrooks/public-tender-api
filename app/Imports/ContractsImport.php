<?php

namespace App\Imports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;

class ContractsImport implements ToModel, WithHeadingRow, WithUpserts, WithChunkReading, WithBatchInserts
{
    use RemembersChunkOffset;

    public function __construct($upload = null)
    {
        $this->upload = $upload;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $chunkOffset = $this->getChunkOffset();
        return new Contract([
            'batch_id'  => $chunkOffset,
            'upload_id'  => $this->upload->id ?? 0,
            'idcontrato'  => $row['idcontrato'],
            'nAnuncio'  => $row['nAnuncio'],
            'tipoContrato'  => $row['tipoContrato'],
            'tipoprocedimento'  => $row['tipoprocedimento'],
            'objectoContrato'  => $row['objectoContrato'],
            'adjudicantes'  => $row['adjudicantes'],
            'adjudicatarios'  => $row['adjudicatarios'],
            'dataPublicacao'  => $row['dataPublicacao'],
            'dataCelebracaoContrato'  => $row['dataCelebracaoContrato'],
            'precoContratual'  => $row['precoContratual'],
            'cpv'  => $row['cpv'],
            'prazoExecucao'  => $row['prazoExecucao'],
            'localExecucao'  => $row['localExecucao'],
            'fundamentacao'  => $row['fundamentacao'],
        ]);
    }

    // import in batches to drastically reduce the import duration.
    public function batchSize(): int
    {
        return 100;
    }

    // read file in chunks to mitigate increase in memory usage
    public function chunkSize(): int
    {
        return 500;
    }

    public function uniqueBy()
    {
        return 'idcontrato';
    }
}
