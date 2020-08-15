<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\CashBoxRequest;
use App\CashBox;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CashBoxController
 * @package App\Api\V1\Controllers
 */
class CashBoxController extends Controller
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
     * @return JsonResponse
     */
    public function index()
    {
        $user = $this->user();

        return response()->json($user->cashBoxes()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CashBoxRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashBoxRequest $request)
    {
        $cashBox = new CashBox($request->all());

        if (!$cashBox->save()) {
            throw new HttpException(500);
        }
        $cashBox->users()->attach($this->user->id);

        return $this->response->created();
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(string $id)
    {
        $cashBox = CashBox::find($id);
        $this->authorize('show', $cashBox);

        return response()->json($cashBox);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @param CashBoxRequest $request
     * @return Response
     * @throws AuthorizationException
     */
    public function update(string $id, CashBoxRequest $request)
    {
        $cashBox = CashBox::find($id);
        $this->authorize('update', $cashBox);
        if (!$cashBox->update($request->all())) {
            throw new HttpException(500);
        }

        return $this->response->created();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function destroy(string $id)
    {
        $cashBox = CashBox::find($id);
        $this->authorize('destroy', $cashBox);
        if (!$cashBox->delete()) {
            throw new HttpException(500);
        }

        return $this->response->noContent();
    }
}
