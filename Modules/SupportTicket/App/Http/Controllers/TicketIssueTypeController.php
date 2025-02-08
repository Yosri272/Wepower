<?php

namespace Modules\SupportTicket\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SupportTicket\App\Models\TicketIssueType;

class TicketIssueTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketIssueTypes = TicketIssueType::latest('id')->paginate(10);

        return view('supportticket::ticket-issue.index', compact('ticketIssueTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        TicketIssueType::create([
            'name' => $request->name,
        ]);

        return back()->withSuccess('Ticket issue type created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketIssueType $ticketIssueType)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $ticketIssueType->update(['name' => $request->name]);

        return back()->withSuccess('Ticket issue type updated successfully');
    }

    /**
     * toggle the specified resource in storage.
     */
    public function toggleStatus(TicketIssueType $ticketIssueType)
    {
        $ticketIssueType->update(['is_active' => ! $ticketIssueType->is_active]);

        return back()->withSuccess('Status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketIssueType $ticketIssueType)
    {
        $ticketIssueType->delete();

        return back()->withSuccess('Issue type deleted successfully');
    }
}
