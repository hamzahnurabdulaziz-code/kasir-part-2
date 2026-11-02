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
            <h1 class="text-xl font-bold text-slate-800">Manajemen Produk</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data inventaris produk, penetapan harga jual, dan jumlah stok</p>
        </div>
        <button 
            wire:click="create" 
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Tambah Produk Baru</span>
        </button>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Cari kode atau nama produk..." 
                class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
            >
        </div>

        <div class="flex items-center gap-1.5 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
            <button 
                wire:click="$set('stockFilter', 'all')" 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all {{ $stockFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </button>
            <button 
                wire:click="$set('stockFilter', 'available')" 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all {{ $stockFilter === 'available' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Stok Aman (&gt;5)
            </button>
            <button 
                wire:click="$set('stockFilter', 'low')" 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all {{ $stockFilter === 'low' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Stok Menipis (1-5)
            </button>
            <button 
                wire:click="$set('stockFilter', 'out')" 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all {{ $stockFilter === 'out' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Habis (0)
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">Kode</th>
                        <th class="px-5 py-3.5">Nama Produk</th>
                        <th class="px-5 py-3.5">Harga Jual</th>
                        <th class="px-5 py-3.5 text-center">Stok</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4 font-mono font-bold text-xs text-indigo-700">
                                {{ $product->code }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $product->name }}
                            </td>
                            <td class="px-5 py-4 font-extrabold text-slate-700">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center font-bold text-slate-800">
                                {{ $product->stock }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($product->stock == 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                        Habis
                                    </span>
                                @elseif($product->stock <= 5)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                        Menipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button 
                                        wire:click="edit({{ $product->id }})" 
                                        class="p-1.5 rounded-lg text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                        title="Ubah Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button 
                                        wire:click="confirmDelete({{ $product->id }})" 
                                        class="p-1.5 rounded-lg text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                        title="Hapus Produk">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada produk yang cocok</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter status stok.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form (Tambah / Edit Produk) -->
    @if($showFormModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all animate-scale-up">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h3 class="font-bold text-base text-slate-800">
                        {{ $editingProductId ? 'Ubah Data Produk' : 'Tambah Produk Baru' }}
                    </h3>
                    <button wire:click="closeFormModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="p-6 space-y-4">
                    <!-- Kode Produk -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Kode Produk <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="code" 
                            placeholder="Contoh: BRG-001" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('code') border-rose-500 @else border-slate-300 @enderror rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                        >
                        @error('code')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Produk -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Produk <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="name" 
                            placeholder="Contoh: Minyak Goreng 2L" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border @error('name') border-rose-500 @else border-slate-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                        >
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grid: Harga & Stok -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Harga -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Harga Jual (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                wire:model="price" 
                                placeholder="0" 
                                min="0"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('price') border-rose-500 @else border-slate-300 @enderror rounded-xl text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                            >
                            @error('price')
                                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stok -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Jumlah Stok <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                wire:model="stock" 
                                placeholder="0" 
                                min="0"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('stock') border-rose-500 @else border-slate-300 @enderror rounded-xl text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                            >
                            @error('stock')
                                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            wire:click="closeFormModal" 
                            class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow transition-all active:scale-95">
                            {{ $editingProductId ? 'Simpan Perubahan' : 'Tambahkan Produk' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Konfirmasi Hapus -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all animate-scale-up p-6 text-center space-y-4">
                <div class="w-12 h-12 mx-auto rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-base text-slate-800">Hapus Produk?</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Apakah Anda yakin ingin menghapus <span class="font-bold text-slate-700">"{{ $deletingProductName }}"</span>?
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1 bg-slate-50 p-2 rounded-lg border border-slate-100">
                        Catatan: Riwayat transaksi penjualan masa lalu yang memuat produk ini akan tetap tersimpan utuh.
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
