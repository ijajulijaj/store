<?php

namespace App\Http\Controllers;

use App\Exports\PendingOrdersExport;
use App\Exports\CompleteOrdersExport;
use App\Exports\CancelOrdersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class OrderReportController extends Controller
{
    public function index()
    {
        return view('admin.orders.report.index');
    }

    public function ordereReportExport(Request $request)
    {
        $startDate = $request->query('start_date', date('Y-m-d'));
        $endDate   = $request->query('end_date', date('Y-m-d'));

        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->withOptions([
            'stream' => true,
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/order_report_export', [
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'user_type'    => session('user_type'),   // ✅ pass user_type
            'outlet_code'  => session('outlet_code')  // ✅ pass outlet_code
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Failed to download report');
        }

        return response()->streamDownload(function () use ($response) {
            echo $response->body();
        }, "order_report_{$startDate}_to_{$endDate}.xlsx");
    }

    public function exportPendingOrders(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return Excel::download(new PendingOrdersExport($start_date, $end_date), 'pending_orders.xlsx');
    }

    public function exportCompleteOrders(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return Excel::download(new CompleteOrdersExport($start_date, $end_date), 'complete_orders.xlsx');
    }

    public function exportCancelOrders(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return Excel::download(new CancelOrdersExport($start_date, $end_date), 'cancel_orders.xlsx');
    }

    public function onlineTransactionExport(Request $request)
    {
        $startDate = $request->query('start_date', date('Y-m-d'));
        $endDate   = $request->query('end_date', date('Y-m-d'));

        $response = Http::withHeaders([
            'access_token' => session('access_token')
        ])->withOptions([
            'stream' => true,
        ])->post(env('NODE_API_BASE_URL') . '/api/admin/export_online_transaction', [
            'start_date' => $startDate,
            'end_date'   => $endDate
        ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Failed to download report');
        }

        return response()->streamDownload(function () use ($response) {
            echo $response->body();
        }, "online_transactions_{$startDate}_to_{$endDate}.xlsx");
    }
}