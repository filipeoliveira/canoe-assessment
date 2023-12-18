<?php 

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Models\Fund as Fund;

class FundQueryBuilder
{
    public static function build(Collection $filters): Builder
    {
        $query = Fund::query();

        if ($name = $filters->get('name')) {
            $query->where('name', 'ILIKE', '%' . $name . '%');
        }

        if ($manager = $filters->get('manager_id')) {
            $query->where('manager_id', $manager);
        }

        if ($managerName = $filters->get('manager_name')) {
            $query->join('fund_managers', 'funds.manager_id', '=', 'fund_managers.id')
                  ->where('fund_managers.name', 'ILIKE', '%' . $managerName . '%');
        }

        if ($year = $filters->get('start_year')) {
            $query->where('start_year', $year);
        }


        return $query;
    }
}
