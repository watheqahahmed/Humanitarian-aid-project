<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\AidRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard statistics for the admin.
     */
    public function index()
    {
        $totalDonations = Donation::count();
        $totalAidRequests = AidRequest::count();
        $pendingRequests = AidRequest::where('status', 'pending')->count();
        $approvedRequests = AidRequest::where('status', 'approved')->count();
        $totalVolunteers = \App\Models\User::where('role', 'volunteer')->count();

        return response()->json([
            'total_donations' => $totalDonations,
            'total_aid_requests' => $totalAidRequests,
            'pending_requests' => $pendingRequests,
            'approved_requests' => $approvedRequests,
            'total_volunteers' => $totalVolunteers,
        ]);
    }
}
