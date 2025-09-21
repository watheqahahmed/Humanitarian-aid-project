<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\AidRequestsExport;
use App\Exports\DonationsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Export all donations as Excel file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportDonations()
    {
        return Excel::download(new DonationsExport, 'donations.xlsx');
    }

    /**
     * Export all aid requests as Excel file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportAidRequests()
    {
        return Excel::download(new AidRequestsExport, 'aid_requests.xlsx');
    }

    /**
     * Export reports dynamically by type (donations / aid-requests).
     *
     * @param string $type
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function export($type)
    {
        if ($type === 'donations') {
            return Excel::download(new DonationsExport, 'donations.xlsx');
        }

        if ($type === 'aid-requests') {
            return Excel::download(new AidRequestsExport, 'aid_requests.xlsx');
        }

        return response()->json(['message' => 'Invalid report type.'], 400);
    }
}
