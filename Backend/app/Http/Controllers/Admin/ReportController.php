<?php

namespace App\Http\Controllers\Admin;
use App\Exports\AidRequestsExport;


use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DonationsExport;

class ReportController extends Controller
{
    public function exportDonations()
    {
        // إنشاء تقرير Excel
        return Excel::download(new DonationsExport, 'donations.xlsx');
    }

    public function exportAidRequests()
{
    return Excel::download(new AidRequestsExport, 'aid_requests.xlsx');
}
}
