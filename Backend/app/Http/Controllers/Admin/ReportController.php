<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\AidRequestsExport;
use App\Exports\DonationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    /**
     * Export all donations as an Excel file.
     *
     * @return BinaryFileResponse
     */
    public function exportDonations(): BinaryFileResponse
    {
        return Excel::download(new DonationsExport, 'donations.xlsx');
    }

    /**
     * Export all aid requests as an Excel file.
     *
     * @return BinaryFileResponse
     */
    public function exportAidRequests(): BinaryFileResponse
    {
        return Excel::download(new AidRequestsExport, 'aid_requests.xlsx');
    }

    /**
     * Export reports dynamically by type (donations / aid-requests).
     *
     * @param string $type
     * @return BinaryFileResponse|JsonResponse
     */
    public function export(string $type)
    {
        switch ($type) {
            case 'donations':
                return Excel::download(new DonationsExport, 'donations.xlsx');

            case 'aid-requests':
                return Excel::download(new AidRequestsExport, 'aid_requests.xlsx');

            default:
                return response()->json(['message' => 'Invalid report type.'], 400);
        }
    }
}
