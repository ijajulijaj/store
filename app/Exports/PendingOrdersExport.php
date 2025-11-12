<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PendingOrdersExport implements FromCollection, WithHeadings
{
    private $start_date;
    private $end_date;
    private $user_type;
    private $outlet_code;

    public function __construct($start_date, $end_date)
    {
        $this->start_date   = $start_date;
        $this->end_date     = $end_date;
        $this->user_type    = session('user_type');   // ✅ get from session
        $this->outlet_code  = session('outlet_code'); // ✅ get from session
    }

    public function collection()
    {
        $orders = DB::table('order_detail as od')
            ->join('address_detail as ad', 'od.address_id', '=', 'ad.address_id')
            ->where(function($q) {
                $q->where('od.payment_type', 1)
                  ->orWhere(function($q2) {
                      $q2->where('od.payment_type', 2)
                         ->where('od.payment_status', 2);
                  })
                  ->orWhere('od.payment_type', 3);
            })
            ->where('od.order_status', '<=', 2) // ✅ pending orders
            ->whereBetween(DB::raw('DATE(od.created_date)'), [$this->start_date, $this->end_date]);

        // ✅ Restrict outlet users
        if ($this->user_type == 2 && $this->outlet_code) {
            $orders->where('ad.outlet_code', $this->outlet_code);
        }

        $orders = $orders->select(
                'od.order_id',
                'ad.name as user_name',
                'ad.phone',
                'ad.address',
                'od.user_pay_price',
                'od.promo_code_id',
                'od.payment_type',
                'ad.outlet_code',
                'od.platform',
                'od.created_date',
                'od.order_status'
            )
            ->orderBy('od.order_id','DESC')
            ->get();
    
        $orders = $orders->map(function ($order) {
            $createdDate = \Carbon\Carbon::parse($order->created_date);
            $now = \Carbon\Carbon::now();
        
            // ✅ get total diff in seconds
            $diffInSeconds = $createdDate->diffInSeconds($now);
        
            $delivery_status = 'On Time';
        
            // 8 hours in seconds
            $eightHoursInSeconds = 8 * 60 * 60;
        
            if ($diffInSeconds > $eightHoursInSeconds && $order->order_status < 3) {
                $lateSeconds = $diffInSeconds - $eightHoursInSeconds;
        
                $lateHours = floor($lateSeconds / 3600);
                $lateMinutes = floor(($lateSeconds % 3600) / 60);
        
                $hourPart = "{$lateHours} hr" . ($lateHours !== 1 ? 's' : '');
                $minutePart = "{$lateMinutes} min" . ($lateMinutes !== 1 ? 's' : '');
        
                $delivery_status = "Late : {$hourPart} {$minutePart}";
            }
        
            // ✅ Convert payment_type number to readable text
            $paymentTypeText = match ($order->payment_type) {
                1 => 'Cash On Delivery',
                2 => 'Online Payment',
                3 => 'Swipe Payment',
                default => 'Unknown',
            };
        
            return [
                'order_id'        => $order->order_id,
                'user_name'       => $order->user_name,
                'phone'           => $order->phone,
                'address'         => $order->address,
                'user_pay_price'  => $order->user_pay_price,
                'promo_code_id'   => $order->promo_code_id,
                'payment_type'    => $paymentTypeText, // ✅ updated field
                'outlet_code'     => $order->outlet_code,
                'platform'     => $order->platform,
                'created_date'    => $order->created_date,
                'delivery_status' => $delivery_status,
            ];
        });

        return $orders;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Customer',
            'Phone',
            'Address',
            'Total Price',
            'Promo Code',
            'Payment Type',
            'Outlet',
            'Platform',
            'Order Date',
            'Delivery Status',
        ];
    }
}