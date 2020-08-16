<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\CashBoxBudgetPlanRequest;
use App\CashBox;
use App\CashBoxBudgetPlan;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CashBoxBudgetPlanController
 * @package App\Api\V1\Controllers
 */
class CashBoxBudgetPlanController extends Controller
{
    use Helpers;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $cashBoxId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(string $cashBoxId)
    {
        $cashBox = CashBox::find($cashBoxId);
        $this->authorize('show', $cashBox);

        return response()->json($cashBox->budgetPlans()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string $cashBoxId
     * @param CashBoxBudgetPlanRequest $request
     * @return Response
     * @throws AuthorizationException
     */
    public function store(string $cashBoxId, CashBoxBudgetPlanRequest $request)
    {
        $cashBox = CashBox::find($cashBoxId);
        $this->authorize('show', $cashBox);

        $plan = new CashBoxBudgetPlan($request->all());

        $plan->cashBox()->associate($cashBox);

        $conflictedPlans = $plan->getConflictedPlans();
        if ($conflictedPlans->count() > 0) {
            throw new HttpException(400, 'The plan overlaps with other plans');
        }

        if (!$plan->save()) {
            throw new HttpException(500);
        }

        return $this->response->created();
    }

    /**
     * Display the specified resource.
     *
     * @param string $cashBoxId
     * @param string $cashBoxBudgetPlanId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(string $cashBoxId, string $cashBoxBudgetPlanId)
    {
        $plan = $this->getPlanThroughCashBox($cashBoxId, 'show', $cashBoxBudgetPlanId);
        return response()->json($plan->load('entries.user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $cashBoxId
     * @param string $cashBoxBudgetPlanId
     * @param CashBoxBudgetPlanRequest $request
     * @return \Dingo\Api\Http\Response
     * @throws AuthorizationException
     */
    public function update(string $cashBoxId, string $cashBoxBudgetPlanId, CashBoxBudgetPlanRequest $request)
    {
        $plan = $this->getPlanThroughCashBox($cashBoxId, 'update', $cashBoxBudgetPlanId);

        $conflictedPlans = $plan->getConflictedPlans($cashBoxBudgetPlanId);
        if ($conflictedPlans->count() > 0) {
            throw new HttpException(400, 'The plan overlaps with other plans');
        }

        if (!$plan->update($request->all())) {
            throw new HttpException(500);
        }

        return $this->response->created();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $cashBoxId
     * @param string $cashBoxBudgetPlanId
     * @return \Dingo\Api\Http\Response
     * @throws AuthorizationException
     */
    public function destroy(string $cashBoxId, string $cashBoxBudgetPlanId)
    {
        $plan = $this->getPlanThroughCashBox($cashBoxId, 'destroy', $cashBoxBudgetPlanId);
        if (!$plan->delete()) {
            throw new HttpException(500);
        }

        return $this->response->noContent();
    }

    /**
     * Get the active plan for the given cash box
     *
     * @param string $cashBoxId
     * @return \Dingo\Api\Http\Response|JsonResponse
     * @throws AuthorizationException
     */
    public function activePlan(string $cashBoxId)
    {
        $cashBox = CashBox::find($cashBoxId);
        $this->authorize('show', $cashBox);

        $activePlan = $cashBox->getActivePlan()->first();

        if ($activePlan) {
            return response()->json($activePlan->load('entries.user'));
        } else {
            return $this->response->noContent();
        }
    }

    /**
     * @param string $cashBoxId
     * @param string $action
     * @param string $cashBoxBudgetPlanId
     * @return mixed
     * @throws AuthorizationException
     */
    public function getPlanThroughCashBox(string $cashBoxId, string $action, string $cashBoxBudgetPlanId)
    {
        $cashBox = CashBox::find($cashBoxId);
        $this->authorize($action, $cashBox);
        $plan = $cashBox->budgetPlans()->where('id', '=', $cashBoxBudgetPlanId)->first();
        $this->authorize($action, $plan);
        return $plan;
    }
}
