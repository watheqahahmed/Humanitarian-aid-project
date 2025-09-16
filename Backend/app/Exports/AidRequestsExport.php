<?php

namespace App\Exports;

use App\Models\AidRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AidRequestsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AidRequest::with('beneficiary')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Beneficiary Name',
            'Type',
            'Description',
            'Status',
            'Created At',
        ];
    }

    public function map($aidRequest): array
    {
        return [
            $aidRequest->id,
            $aidRequest->beneficiary->name,
            $aidRequest->type,
            $aidRequest->description,
            $aidRequest->status,
            $aidRequest->created_at,
        ];
    }
}
