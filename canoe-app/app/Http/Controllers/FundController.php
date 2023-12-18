<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fund;
use App\Models\FundManager;
use App\Repositories\FundRepository;
use App\Events\FundCreateEvent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class FundController extends Controller
{
    protected $fundsRepository;

    public function __construct(FundRepository $fundsRepository)
    {
        $this->fundsRepository = $fundsRepository;
    }

    public function index(Request $request)
    {
        $filters = collect($request->all());
        $funds = $this->fundsRepository->getAllFunds($filters);

        $funds->load('manager');
        return response()->json(['funds' => $funds]);
    }

    public function show($id)
    {
        $fund = Fund::findOrFail($id);
        $fund->load('manager');

        return response()->json(['fund' => $fund]);
    }

    public function showPossibleDuplicates($id)
    {
        $fund = Fund::findOrFail($id);
        $fund->load('manager');

        $duplicates = $this->fundsRepository->findPotentiallyDuplicateFunds($fund);
        return response()->json(['fund' => $fund, 'possible_duplicates' => $duplicates]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'start_year' => 'required|integer',
            'fund_manager_id' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $validatedData = $validator->getData();

        try {
            // Start a database transaction
            DB::beginTransaction();
            $fundManager = FundManager::firstOrCreate(['id' => $validatedData['fund_manager_id']]);

            // Create the fund within the transaction
            $fund = Fund::create([
                'name' => $validatedData['name'],
                'start_year' => $validatedData['start_year'],
                'manager_id' => $fundManager->id,
            ]);

            DB::commit();
            event(new FundCreateEvent($fund));

            $fund->load('manager');
            return response()->json(['message' => 'Fund created successfully', 'fund' => $fund], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while creating the fund'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'start_year' => 'integer',
            'fund_manager_id' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $fund = Fund::findOrFail($id);

        $validatedData = $validator->getData();

        $fieldsToUpdate = [
            'name' => $validatedData['name'] ?? $fund->name,
            'start_year' => $validatedData['start_year'] ?? $fund->start_year,
        ];


        if (isset($validatedData['fund_manager_id'])) {
            FundManager::findOrFail($validatedData['fund_manager_id']);
            $fieldsToUpdate['manager_id'] = $validatedData['fund_manager_id'];
        }

        $fund->update($fieldsToUpdate);
        $fund->load('manager');
        return response()->json(['message' => 'Fund updated successfully', 'fund' => $fund], 200);
    }

    public function destroy($id)
    {
        $fund = Fund::findOrFail($id);
        $fund->aliases()->delete();
        $fund->delete();

        return response()->json(['message' => 'Fund and associated aliases deleted successfully'], 204);
    }
}
