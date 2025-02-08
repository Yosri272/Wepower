<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportTicket\App\Http\Controllers\API\MessageController;
use Modules\SupportTicket\App\Http\Controllers\API\SupportTicketController;
use Modules\SupportTicket\App\Http\Controllers\API\TicketIssueTypeController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {

    // support ticket
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/support-tickets', 'index');
        Route::post('/support-ticket', 'store');
        Route::get('/support-ticket/show', 'show');
    });

    // support ticket message
    Route::controller(MessageController::class)->group(function () {
        Route::get('/support-ticket-messages', 'index');
        Route::post('/support-ticket-message', 'store');
    });

    // ticket issue types
    Route::controller(TicketIssueTypeController::class)->group(function () {
        Route::get('ticket-issue-types', 'index');
    });
});
