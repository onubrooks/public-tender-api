<?php

namespace App\Imports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\WithValidation;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class ContractsImport implements ToModel, WithHeadingRow, WithUpserts, WithChunkReading, WithBatchInserts, WithValidation
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
            'nAnuncio'  => $row['nanuncio'],
            'tipoContrato'  => $row['tipocontrato'],
            'tipoprocedimento'  => $row['tipoprocedimento'],
            'objectoContrato'  => $row['objectocontrato'],
            'adjudicantes'  => $row['adjudicantes'],
            'adjudicatarios'  => $row['adjudicatarios'],
            'dataPublicacao'  => $row['datapublicacao'],
            'dataCelebracaoContrato'  => $row['datacelebracaocontrato'],
            'precoContratual'  => $row['precocontratual'],
            'cpv'  => $row['cpv'],
            'prazoExecucao'  => $row['prazoexecucao'],
            'localExecucao'  => $row['localexecucao'],
            'fundamentacao'  => $row['fundamentacao'],
        ]);
    }

    // import in batches to drastically reduce the import duration.
    public function batchSize(): int
    {
        return 1000;
    }

    // read file in chunks to mitigate increase in memory usage
    public function chunkSize(): int
    {
        return 4000;
    }

    public function uniqueBy()
    {
        return 'idcontrato';
    }

    public function prepareForValidation($data, $index)
    {
        $data['datapublicacao'] = Date::excelToDateTimeObject($data['datapublicacao'])->format('Y-m-d');
        $data['datacelebracaocontrato'] = Date::excelToDateTimeObject($data['datacelebracaocontrato'])->format('Y-m-d');

        return $data;
    }

    /**
     * List the validation rules
     * @return array
     */
    public function rules(): array
    {
        return [
            'idcontrato'  => 'numeric',
            'nAnuncio'  => 'string',
            'tipoContrato'  => 'string',
            'tipoprocedimento'  => 'string',
            'objectoContrato'  => 'string',
            'adjudicantes'  => 'string',
            'adjudicatarios'  => 'string',
            'precoContratual'  => 'numeric',
            'cpv'  => 'string',
            'prazoExecucao'  => 'numeric',
            'localExecucao'  => 'string',
            'fundamentacao'  => 'string',
            'dataPublicacao' => 'string',
            'dataCelebracaoContrato' => 'string',
        ];
    }
}
