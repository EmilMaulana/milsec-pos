<?php

namespace App\Livewire\Transactions;

use Livewire\Component;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendTransactionMessageJob;
use App\Models\Cashflow;
use App\Models\User;


class Index extends Component
{
    public $isPaymentConfirmed = false;
    public $transactionProcessed = false; // Set agar memicu cetak struk
    public $customer_name;
    public $transactionItems = [];
    public $totalPrice = 0;
    public $search = '';
    public $showModal = false;
    public $paymentMethod;
    public $paymentAmount = 0; // Inisialisasi dengan 0
    public $changeAmount = 0;
    public $totalPriceBeforeDiscount = 0;
    public $phone; // Pastikan $changeAmount diinisialisasi di sini
    protected $whatsappService;
    public $paymentConfirmed = false;

    public function mount()
    {
        $this->totalPrice = $this->getTotalPrice();
        $this->paymentMethod = 'cash';

    }

    public function dehydrate()
    {
        if ($this->paymentConfirmed = false) {
            $this->updateTotalPrice();
        }
    }
    
    public function updatedPaymentAmount($value)
    {
        // Pastikan untuk mengkonversi value ke float sebelum perhitungan
        $paymentValue = (float) $value; // Ubah ke float untuk akurasi desimal
        $this->changeAmount = $paymentValue - $this->totalPrice; // Hitung kembalian
    }

    public function addTransactionItem($productId)
    {
        $product = Product::find($productId);

        // Cek apakah stok produk cukup sebelum mengurangi
        if ($product->stock < 1) {
            // Menampilkan pesan kesalahan jika stok tidak cukup
            session()->flash('error', 'Stok produk tidak cukup.');
            return;
        }

        // Kurangi stok produk di database jika stok mencukupi
        $product->decrement('stock', 1);

        // Periksa apakah produk sudah ada dalam daftar transaksi
        $existingItemIndex = collect($this->transactionItems)->search(function ($item) use ($productId) {
            return $item['product']->id === $productId;
        });

        if ($existingItemIndex !== false) {
            // Jika produk sudah ada, tambah kuantitas
            $this->transactionItems[$existingItemIndex]['quantity']++;
        } else {
            // Jika produk belum ada, tambahkan produk baru ke transaksi
            $this->transactionItems[] = [
                'product' => $product,
                'quantity' => 1,
            ];
        }

        $this->updateTotalPrice();
        $this->search = '';
    }

    public function increaseTransactionItemQuantity($index)
    {
        // Ambil item produk yang ada di transaksi
        $product = $this->transactionItems[$index]['product'];

        // Cek apakah stok cukup untuk menambah kuantitas
        if ($product->stock < 1) {
            // Menampilkan pesan kesalahan jika stok tidak cukup
            session()->flash('error', 'Stok produk tidak cukup.');
            return;
        }

        // Tambah kuantitas di transaksi
        $this->transactionItems[$index]['quantity']++;
        
        // Kurangi stok produk di database
        $product->decrement('stock', 1);

        $this->updateTotalPrice();
    }

    public function decreaseTransactionItemQuantity($index)
    {
        // Ambil item produk yang ada di transaksi
        $product = $this->transactionItems[$index]['product'];
        
        // Jika kuantitas lebih dari 1, kurangi kuantitas
        if ($this->transactionItems[$index]['quantity'] > 1) {
            $this->transactionItems[$index]['quantity']--;
            // Tambah stok produk ke database
            $product->increment('stock', 1);
        } else {
            // Jika kuantitas 1, hapus item dari transaksi
            $this->removeTransactionItem($index);
        }

        $this->updateTotalPrice();
    }

    public function removeTransactionItem($index)
    {
        // Ambil produk yang dihapus dari transaksi
        $product = $this->transactionItems[$index]['product'];

        // Tambahkan stok produk kembali ke database
        $product->increment('stock', $this->transactionItems[$index]['quantity']);

        // Hapus item dari daftar transaksi
        unset($this->transactionItems[$index]);
        $this->transactionItems = array_values($this->transactionItems);

        $this->updateTotalPrice();
    }


    public function updateTotalPrice()
    {
        $this->totalPrice = $this->getTotalPrice();
        $this->changeAmount = (float) $this->paymentAmount - (float) $this->totalPrice; // Perhitungan ulang untuk kembalian
    }


    public function getTotalPrice()
    {
        return collect($this->transactionItems)->sum(function ($item) {
            $product = $item['product'];
            $priceAfterDiscount = $product->sell_price - $product->disc; // Harga setelah diskon
            return $priceAfterDiscount * (int) $item['quantity']; // Total harga setelah diskon
        });
    }


