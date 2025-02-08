<?php

use Illuminate\Support\Facades\Route;
use Modules\SupportTicket\App\Http\Controllers\SupportTicketController;
use Modules\SupportTicket\App\Http\Controllers\TicketIssueTypeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'role:root|admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/support-tickets', 'index')->name('supportTicket.index');
        Route::get('/support-tickets/{supportTicket}', 'show')->name('supportTicket.show');
        Route::post('/support-tickets/{supportTicket}/scheduled', 'setScheduled')->name('supportTicket.setScheduled');
        Route::post('/support-tickets/{supportTicket}/send-message', 'sendMessage')->name('supportTicket.sendMessage');
        Route::get('/support-tickets/{supportTicket}/fetch-messages', 'fetchMessages')->name('supportTicket.fetchMessages');
        Route::get('/support-tickets/{supportTicket}/update-status', 'updateStatus')->name('supportTicket.updateStatus');
        Route::get('/support-tickets/{supportTicketMessage}/pin', 'pinMessage')->name('supportTicket.pinMessage');
        Route::get('/support-tickets/{supportTicket}/chat-toggle', 'chatToggle')->name('supportTicket.chatToggle');
    });

    Route::controller(TicketIssueTypeController::class)->group(function () {
        Route::get('ticket-issue-types', 'index')->name('ticketissuetype.index');
        Route::post('ticket-issue-type/store', 'store')->name('ticketissuetype.store');
        Route::put('ticket-issue-type/{ticketIssueType}/update', 'update')->name('ticketissuetype.update');
        Route::get('ticket-issue-type/{ticketIssueType}/status-toggle', 'toggleStatus')->name('ticketissuetype.toggle');
        Route::get('ticket-issue-type/{ticketIssueType}/delete', 'destroy')->name('ticketissuetype.delete');
    });
});
