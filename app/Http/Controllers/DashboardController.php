<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\TicketStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function __invoke()
    {

    
        Gate::authorize('viewAny', Ticket::class);

        //read month from query: ?month=2025-12
        $selectedMonth = request()->query('month'); //"YYY-MM" or null

        //Monthly range (selected month or current month)
        $start = $selectedMonth
        ? Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth()
        : Carbon::now()->startOfMonth();

        $end = (clone $start)->endOfMonth();

        // ✅ Dropdown options (last 12 months incl current)
        $monthOptions = collect(range(0, 11))->map(function ($i) {
            $d = Carbon::now()->subMonths($i);
            return [
                'value' => $d->format('Y-m'),
                'label' => $d->translatedFormat('F Y'),
            ];
        });

         // Tickets by Category (this month)
        $categoryCounts = Category::select('categories.name')
            ->leftJoin('tickets', function ($join) use ($start, $end) {
                $join->on('tickets.category_id', '=', 'categories.id')
                     ->whereBetween('tickets.created_at', [$start, $end]);
            })
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('COUNT(tickets.id) as count')
            ->get();

        // Status colors
        $statuses = TicketStatus::select('id', 'name', 'color')
            ->get()
            ->keyBy('name');

        // Tickets by Status (this month)
        $statusCounts = TicketStatus::select('name')
            ->leftJoin('tickets', function ($join) use ($start, $end) {
                $join->on('tickets.ticket_status_id', '=', 'ticket_statuses.id')
                     ->whereBetween('tickets.created_at', [$start, $end]);
            })
            ->groupBy('ticket_statuses.id', 'ticket_statuses.name')
            ->selectRaw('COUNT(tickets.id) as count')
            ->pluck('count', 'name');

        $statusColors = $statuses->mapWithKeys(function ($status) {
            return [$status->name => $status->color];
        });

        // $statusColors = $statuses->mapWithKeys(function($status) {
        //     return [$status->name => $status->color];
        // });

        // Tickets by Department (this month)
        $departmentCounts = \App\Models\Department::select('departments.name')
            ->leftJoin('users', 'users.department_id', '=', 'departments.id')
            ->leftJoin('tickets', function ($join) use ($start, $end) {
                $join->on('tickets.user_id', '=', 'users.id')
                     ->whereBetween('tickets.created_at', [$start, $end]);
            })
            ->groupBy('departments.id', 'departments.name')
            ->selectRaw('COUNT(tickets.id) as count')
            ->get();

        // My Tasks / My Tickets (this month)
        $user = Auth::user();
        $assignedToMeCount = 0;
        $createdByMeCount = 0;

        if ($user) {
            $assignedToMeCount = $user->assignedTickets()
                ->whereBetween('tickets.created_at', [$start, $end])
                ->count();

            $createdByMeCount = $user->tickets()
                ->whereBetween('tickets.created_at', [$start, $end])
                ->count();
        }

        return view('dashboard', compact(
            'categoryCounts',
            'statusCounts',
            'statusColors',
            'departmentCounts',
            'assignedToMeCount',
            'createdByMeCount',
            'monthOptions',
            'selectedMonth',
            'start',
            'end'
        ));
    }
}
