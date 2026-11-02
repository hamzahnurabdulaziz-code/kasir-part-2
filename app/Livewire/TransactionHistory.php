<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionHistory extends Component
{
    use WithPagination;

    /**
     * Search invoice number.
     */
    public string $search = '';

    /**
     * Filter by specific date (YYYY-MM-DD).
     */
    public string $dateFilter = '';

    /**
     * Currently viewed transaction for detail modal or reprint.
     */
    public ?Transaction $selectedTransaction = null;

    /**
     * Modal visibility.
     */
    public bool $showDetailModal = false;
    public bool $showReceiptModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingTransactionId = null;
    public ?string $deletingInvoiceNo = null;

    /**
     * Feedback alert messages.
     */
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    /**
     * Reset pagination when search is updated.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when date filter is updated.
     */
    public function updatingDateFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Open detail modal for transaction.
     */
    public function viewDetails(int $transactionId): void
    {
        $this->selectedTransaction = Transaction::with('items')->find($transactionId);
        if ($this->selectedTransaction) {
            $this->showDetailModal = true;
        }
    }

    /**
     * Open print receipt modal.
     */
    public function printReceipt(int $transactionId): void
    {
        $this->selectedTransaction = Transaction::with('items')->find($transactionId);
        if ($this->selectedTransaction) {
            $this->showReceiptModal = true;
        }
    }

    /**
     * Prompt delete confirmation modal.
     */
    public function confirmDelete(int $transactionId): void
    {
        $transaction = Transaction::find($transactionId);
        if ($transaction) {
            $this->deletingTransactionId = $transaction->id;
            $this->deletingInvoiceNo = $transaction->invoice_no;
            $this->showDeleteModal = true;
        }
    }

    /**
     * Delete transaction.
     */
    public function delete(): void
    {
        if ($this->deletingTransactionId) {
            $transaction = Transaction::find($this->deletingTransactionId);
            if ($transaction) {
                $invoiceNo = $transaction->invoice_no;
                $transaction->delete();
                $this->successMessage = "Transaksi '{$invoiceNo}' berhasil dihapus dari riwayat.";
            }
            $this->closeDeleteModal();
        }
    }

    /**
     * Close detail modal.
     */
    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedTransaction = null;
    }

    /**
     * Close receipt modal.
     */
    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->selectedTransaction = null;
    }

    /**
     * Close delete modal.
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingTransactionId = null;
        $this->deletingInvoiceNo = null;
    }

    /**
     * Clear filters.
     */
    public function clearFilters(): void
    {
        $this->search = '';
        $this->dateFilter = '';
        $this->resetPage();
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
     * Render the component.
     */
    public function render(): View
    {
        $query = Transaction::with('items');

        if (! empty($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $query->where('invoice_no', 'like', $term);
        }

        if (! empty($this->dateFilter)) {
            $query->whereDate('transaction_date', $this->dateFilter);
        }

        $transactions = $query->latest('transaction_date')->paginate(10);

        return view('livewire.transaction-history', [
            'transactions' => $transactions,
        ])->layout('layouts.app', ['title' => 'Riwayat Transaksi - Kasir Tunai Nusantara']);
    }
}
