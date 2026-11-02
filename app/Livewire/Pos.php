<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pos extends Component
{
    /**
     * Search keyword for products.
     *
     * @var string
     */
    public string $search = '';

    /**
     * Selected category filter or quick filter.
     *
     * @var string
     */
    public string $stockFilter = 'all';

    /**
     * Cart items.
     *
     * @var array<int, array{
     *     id: int,
     *     code: string,
     *     name: string,
     *     price: int,
     *     stock: int,
     *     quantity: int,
     *     line_total: int
     * }>
     */
    public array $cart = [];

    /**
     * Cash paid by the customer.
     *
     * @var int|string
     */
    public int|string $paidAmount = '';

    /**
     * Notification message for user feedback.
     *
     * @var string|null
     */
    public ?string $errorMessage = null;

    /**
     * Success message.
     *
     * @var string|null
     */
    public ?string $successMessage = null;

    /**
     * Currently completed transaction for receipt modal.
     *
     * @var Transaction|null
     */
    public ?Transaction $completedTransaction = null;

    /**
     * Whether receipt modal is shown.
     *
     * @var bool
     */
    public bool $showReceiptModal = false;

    /**
     * Add a product to the cart.
     */
    public function addToCart(int $productId): void
    {
        $this->clearMessages();

        $product = Product::find($productId);
        if (! $product) {
            $this->errorMessage = 'Produk tidak ditemukan.';
            return;
        }

        if ($product->stock <= 0) {
            $this->errorMessage = "Stok untuk produk '{$product->name}' sudah habis!";
            return;
        }

        $existingKey = null;
        foreach ($this->cart as $key => $item) {
            if ($item['id'] === $productId) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey !== null) {
            $newQty = $this->cart[$existingKey]['quantity'] + 1;
            if ($newQty > $product->stock) {
                $this->errorMessage = "Jumlah melebihi stok yang tersedia ({$product->stock} item).";
                return;
            }

            $this->cart[$existingKey]['quantity'] = $newQty;
            $this->cart[$existingKey]['line_total'] = $newQty * $this->cart[$existingKey]['price'];
        } else {
            $this->cart[] = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'quantity' => 1,
                'line_total' => $product->price,
            ];
        }

        $this->successMessage = "'{$product->name}' ditambahkan ke keranjang.";
    }

    /**
     * Update quantity for a cart item.
     */
    public function updateQuantity(int $index, int $qty): void
    {
        $this->clearMessages();

        if (! isset($this->cart[$index])) {
            return;
        }

        $product = Product::find($this->cart[$index]['id']);
        $maxStock = $product ? $product->stock : $this->cart[$index]['stock'];

        if ($qty <= 0) {
            $this->removeFromCart($index);
            return;
        }

        if ($qty > $maxStock) {
            $this->cart[$index]['quantity'] = $maxStock;
            $this->cart[$index]['line_total'] = $maxStock * $this->cart[$index]['price'];
            $this->errorMessage = "Jumlah disesuaikan dengan stok maksimal ({$maxStock} item).";
            return;
        }

        $this->cart[$index]['quantity'] = $qty;
        $this->cart[$index]['line_total'] = $qty * $this->cart[$index]['price'];
    }

    /**
     * Increment quantity.
     */
    public function incrementQuantity(int $index): void
    {
        if (isset($this->cart[$index])) {
            $this->updateQuantity($index, $this->cart[$index]['quantity'] + 1);
        }
    }

    /**
     * Decrement quantity.
     */
    public function decrementQuantity(int $index): void
    {
        if (isset($this->cart[$index])) {
            $this->updateQuantity($index, $this->cart[$index]['quantity'] - 1);
        }
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart(int $index): void
    {
        if (isset($this->cart[$index])) {
            $removedName = $this->cart[$index]['name'];
            array_splice($this->cart, $index, 1);
            $this->successMessage = "'{$removedName}' dihapus dari keranjang.";
        }
    }

    /**
     * Clear all cart items.
     */
    public function clearCart(): void
    {
        $this->cart = [];
        $this->paidAmount = '';
        $this->clearMessages();
    }

    /**
     * Set quick paid amount.
     */
    public function setQuickAmount(int $amount): void
    {
        $this->paidAmount = $amount;
    }

    /**
     * Set exact payment (uang pas).
     */
    public function setExactAmount(): void
    {
        $this->paidAmount = $this->getSubtotal();
    }

    /**
     * Get subtotal of current cart.
     */
    public function getSubtotal(): int
    {
        return (int) array_sum(array_column($this->cart, 'line_total'));
    }

    /**
     * Calculate change amount.
     */
    public function getChangeAmount(): int
    {
        $paid = (int) $this->paidAmount;
        $subtotal = $this->getSubtotal();

        return max(0, $paid - $subtotal);
    }

    /**
     * Process cash payment and save transaction.
     */
    public function processPayment(): void
    {
        $this->clearMessages();

        if (empty($this->cart)) {
            $this->errorMessage = 'Keranjang belanja masih kosong! Silakan pilih produk terlebih dahulu.';
            return;
        }

        $subtotal = $this->getSubtotal();
        $paid = (int) $this->paidAmount;

        if ($paid < $subtotal) {
            $shortage = $subtotal - $paid;
            $this->errorMessage = 'Uang tunai kurang Rp ' . number_format($shortage, 0, ',', '.') . '. Pembayaran harus sekurang-kurangnya sama dengan total.';
            return;
        }

        // Validate stock for all cart items against database
        foreach ($this->cart as $item) {
            $product = Product::find($item['id']);
            if (! $product || $product->stock < $item['quantity']) {
                $available = $product ? $product->stock : 0;
                $this->errorMessage = "Stok untuk '{$item['name']}' tidak mencukupi (Tersisa: {$available}, Di keranjang: {$item['quantity']}).";
                return;
            }
        }

        $change = $paid - $subtotal;

        // Generate unique invoice number: INV-YYYYMMDD-XXXX
        $dateStr = now()->format('Ymd');
        $countToday = Transaction::whereDate('transaction_date', Carbon::today())->count() + 1;
        $invoiceNo = sprintf('INV-%s-%04d', $dateStr, $countToday);

        // Ensure uniqueness
        while (Transaction::where('invoice_no', $invoiceNo)->exists()) {
            $countToday++;
            $invoiceNo = sprintf('INV-%s-%04d', $dateStr, $countToday);
        }

        $transaction = DB::transaction(function () use ($invoiceNo, $subtotal, $paid, $change) {
            $txn = Transaction::create([
                'invoice_no' => $invoiceNo,
                'transaction_date' => now(),
                'subtotal' => $subtotal,
                'paid_amount' => $paid,
                'change_amount' => $change,
            ]);

            foreach ($this->cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $txn->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);

                // Atomically decrement product stock
                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }

            return $txn;
        });

        $this->completedTransaction = $transaction->load('items');
        $this->showReceiptModal = true;
        $this->cart = [];
        $this->paidAmount = '';
        $this->successMessage = 'Transaksi berhasil disimpan dan stok produk telah diperbarui!';
    }

    /**
     * Close receipt modal and start new transaction.
     */
    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->completedTransaction = null;
    }

    /**
     * Clear alert messages.
     */
    public function clearMessages(): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;
    }

    /**
     * Render the POS component.
     */
    public function render(): View
    {
        $query = Product::query();

        if (! empty($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('code', 'like', $term);
            });
        }

        if ($this->stockFilter === 'available') {
            $query->where('stock', '>', 0);
        } elseif ($this->stockFilter === 'low') {
            $query->where('stock', '<=', 5);
        }

        $products = $query->orderBy('name')->get();

        return view('livewire.pos', [
            'products' => $products,
            'subtotal' => $this->getSubtotal(),
            'changeAmount' => $this->getChangeAmount(),
        ])->layout('layouts.app', ['title' => 'Kasir (POS) - Kasir Tunai Nusantara']);
    }
}
