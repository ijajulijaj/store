<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class allCustomerReportController extends Controller
{
    public function exportAllCustomers(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return Excel::download(new CustomerExport($start_date, $end_date), 'all_customers.xlsx');
    }
}