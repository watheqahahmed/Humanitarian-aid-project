<?php

namespace App\Exports;

use App\Models\Donation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DonationsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Donation::select('donor_name', 'type', 'quantity', 'status', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'Donor Name',
            'Donation Type',
            'Quantity',
            'Status',
            'Date',
        ];
    }
}
