<?php 
// app/Repositories/FundRepository.php

namespace App\Repositories;

use App\QueryBuilders\FundQueryBuilder;
use App\Models\Fund;

class FundRepository
{
    public function getAllFunds($request)
    {
        return FundQueryBuilder::build($request)->get();
    }

    public function findPotentiallyDuplicateFunds(Fund $fund)
    {
        return Fund::where('manager_id', $fund->manager_id)
            ->where('id', '!=', $fund->id)
            ->where(function ($query) use ($fund) {
                $query->where('name', 'ILIKE', '%' . $fund->name . '%')
                    ->orWhereHas('aliases', function ($subQuery) use ($fund) {
                        $subQuery->where('name', 'ILIKE', '%' . $fund->name . '%');
                    });
            })
            ->get();
    }
}
