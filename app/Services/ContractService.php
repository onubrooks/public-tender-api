<?php

namespace App\Services;

use App\Models\Contract;
use Illuminate\Support\Carbon;

class ContractService
{
    public static function searchContracts($dataCelebracaoContrato = null, $precoContratual_lower = null, $precoContratual_upper, $adjudicatarios = null)
    {
        // search the following: dataCelebracaoContrato, precoContratual, adjudicatarios
        $query = Contract::whereNull('id');
        if (null !== $dataCelebracaoContrato || '' !== $dataCelebracaoContrato) {
            $query->orWhereDate('dataCelebracaoContrato', $dataCelebracaoContrato);
        }
        if (null !== $adjudicatarios || '' !== $adjudicatarios) {
            $query->orWhere('adjudicatarios', $adjudicatarios);
        }
        if (null !== $precoContratual_lower && null !== $precoContratual_upper) {
            // both upper and lower are present so use whereBetween
            $query->orWhereBetween('precoContratual', [$precoContratual_lower, $precoContratual_upper]);
        } else {
            // either or both are absent so use where for those present
            if (null !== $precoContratual_lower || '' !== $precoContratual_lower) {
                $query->orWhere('precoContratual', $precoContratual_lower);
            }
            if (null !== $precoContratual_upper || '' !== $precoContratual_upper) {
                $query->orWhere('adjudicatarios', $precoContratual_upper);
            }
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