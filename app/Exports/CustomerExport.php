<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerExport implements FromCollection, WithHeadings
{
    
    private $start_date;
    private $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    
    public function collection()
    {
        $customers = DB::table('user_detail as customer')
            ->whereBetween(DB::raw('DATE(customer.created_date)'), [$this->start_date, $this->end_date])
            ->select(
                'customer.user_id',
                'customer.username',
                'customer.name',
                'customer.email',
                'customer.mobile',
                'customer.address',
                'customer.gender',
                'customer.aponjon_number',
                'customer.status',
                'customer.created_date',
                'customer.modify_date'
            )
            ->orderBy('customer.user_id', 'DESC')
            ->get();

        return $customers->map(function ($customer) {
            return [
                'Customer ID'     => $customer->user_id,
                'Username'           => $customer->username,
                'Name'       => $customer->name,
                'Email'   => $customer->email,
                'Mobile'         => $customer->mobile,
                'Address'      => $customer->address,
                'Gender'     => $customer->gender,
                'Aponjon Number'     => $customer->aponjon_number,
                'Status'         => $customer->status == 1 ? 'Active' : 'Inactive',
                'Created Date'   => $customer->created_date,
                'Modified Date'  => $customer->modify_date,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Customer ID',
            'Username',
            'Name',
            'Email',
            'Mobile',
            'Address',
            'Gender',
            'Aponjon Number',
            'Status',
            'Created Date',
            'Modified Date',
        ];
    }
}