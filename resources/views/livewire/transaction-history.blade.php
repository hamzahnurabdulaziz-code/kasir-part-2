<div class="space-y-6">
    <!-- Feedback Alerts -->
    @if($successMessage)
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl flex items-start justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="text-emerald-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-emerald-800">{{ $successMessage }}</p>
            </div>
            <button wire:click="clearMessages" class="text-emerald-400 hover:text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if($errorMessage)
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl flex items-start justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="text-rose-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-rose-800">{{ $errorMessage }}</p>
            </div>
            <button wire:click="clearMessages" class="text-rose-400 hover:text-rose-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Top Section -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Riwayat Penjualan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Daftar rekaman transaksi kasir, rincian pembayaran, dan cetak ulang struk</p>
        </div>
        <a href="{{ route('pos') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Transaksi Baru (POS)</span>
        </a>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <!-- Search Invoice -->
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari No. Invoice..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all font-mono"
                >
            </div>

            <!-- Date Filter -->
            <div class="w-full sm:w-48">
                <input 
                    type="date" 
                    wire:model.live="dateFilter" 
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-slate-700"
                >
            </div>
        </div>

        @if(!empty($search) || !empty($dateFilter))
            <button 
                wire:click="clearFilters" 
                class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset Filter
            </button>
        @endif
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">No. Invoice</th>
                        <th class="px-5 py-3.5">Tanggal & Waktu</th>
                        <th class="px-5 py-3.5">Item Barang</th>
                        <th class="px-5 py-3.5">Total Belanja</th>
                        <th class="px-5 py-3.5">Bayar / Kembali</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $txn)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4 font-mono font-bold text-xs text-indigo-700">
                                {{ $txn->invoice_no }}
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600">
                                <p class="font-medium text-slate-800">{{ $txn->transaction_date->format('d/m/Y') }}</p>
                                <p class="text-[11px] text-slate-400">{{ $txn->transaction_date->format('H:i:s') }} WIB</p>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600">
                                <span class="inline-flex items-center gap-1 font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $txn->items->count() }} jenis ({{ $txn->items->sum('quantity') }} pcs)
                                </span>
                            </td>
                            <td class="px-5 py-4 font-extrabold text-sm text-slate-800">
                                Rp {{ number_format($txn->subtotal, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-xs">
                                <p class="text-slate-600">Bayar: <span class="font-medium">Rp {{ number_format($txn->paid_amount, 0, ',', '.') }}</span></p>
                                <p class="text-emerald-700 font-semibold">Kembali: Rp {{ number_format($txn->change_amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Detail -->
                                    <button 
                                        wire:click="viewDetails({{ $txn->id }})" 
                                        class="px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-semibold transition-colors flex items-center gap-1"
                                        title="Rincian Transaksi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>Detail</span>
                                    </button>

                                    <!-- Print -->
                                    <button 
                                        wire:click="printReceipt({{ $txn->id }})" 
                                        class="p-1.5 rounded-lg text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                        title="Cetak Struk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete -->
                                    <button 
                                        wire:click="confirmDelete({{ $txn->id }})" 
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                        title="Hapus Transaksi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm font-semibold text-slate-600">Belum ada riwayat transaksi</p>
                                <p class="text-xs text-slate-400 mt-1">Lakukan transaksi di Kasir (POS) untuk merekam data penjualan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Transaksi -->
    @if($showDetailModal && $selectedTransaction)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all animate-scale-up">
                
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div>
                        <span class="text-[11px] font-mono text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded font-bold">
                            {{ $selectedTransaction->invoice_no }}
                        </span>
                        <h3 class="font-bold text-base text-slate-800 mt-1">Rincian Transaksi Penjualan</h3>
                    </div>
                    <button wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Meta info -->
                    <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <div>
                            <span class="text-slate-400 block">Waktu Transaksi</span>
                            <span class="font-semibold text-slate-700">{{ $selectedTransaction->transaction_date->format('d F Y, H:i:s') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Metode Pembayaran</span>
                            <span class="font-semibold text-emerald-600">Tunai (Cash)</span>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Item Produk</h4>
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold">
                                    <tr>
                                        <th class="px-3 py-2">Nama Produk</th>
                                        <th class="px-3 py-2 text-center">Qty</th>
                                        <th class="px-3 py-2 text-right">Harga</th>
                                        <th class="px-3 py-2 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($selectedTransaction->items as $item)
                                        <tr>
                                            <td class="px-3 py-2.5 font-semibold text-slate-800">
                                                {{ $item->product_name }}
                                            </td>
                                            <td class="px-3 py-2.5 text-center font-bold text-slate-700">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-3 py-2.5 text-right text-slate-600">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-3 py-2.5 text-right font-extrabold text-slate-800">
                                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs">
                        <div class="flex justify-between font-bold text-sm text-slate-800">
                            <span>Total Pembayaran:</span>
                            <span class="text-indigo-700">Rp {{ number_format($selectedTransaction->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Uang Diterima:</span>
                            <span>Rp {{ number_format($selectedTransaction->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-emerald-700 font-bold border-t border-slate-200 pt-1.5">
                            <span>Kembalian:</span>
                            <span>Rp {{ number_format($selectedTransaction->change_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="pt-2 flex gap-3">
                        <button 
                            type="button" 
                            wire:click="printReceipt({{ $selectedTransaction->id }})" 
                            class="flex-1 py-2.5 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            <span>Cetak Struk Transaksi Ini</span>
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeDetailModal" 
                            class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Cetak Struk -->
    @if($showReceiptModal && $selectedTransaction)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all animate-scale-up">
                
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <h3 class="font-bold text-sm text-slate-800">Cetak Struk Penjualan</h3>
                    </div>
                    <button wire:click="closeReceiptModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Printable Struk Thermal -->
                <div id="printable-receipt" class="p-6 font-mono text-xs text-slate-800 bg-white space-y-3">
                    <div class="text-center border-b border-dashed border-slate-300 pb-3">
                        <h2 class="text-base font-black tracking-wider uppercase">KASIR NUSANTARA</h2>
                        <p class="text-[11px] text-slate-500">Jl. Nusantara No. 1, Indonesia</p>
                        <p class="text-[11px] text-slate-500">Telp: 0812-3456-7890</p>
                    </div>

                    <div class="space-y-1 text-[11px] border-b border-dashed border-slate-300 pb-2">
                        <div class="flex justify-between">
                            <span class="text-slate-500">No. Invoice:</span>
                            <span class="font-bold">{{ $selectedTransaction->invoice_no }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Waktu:</span>
                            <span>{{ $selectedTransaction->transaction_date->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Metode:</span>
                            <span class="font-semibold uppercase">Tunai</span>
                        </div>
                    </div>

                    <!-- Items list -->
                    <div class="space-y-2 border-b border-dashed border-slate-300 pb-3 text-[11px]">
                        @foreach($selectedTransaction->items as $item)
                            <div>
                                <p class="font-bold text-slate-800">{{ $item->product_name }}</p>
                                <div class="flex justify-between text-slate-600">
                                    <span>{{ $item->quantity }} &times; Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    <span class="font-semibold">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total & Payment -->
                    <div class="space-y-1.5 text-[11px] border-b border-dashed border-slate-300 pb-3">
                        <div class="flex justify-between font-bold text-sm pt-1">
                            <span>TOTAL:</span>
                            <span>Rp {{ number_format($selectedTransaction->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Bayar (Tunai):</span>
                            <span>Rp {{ number_format($selectedTransaction->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-semibold">
                            <span class="text-slate-600">Kembalian:</span>
                            <span>Rp {{ number_format($selectedTransaction->change_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Receipt Footer -->
                    <div class="text-center text-[10px] text-slate-500 pt-1 space-y-1">
                        <p>Terima kasih atas kunjungan Anda!</p>
                        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex gap-2">
                    <button 
                        type="button" 
                        onclick="window.print()" 
                        class="flex-1 py-2.5 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Cetak Sekarang</span>
                    </button>
                    <button 
                        type="button" 
                        wire:click="closeReceiptModal" 
                        class="flex-1 py-2.5 px-3 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl border border-slate-300 transition-all active:scale-95">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Konfirmasi Hapus Transaksi -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all animate-scale-up p-6 text-center space-y-4">
                <div class="w-12 h-12 mx-auto rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-base text-slate-800">Hapus Transaksi?</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Yakin ingin menghapus riwayat transaksi <span class="font-bold text-slate-700">{{ $deletingInvoiceNo }}</span>?
                    </p>
                </div>
                <div class="flex items-center justify-center gap-2 pt-2">
                    <button 
                        wire:click="closeDeleteModal" 
                        class="flex-1 py-2 px-3 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button 
                        wire:click="delete" 
                        class="flex-1 py-2 px-3 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow transition-all active:scale-95">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
