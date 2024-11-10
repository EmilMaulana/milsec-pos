<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil semua produk tanpa kolom slug
        return Product::select('id', 'name', 'price', 'disc', 'unit', 'stock')->get(); 
    }

    public function headings(): array
    {
        return [
            'ID',
            'NAMA PRODUK',
            'HARGA',
            'DISKON',
            'SATUAN',
            'STOK',
        ];
    }
}