    public function confirmPayment()
    {
        // Logika konfirmasi pembayaran
        $this->isPaymentConfirmed = true;
        // Validasi input sebelum memproses pembayaran
        $this->validate([
            'paymentMethod' => 'required',
            'paymentAmount' => 'required|numeric|min:' . $this->totalPrice,
            'phone' => [
                'nullable',
                'string'
            ],
        ], [
            'paymentMethod.required' => 'Metode pembayaran harus dipilih.',
            'paymentAmount.required' => 'Nominal uang harus diisi.',
            'paymentAmount.min' => 'Nominal uang tidak mencukupi untuk total pembayaran.',
        ]);
        
        // Proses transaksi jika validasi berhasil
        $this->completeTransaction();
    }

    public function completeTransaction()
    {
        $this->paymentConfirmed = true;

        
        // Ambil store_id dari pengguna yang sedang login
        $store_id = Auth::user()->store->id;

        // Format nomor telepon
        if (substr($this->phone, 0, 1) === '0') {
            $this->phone = '62' . substr($this->phone, 1);
        }
        if (substr($this->phone, 0, 3) === '+62') {
            $this->phone = substr($this->phone, 1);
        }

        // Hitung total harga sebelum dan setelah diskon
        $this->totalPriceBeforeDiscount = collect($this->transactionItems)->sum(function ($item) {
            return $item['product']->sell_price * $item['quantity'];
        });

        $this->totalPrice = $this->getTotalPrice(); // Total setelah diskon
        $totalDiscount = $this->totalPriceBeforeDiscount - $this->totalPrice; // Total diskon
        
        // Hitung kembalian
        $changeAmount = $this->paymentAmount - $this->totalPrice;

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'store_id' => $store_id,
            'total_price' => $this->totalPrice,
            'payment_method' => $this->paymentMethod,
            'payment_amount' => $this->paymentAmount,
            'disc' => $totalDiscount,
            'change_amount' => $changeAmount,
            'phone' => $this->phone,
            'transaction_date' => now(),
        ]);

        // Proses item transaksi
        foreach ($this->transactionItems as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'price' => $item['product']->sell_price,
            ]);

            // Kurangi stok produk
            $product = Product::find($item['product']->id);
            $product->save();
        }

        // Hitung saldo awal dari cashflow terakhir (jika ada)
        $lastCashflow = Cashflow::where('store_id', $store_id)->orderBy('id', 'desc')->first();
        $startingBalance = $lastCashflow ? $lastCashflow->ending_balance : 0; // Jika tidak ada cashflow sebelumnya, saldo awal adalah 0

        // Catat pemasukan ke tabel cashflow
        $endingBalance = $startingBalance + $this->totalPrice; // Hitung saldo akhir

        Cashflow::create([
            'user_id' => Auth::id(),
            'store_id' => $store_id,
            'amount' => $this->totalPrice, // Jumlah pemasukan
            'type' => 'income', // Tipe transaksi adalah pemasukan
            'starting_balance' => $startingBalance, // Ambil saldo awal
            'ending_balance' => $endingBalance, // Hitung saldo akhir
            'description' => 'Transaksi penjualan pada ' . now(), // Deskripsi transaksi
        ]);

        $usersInStore = User::where('store_id', $store_id)->get();

        // Dispatch job untuk mengirim pesan
        SendTransactionMessageJob::dispatch($transaction, $usersInStore);

        session()->flash('message', 'Transaksi Berhasil!');
        return redirect()->route('transaction.index');

        // Reset cart
        $this->resetCart();
    }


    public function render()
    {
        $user = Auth::user();
        $store_id = $user->store ? $user->store->id : null;

        // Debugging: Pastikan store_id terisi
        Log::info('Store ID: ' . $store_id);

        if (!$store_id) {
            Log::info('Pengguna tidak memiliki store, ID pengguna: ' . $user->id);
            session()->flash('error', 'Store tidak ditemukan.');
            return view('livewire.transactions.index', [
                'products' => collect(), // kosongkan produk
                'transactionId' => str_pad(rand(0, 99999), 8, '0', STR_PAD_LEFT),
            ]);
        }
        

        // Ambil produk berdasarkan pencarian dan store_id
        $products = Product::where('store_id', $store_id)
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get();

        // Debugging: Pastikan produk terisi
        Log::info('Products Count: ' . $products->count());

        $transactionId = str_pad(rand(0, 99999), 8, '0', STR_PAD_LEFT);

        return view('livewire.transactions.index', [
            'products' => $products,
            'transactionId' => $transactionId,
        ]);
    }

    private function resetCart()
    {
        $this->transactionItems = [];
        $this->totalPrice = 0;
        $this->paymentAmount = 0; // Reset paymentAmount ke 0
        $this->changeAmount = 0; // Reset kembalian setelah transaksi
        $this->showModal = false;
    }

    public function resetModal()
    {
        $this->changeAmount = 0; // Reset kembalian setelah transaksi
        $this->showModal = false;
    }
}
