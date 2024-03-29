<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function (Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');

        $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
        $api->post('refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');
        $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');
    });

    $api->group(['prefix' => 'cash-boxes'], function (Router $api) {
        $controller = 'App\\Api\\V1\\Controllers\\CashBoxController';

        $api->get('', $controller . '@index');
        $api->get('{id}', $controller . '@show');
        $api->post('', $controller . '@store');
        $api->put('{id}', $controller . '@update');
        $api->delete('{id}', $controller . '@destroy');

    });
    $api->group(['prefix' => 'cash-boxes/{cashBoxId}/plans'], function (Router $api) {
        $controller = 'App\\Api\\V1\\Controllers\\CashBoxBudgetPlanController';

        $api->get('', $controller . '@index');
        $api->get('active', $controller . '@activePlan');
        $api->get('{cashBoxBudgetPlanId}', $controller . '@show');
        $api->post('', $controller . '@store');
        $api->put('{cashBoxBudgetPlanId}', $controller . '@update');
        $api->delete('{cashBoxBudgetPlanId}', $controller . '@destroy');
    });

    $api->group(['prefix' => 'cash-boxes/{cashBoxId}/plans/{cashBoxBudgetPlanId}/entries'], function (Router $api) {
        $controller = 'App\\Api\\V1\\Controllers\\CashBoxBudgetPlanEntryController';

        $api->post('', $controller . '@store');
        $api->put('{entryId}', $controller . '@update');
        $api->delete('{entryId}', $controller . '@destroy');
    });

    $api->group(['middleware' => 'jwt.auth'], function (Router $api) {
        $api->get('protected', function () {
            return response()->json([
                'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function () {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });
});
