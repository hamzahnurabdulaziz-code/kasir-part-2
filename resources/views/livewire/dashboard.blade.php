<div class="space-y-6">
    <!-- Header banner -->
    <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs uppercase tracking-wider font-semibold bg-white/20 px-2.5 py-0.5 rounded-full">Ringkasan Hari Ini</span>
                <span class="text-xs text-indigo-100">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold mt-1 tracking-tight">Selamat Datang di Kasir Nusantara</h1>
            <p class="text-sm text-indigo-100 mt-1">Kelola penjualan tunai, stok barang, dan riwayat transaksi dengan cepat dan akurat.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('pos') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-xl shadow-md transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Buka Kasir (POS)</span>
            </a>
            <a href="{{ route('products') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-medium text-sm rounded-xl border border-white/20 transition-all">
                <span>Kelola Produk</span>
            </a>
        </div>
    </div>

    <!-- 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Penjualan Hari Ini -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Penjualan Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-600 font-medium mt-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Total omzet tunai
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Card 2: Jumlah Transaksi Hari Ini -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaksi Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($todayTransactionCount, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Struk selesai dicetak</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>

        <!-- Card 3: Total Produk -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Produk</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalProducts, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Item aktif di katalog</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <!-- Card 4: Stok Menipis -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Menipis (≤ 5)</p>
                <h3 class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-slate-800' }} mt-1">{{ $lowStockCount }}</h3>
                <p class="text-xs {{ $lowStockCount > 0 ? 'text-amber-600 font-medium' : 'text-slate-500' }} mt-1">
                    {{ $lowStockCount > 0 ? 'Perlu segera restock' : 'Semua stok aman' }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl {{ $lowStockCount > 0 ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-slate-50 text-slate-400 border border-slate-200' }} flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout: Stok Menipis & Transaksi Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Section: Peringatan Stok Menipis -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Peringatan Stok Menipis</h2>
                        <p class="text-xs text-slate-500">Produk dengan stok tersisa 5 atau kurang</p>
                    </div>
                </div>
                <a href="{{ route('products') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                    <span>Lihat Semua Produk</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($lowStockProducts->isEmpty())
                <div class="py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                    <div class="w-10 h-10 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Kondisi Stok Sangat Baik!</p>
                    <p class="text-xs text-slate-500 mt-0.5">Tidak ada produk dengan stok menipis saat ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-semibold text-slate-400 uppercase">
                                <th class="pb-2.5">Kode & Produk</th>
                                <th class="pb-2.5">Harga</th>
                                <th class="pb-2.5 text-center">Sisa Stok</th>
                                <th class="pb-2.5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($lowStockProducts as $product)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3">
                                        <p class="font-semibold text-slate-800">{{ $product->name }}</p>
                                        <p class="text-xs font-mono text-slate-500">{{ $product->code }}</p>
                                    </td>
                                    <td class="py-3 font-medium text-slate-700">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $product->stock == 0 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $product->stock }} item
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        @if($product->stock == 0)
                                            <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">Habis</span>
                                        @else
                                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Menipis</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Section: Transaksi Terbaru -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Transaksi Terakhir</h2>
                        <p class="text-xs text-slate-500">5 transaksi tunai terbaru</p>
                    </div>
                </div>
                <a href="{{ route('history') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                    <span>Lihat Riwayat Lengkap</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($recentTransactions->isEmpty())
                <div class="py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                    <div class="w-10 h-10 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum Ada Transaksi</p>
                    <p class="text-xs text-slate-500 mt-0.5">Buka kasir untuk memulai transaksi pertama Anda.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($recentTransactions as $transaction)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-xs text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                        {{ $transaction->invoice_no }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        {{ $transaction->transaction_date->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $transaction->items->count() }} jenis barang ({{ $transaction->items->sum('quantity') }} pcs)
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-extrabold text-slate-800">
                                    Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                                </p>
                                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">
                                    Tunai Lunas
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
