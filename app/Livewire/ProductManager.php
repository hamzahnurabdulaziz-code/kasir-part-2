<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;

    /**
     * Search query.
     */
    public string $search = '';

    /**
     * Stock filter: 'all', 'available', 'low', 'out'.
     */
    public string $stockFilter = 'all';

    /**
     * Form state for creating or editing.
     */
    public ?int $editingProductId = null;
    public string $code = '';
    public string $name = '';
    public int|string $price = '';
    public int|string $stock = '';

    /**
     * Modal visibility flags.
     */
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingProductId = null;
    public ?string $deletingProductName = null;

    /**
     * Feedback alerts.
     */
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    /**
     * Validation rules for saving a product.
     *
     * @return array<string, list<mixed>>
     */
    protected function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'code')->ignore($this->editingProductId),
            ],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'code.required' => 'Kode produk wajib diisi.',
            'code.unique' => 'Kode produk sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.integer' => 'Harga produk harus berupa angka bulat.',
            'price.min' => 'Harga produk tidak boleh bernilai negatif.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok produk harus berupa angka bulat.',
            'stock.min' => 'Stok produk tidak boleh bernilai negatif.',
        ];
    }

    /**
     * Reset pagination when search is updated.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when filter is updated.
     */
    public function updatingStockFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Open modal to create a new product.
     */
    public function create(): void
    {
        $this->resetForm();
        // Generate automatic code proposal (e.g. BRG-XXX)
        $latest = Product::withTrashed()->latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $this->code = sprintf('BRG-%03d', $nextId);

        $this->showFormModal = true;
    }

    /**
     * Open modal to edit existing product.
     */
    public function edit(int $productId): void
    {
        $this->resetForm();
        $product = Product::find($productId);
        if (! $product) {
            $this->errorMessage = 'Produk tidak ditemukan.';
            return;
        }

        $this->editingProductId = $product->id;
        $this->code = $product->code;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->showFormModal = true;
    }

    /**
     * Save product (create or update).
     */
    public function save(): void
    {
        $this->validate();

        if ($this->editingProductId) {
            $product = Product::find($this->editingProductId);
            if ($product) {
                $product->update([
                    'code' => trim($this->code),
                    'name' => trim($this->name),
                    'price' => (int) $this->price,
                    'stock' => (int) $this->stock,
                ]);
                $this->successMessage = "Produk '{$product->name}' berhasil diperbarui.";
            }
        } else {
            $product = Product::create([
                'code' => trim($this->code),
                'name' => trim($this->name),
                'price' => (int) $this->price,
                'stock' => (int) $this->stock,
            ]);
            $this->successMessage = "Produk '{$product->name}' berhasil ditambahkan.";
        }

        $this->closeFormModal();
    }

    /**
     * Prompt delete confirmation modal.
     */
    public function confirmDelete(int $productId): void
    {
        $product = Product::find($productId);
        if ($product) {
            $this->deletingProductId = $product->id;
            $this->deletingProductName = $product->name;
            $this->showDeleteModal = true;
        }
    }

    /**
     * Execute soft deletion.
     */
    public function delete(): void
    {
        if ($this->deletingProductId) {
            $product = Product::find($this->deletingProductId);
            if ($product) {
                $name = $product->name;
                $product->delete();
                $this->successMessage = "Produk '{$name}' berhasil dihapus (riwayat transaksi masa lalu tetap tersimpan).";
            }
            $this->closeDeleteModal();
        }
    }

    /**
     * Close form modal.
     */
    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    /**
     * Close delete modal.
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingProductId = null;
        $this->deletingProductName = null;
    }

    /**
     * Clear messages.
     */
    public function clearMessages(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    /**
     * Reset form fields.
     */
    private function resetForm(): void
    {
        $this->resetValidation();
        $this->editingProductId = null;
        $this->code = '';
        $this->name = '';
        $this->price = '';
        $this->stock = '';
    }

    /**
     * Render the product manager component.
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
            $query->where('stock', '>', 5);
        } elseif ($this->stockFilter === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', 5);
        } elseif ($this->stockFilter === 'out') {
            $query->where('stock', '<=', 0);
        }

        $products = $query->orderBy('name')->paginate(10);

        return view('livewire.product-manager', [
            'products' => $products,
        ])->layout('layouts.app', ['title' => 'Kelola Produk - Kasir Tunai Nusantara']);
    }
}
