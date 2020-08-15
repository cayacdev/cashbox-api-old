<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\CashBoxBudgetPlanEntryRequest;
use App\CashBox;
use App\CashBoxBudgetPlan;
use App\CashBoxBudgetPlanEntry;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CashBoxBudgetPlanEntryController extends Controller
{
    use Helpers;

    /**
     * Store a newly created resource in storage.
     *
     * @param string $cashBoxId
     * @param string $planId
     * @param CashBoxBudgetPlanEntryRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(string $cashBoxId, string $planId, CashBoxBudgetPlanEntryRequest $request)
    {
        $cashBox = CashBox::find($cashBoxId);
        // TODO: policy by cash box
        $plan = CashBoxBudgetPlan::find($planId);

        $entry = new CashBoxBudgetPlanEntry($request->all());
        $entry->budgetPlan()->associate($plan);
        $entry->user()->associate($this->user);

        if (!$entry->save()) {
            throw new HttpException(500);
        }

        return $this->response->created();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $cashBoxId
     * @param string $planId
     * @param string $entryId
     * @param CashBoxBudgetPlanEntryRequest $request
     * @return Response
     */
    public function update(string $cashBoxId, string $planId, string $entryId, CashBoxBudgetPlanEntryRequest $request)
    {
        $entry = CashBoxBudgetPlanEntry::find($entryId);
        // TODO: policy owner

        if (!$entry->update($request->all())) {
            throw new HttpException(500);
        }

        return $this->response->created();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $cashBoxId
     * @param string $planId
     * @param string $entryId
     * @return Response
     * @throws Exception
     */
    public function destroy(string $cashBoxId, string $planId, string $entryId)
    {
        $entry = CashBoxBudgetPlanEntry::find($entryId);
        // TODO: policy owner

        if (!$entry->delete()) {
            throw new HttpException(500);
        }

        return $this->response->noContent();
    }
}
