<?php

namespace App\Services;

use App\Models\Contract;

class ContractService
{
    public static function searchContracts($search, $dataCelebracaoContrato = null, $precoContratual = null, $adjudicatarios = null)
    {
        // search the following: dataCelebracaoContrato, precoContratual, adjudicatarios
        $query = Contract::whereDate('dataCelebracaoContrato', $search)
            ->orWhere('precoContratual', $search)
            ->orWhere('adjudicatarios', $search);
        if (null !== $dataCelebracaoContrato || '' !== $dataCelebracaoContrato) {
            $query->orWhereDate('dataCelebracaoContrato', $dataCelebracaoContrato);
        }
        if (null !== $precoContratual || '' !== $precoContratual) {
            $query->orWhere('precoContratual', $precoContratual);
        }
        if (null !== $adjudicatarios || '' !== $adjudicatarios) {
            $query->orWhere('adjudicatarios', $adjudicatarios);
        }

        return $query->get();
    }

    public static function fetchContract($contract_id)
    {
        $contract = Contract::find($contract_id);

        if(! is_null($contract) && is_null($contract->read_at))
        {
            $contract->read_at = now();
            $contract->save();
        }

        return $contract;
    }

    public static function fetchContractReadStatus($contract_id)
    {
        $contract = Contract::select('read_at')->find($contract_id);

        return $contract;
    }
}