<div class="space-y-4">
    <!-- Feedback Alerts -->
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

    <!-- Main Grid: Products on Left, Cart & Checkout on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Product Catalog (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
            <!-- Search & Filters Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row gap-3 items-center justify-between">
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama atau kode produk..." 
                        class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                </div>

                <div class="flex items-center gap-1.5 w-full sm:w-auto">
                    <button 
                        wire:click="$set('stockFilter', 'all')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $stockFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua
                    </button>
                    <button 
                        wire:click="$set('stockFilter', 'available')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $stockFilter === 'available' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Tersedia
                    </button>
                    <button 
                        wire:click="$set('stockFilter', 'low')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $stockFilter === 'low' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Stok Menipis
                    </button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @forelse($products as $product)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 hover:border-indigo-400 hover:shadow-md transition-all flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between gap-1 mb-2">
                                <span class="font-mono text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $product->code }}
                                </span>
                                @if($product->stock == 0)
                                    <span class="text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">
                                        Habis
                                    </span>
                                @elseif($product->stock <= 5)
                                    <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                        Sisa {{ $product->stock }}
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        Stok {{ $product->stock }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-bold text-sm text-slate-800 line-clamp-2 leading-snug group-hover:text-indigo-600 transition-colors">
                                {{ $product->name }}
                            </h3>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-sm font-extrabold text-indigo-700">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>

                            @if($product->stock > 0)
                                <button 
                                    wire:click="addToCart({{ $product->id }})" 
                                    class="inline-flex items-center justify-center p-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all active:scale-90 shadow-sm"
                                    title="Tambah ke Keranjang">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </button>
                            @else
                                <span class="text-[11px] font-medium text-slate-400 italic">
                                    Kosong
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-600">Produk Tidak Ditemukan</p>
                        <p class="text-xs text-slate-400 mt-1">Coba kata kunci pencarian yang lain.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Cart & Payment Checkout (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            
            <!-- Cart Box -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h2 class="font-bold text-sm text-slate-800">Keranjang Belanja</h2>
                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-bold">
                            {{ count($cart) }}
                        </span>
                    </div>

                    @if(!empty($cart))
                        <button 
                            wire:click="clearCart" 
                            wire:confirm="Yakin ingin mengosongkan seluruh isi keranjang belanja?"
                            class="text-xs text-rose-600 hover:text-rose-800 font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Kosongkan
                        </button>
                    @endif
                </div>

                <!-- Cart Items List -->
                <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 p-2">
                    @forelse($cart as $index => $item)
                        <div class="p-3 flex items-center justify-between gap-2 hover:bg-slate-50/80 rounded-xl transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $item['name'] }}</p>
                                <p class="text-[11px] text-slate-500">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} &times; {{ $item['quantity'] }}
                                </p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-1.5">
                                <button 
                                    wire:click="decrementQuantity({{ $index }})" 
                                    class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 flex items-center justify-center font-bold text-xs transition-all active:scale-95">
                                    -
                                </button>
                                <span class="w-8 text-center text-xs font-bold text-slate-800">
                                    {{ $item['quantity'] }}
                                </span>
                                <button 
                                    wire:click="incrementQuantity({{ $index }})" 
                                    class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 flex items-center justify-center font-bold text-xs transition-all active:scale-95">
                                    +
                                </button>
                            </div>

                            <div class="text-right w-24">
                                <p class="text-xs font-extrabold text-slate-800">
                                    Rp {{ number_format($item['line_total'], 0, ',', '.') }}
                                </p>
                                <button 
                                    wire:click="removeFromCart({{ $index }})" 
                                    class="text-[11px] text-rose-500 hover:text-rose-700 font-medium">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <div class="w-10 h-10 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-600">Keranjang Masih Kosong</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Pilih produk di sebelah kiri untuk menambahkan ke keranjang.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Subtotal summary -->
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                    <span class="text-sm font-semibold text-slate-600">Total Tagihan</span>
                    <span class="text-xl font-black text-indigo-700">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Payment Box -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-sm text-slate-800">Pembayaran Tunai</h2>
                        <p class="text-xs text-slate-500">Masukkan uang yang diterima dari pembeli</p>
                    </div>
                </div>

                <!-- Cash input -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nominal Uang Diterima (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 font-bold text-sm">
                            Rp
                        </span>
                        <input 
                            type="number" 
                            wire:model.live="paidAmount" 
                            placeholder="0" 
                            min="0"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold text-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all"
                        >
                    </div>
                </div>

                <!-- Quick Amount Buttons -->
                <div>
                    <p class="text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Uang Cepat / Pas</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button 
                            type="button" 
                            wire:click="setExactAmount"
                            class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-200 transition-all active:scale-95">
                            Uang Pas
                        </button>
                        <button 
                            type="button" 
                            wire:click="setQuickAmount(10000)"
                            class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition-all active:scale-95">
                            10.000
                        </button>
                        <button 
                            type="button" 
                            wire:click="setQuickAmount(20000)"
                            class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition-all active:scale-95">
                            20.000
                        </button>
                        <button 
                            type="button" 
                            wire:click="setQuickAmount(50000)"
                            class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition-all active:scale-95">
                            50.000
                        </button>
                        <button 
                            type="button" 
                            wire:click="setQuickAmount(100000)"
                            class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition-all active:scale-95">
                            100.000
                        </button>
                        <button 
                            type="button" 
                            wire:click="setQuickAmount(200000)"
                            class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition-all active:scale-95">
                            200.000
                        </button>
                    </div>
                </div>

                <!-- Kembalian Display -->
                <div class="p-3.5 rounded-xl border {{ (int)$paidAmount >= $subtotal && $subtotal > 0 ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold">Kembalian</span>
                        <span class="text-base font-extrabold {{ (int)$paidAmount >= $subtotal && $subtotal > 0 ? 'text-emerald-700' : 'text-slate-700' }}">
                            Rp {{ number_format($changeAmount, 0, ',', '.') }}
                        </span>
                    </div>

                    @if((int)$paidAmount > 0 && (int)$paidAmount < $subtotal)
                        <div class="mt-2 pt-2 border-t border-rose-200/50 text-xs text-rose-600 font-semibold flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Uang tunai kurang Rp {{ number_format($subtotal - (int)$paidAmount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Process Payment Button -->
                <button 
                    type="button"
                    wire:click="processPayment"
                    wire:loading.attr="disabled"
                    @disabled(empty($cart) || (int)$paidAmount < $subtotal || $subtotal <= 0)
                    class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-extrabold text-sm rounded-xl shadow-md transition-all active:scale-98 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan & Bayar Transaksi</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    @if($showReceiptModal && $completedTransaction)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all animate-scale-up">
                
                <!-- Receipt Header in Modal -->
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-sm text-slate-800">Transaksi Berhasil</h3>
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
                            <span class="font-bold">{{ $completedTransaction->invoice_no }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Waktu:</span>
                            <span>{{ $completedTransaction->transaction_date->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Metode:</span>
                            <span class="font-semibold uppercase">Tunai</span>
                        </div>
                    </div>

                    <!-- Items list -->
                    <div class="space-y-2 border-b border-dashed border-slate-300 pb-3 text-[11px]">
                        @foreach($completedTransaction->items as $item)
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
                            <span>Rp {{ number_format($completedTransaction->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Bayar (Tunai):</span>
                            <span>Rp {{ number_format($completedTransaction->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-semibold">
                            <span class="text-slate-600">Kembalian:</span>
                            <span>Rp {{ number_format($completedTransaction->change_amount, 0, ',', '.') }}</span>
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
                        <span>Cetak Struk</span>
                    </button>
                    <button 
                        type="button" 
                        wire:click="closeReceiptModal" 
                        class="flex-1 py-2.5 px-3 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl border border-slate-300 transition-all active:scale-95">
                        Transaksi Baru
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
