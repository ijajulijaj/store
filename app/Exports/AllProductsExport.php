<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AllProductsExport implements FromCollection, WithHeadings
{
    
    public function collection()
    {
        $products = DB::table('product_detail as p')
            ->leftJoin('category_detail as c', 'p.cat_id', '=', 'c.cat_id')
            ->leftJoin('sub_category_detail as sc', 'p.sub_cat_id', '=', 'sc.sub_cat_id')
            ->select(
                'p.prod_id',
                'p.name',
                'c.cat_name',
                'sc.sub_cat_name',
                'p.detail',
                'p.unit_name',
                'p.unit_value',
                'p.unit_price',
                'p.vat_percentage',
                'p.max_order_qty',
                'p.status',
                'p.created_date',
                'p.modify_date'
            )
            ->orderBy('p.prod_id', 'DESC')
            ->get();

        return $products->map(function ($product) {
            return [
                'Product ID'     => $product->prod_id,
                'Name'           => $product->name,
                'Category'       => $product->cat_name,
                'Sub Category'   => $product->sub_cat_name,
                'Detail'         => $product->detail,
                'Unit Name'      => $product->unit_name,
                'Unit Value'     => $product->unit_value,
                'Unit Price'     => $product->unit_price,
                'VAT %'          => $product->vat_percentage,
                'Max Order Qty'  => $product->max_order_qty,
                'Status'         => $product->status == 1 ? 'Active' : 'Inactive',
                'Created Date'   => $product->created_date,
                'Modified Date'  => $product->modify_date,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Product ID',
            'Name',
            'Category',
            'Sub Category',
            'Detail',
            'Unit Name',
            'Unit Value',
            'Unit Price',
            'VAT %',
            'Max Order Qty',
            'Status',
            'Created Date',
            'Modified Date',
        ];
    }
}