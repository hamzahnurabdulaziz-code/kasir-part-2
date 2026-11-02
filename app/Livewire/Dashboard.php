<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * Render the dashboard component.
     */
    public function render(): View
    {
        $today = Carbon::today();

        $todayTransactions = Transaction::whereDate('transaction_date', $today)->get();
        $todaySales = (int) $todayTransactions->sum('subtotal');
        $todayTransactionCount = $todayTransactions->count();

        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();
        $lowStockCount = $lowStockProducts->count();

        $recentTransactions = Transaction::with('items')
            ->latest('transaction_date')
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'todaySales' => $todaySales,
            'todayTransactionCount' => $todayTransactionCount,
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'lowStockProducts' => $lowStockProducts,
            'recentTransactions' => $recentTransactions,
        ])->layout('layouts.app', ['title' => 'Dashboard - Kasir Tunai Nusantara']);
    }
}
