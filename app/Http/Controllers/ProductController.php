<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel as ExcelExport;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Response;
use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product.index', [
            'title' => 'Semua Produk'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create', [
            'title' => 'Tambah Produk'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($request->all());
        return response()->json([
            'data' => $product,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('product.edit', [
            'title' => $product->name,
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
        ]);

        $product->update($request->all());
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }

    /**
     * Export the products to Excel.
     */

    public function template()
    {
        // Buat path ke file template
        $filePath = public_path('templates/template_import_produk.xlsx');

        // Pastikan file ada
        if (!file_exists($filePath)) {
            abort(404); // Jika file tidak ada, kirim 404
        }

        return Response::download($filePath);
    }

    public function generateBarcode($id)
    {
        $product = Product::findOrFail($id);
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($product->id_barang, $generator::TYPE_CODE_128);

        return response($barcode)->header('Content-Type', 'image/png');
    }

    public function downloadBarcode($id)
    {
        $product = Product::findOrFail($id);

        // Pastikan id_barang ada dan tidak null
        if (empty($product->id_barang)) {
            return response()->json(['error' => 'ID Barang tidak ditemukan.'], 404);
        }

        // Generate barcode image in PNG format
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($product->id_barang, $generator::TYPE_CODE_128);
        $barcodeBase64 = base64_encode($barcode); // Encode ke base64

        // Generate PDF
        $pdf = PDF::loadView('pdf.barcode', [
            'product' => $product,
            'barcode' => $barcodeBase64, // Kirim base64 ke view
        ]);

        // Download the PDF with a specific filename
        return $pdf->download('barcode_' . $product->id_barang . '.pdf');
    }
    // public function downloadBarcode($id)
    // {
    //     $product = Product::findOrFail($id);
    //     $generator = new BarcodeGeneratorPNG();
    //     $barcode = $generator->getBarcode($product->id_barang, $generator::TYPE_CODE_128);

    //     return response($barcode)
    //         ->header('Content-Type', 'image/png')
    //         ->header('Content-Disposition', 'attachment; filename="barcode-'.$product->id_barang.'.png"');
    // }
}
