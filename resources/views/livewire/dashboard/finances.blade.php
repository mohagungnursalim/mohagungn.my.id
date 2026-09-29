<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto dark:bg-gray-900 min-h-screen" x-data="{ showModal: false, isEdit: false, deleteModal: false, itemToDelete: null }">
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Manajemen Keuangan</h1>
        </div>

        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <button @click="showModal = true; isEdit = false; $wire.resetFields()" class="btn bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg px-4 py-2 flex items-center shadow-sm transition">
                <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                </svg>
                <span class="ml-2 font-medium">Tambah Transaksi</span>
            </button>
        </div>
    </div>



    <!-- Summary Cards Bulan Berjalan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <!-- Pemasukan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pemasukan Bulan Ini</p>
                <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">Rp {{ number_format($summary['in'], 0, ',', '.') }}</p>
            </div>
        </div>
        <!-- Pengeluaran -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center">
                <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pengeluaran Bulan Ini</p>
                <p class="text-xl font-bold text-rose-600 dark:text-rose-400 mt-0.5">Rp {{ number_format($summary['out'], 0, ',', '.') }}</p>
            </div>
        </div>
        <!-- Saldo -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $summary['balance'] >= 0 ? 'bg-indigo-100 dark:bg-indigo-900/40' : 'bg-amber-100 dark:bg-amber-900/40' }} flex items-center justify-center">
                <i class="fa-solid fa-wallet text-xl {{ $summary['balance'] >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-amber-600 dark:text-amber-400' }}"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Saldo Bulan Ini</p>
                <p class="text-xl font-bold {{ $summary['balance'] >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-amber-600 dark:text-amber-400' }} mt-0.5">
                    {{ $summary['balance'] >= 0 ? '+' : '' }}Rp {{ number_format($summary['balance'], 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-100 dark:border-gray-700 relative overflow-hidden">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-3">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                Transaksi Bulan <span class="text-indigo-600 dark:text-indigo-400">{{ \Carbon\Carbon::parse($filterMonth . '-01')->translatedFormat('F Y') }}</span>
                <span class="text-gray-400 font-medium ml-1">({{ $finances->total() }})</span>
            </h2>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <!-- Filter Bulan -->
                <input type="month" wire:model.live="filterMonth"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Cari transaksi...">
                </div>
            </div>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 border-t border-b border-gray-100 dark:border-gray-600">
                        <tr>
                            <th class="px-2 py-3 whitespace-nowrap"><div class="font-semibold text-left">Tanggal</div></th>
                            <th class="px-2 py-3 whitespace-nowrap"><div class="font-semibold text-left">Deskripsi</div></th>
                            <th class="px-2 py-3 whitespace-nowrap"><div class="font-semibold text-left">Tipe</div></th>
                            <th class="px-2 py-3 whitespace-nowrap"><div class="font-semibold text-right">Jumlah (Rp)</div></th>
                            <th class="px-2 py-3 whitespace-nowrap"><div class="font-semibold text-center">Aksi</div></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($finances as $finance)
                        <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-2 py-3 whitespace-nowrap">
                                <div class="text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($finance->date)->format('d M Y') }}</div>
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap">
                                <div class="text-gray-800 dark:text-gray-200 font-medium truncate max-w-xs">{{ $finance->description ?? '-' }}</div>
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap">
                                @if($finance->type === 'in')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap">
                                <div class="text-right font-medium {{ $finance->type === 'in' ? 'text-emerald-500 dark:text-emerald-400' : 'text-rose-500 dark:text-rose-400' }}">
                                    {{ $finance->type === 'in' ? '+' : '-' }} Rp {{ number_format($finance->amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Edit Button -->
                                    <button
                                        x-data="{}"
                                        @click="
                                            showModal = true;
                                            isEdit = true;
                                            $wire.set('financeId', {{ $finance->id }});
                                            $wire.set('type', '{{ $finance->type }}');
                                            $wire.set('amount', {{ $finance->amount }});
                                            $wire.set('description', {{ \Illuminate\Support\Js::from($finance->description ?? '') }});
                                            $wire.set('date', '{{ $finance->date }}');
                                        "
                                        class="text-slate-400 hover:text-indigo-500 rounded-full p-2 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 transition-colors">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16">
                                            <path d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z" />
                                        </svg>
                                    </button>
                                    <!-- Delete Button -->
                                    <button @click="deleteModal = true; itemToDelete = {{ $finance->id }}" class="text-slate-400 hover:text-rose-500 rounded-full p-2 hover:bg-rose-50 dark:hover:bg-rose-500/20 transition-colors">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16">
                                            <path d="M5 7h2v6H5V7zm4 0h2v6H9V7zm3-6v2h4v2h-1v10c0 .6-.4 1-1 1H2c-.6 0-1-.4-1-1V5H0V3h4V1c0-.6.4-1 1-1h6c.6 0 1 .4 1 1zM6 2v1h4V2H6zm7 3H3v9h10V5z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-2 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4M16 8l4 4-4 4"></path></svg>
                                    <p>Belum ada transaksi.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $finances->links() }}
            </div>
        </div>
    </div>

    <!-- Background Overlay -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-900 bg-opacity-75 backdrop-blur-sm"
         @click="showModal = false" style="display: none;"></div>

    <!-- Slide-in Modal dari Kiri -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full shadow-none"
         x-transition:enter-end="translate-x-0 shadow-2xl"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0 shadow-2xl"
         x-transition:leave-end="-translate-x-full shadow-none"
         @close-modal.window="showModal = false"
         class="fixed inset-y-0 left-0 z-50 w-full max-w-sm bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 overflow-y-auto" style="display: none;">
        
        <div class="p-6 h-full flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100" x-text="isEdit ? 'Edit Transaksi' : 'Tambah Transaksi'"></h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 rounded-full p-2 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="save" class="flex-1 overflow-y-auto pr-2 pb-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Tipe Transaksi</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" wire:model="type" value="in" class="peer sr-only">
                            <div class="p-3 text-center rounded-lg border border-gray-200 dark:border-gray-600 peer-checked:border-emerald-500 dark:peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/30 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all font-medium text-gray-600 dark:text-gray-400 block w-full">
                                Pemasukan
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" wire:model="type" value="out" class="peer sr-only">
                            <div class="p-3 text-center rounded-lg border border-gray-200 dark:border-gray-600 peer-checked:border-rose-500 dark:peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/30 peer-checked:text-rose-700 dark:peer-checked:text-rose-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all font-medium text-gray-600 dark:text-gray-400 block w-full">
                                Pengeluaran
                            </div>
                        </label>
                    </div>
                    @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Jumlah (Rp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                        </div>
                        <input type="number" wire:model="amount" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 outline-none transition-all placeholder-gray-400 dark:placeholder-gray-500" placeholder="0">
                    </div>
                    @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Tanggal</label>
                    <input type="date" wire:model="date" class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 outline-none transition-all">
                    @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Deskripsi</label>
                    <textarea wire:model="description" rows="3" class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 outline-none transition-all resize-none placeholder-gray-400 dark:placeholder-gray-500" placeholder="Masukkan keterangan tambahan..."></textarea>
                    @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700 mt-4">
                    <button type="submit" class="w-full py-3 bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex justify-center items-center">
                        <span wire:loading.remove wire:target="save">Simpan Data</span>
                        <span wire:loading wire:target="save">Memproses...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete Confirmation (Slide from top/scale) -->
    <div x-show="deleteModal" style="display: none;">
        <!-- Backdrop -->
        <div x-show="deleteModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-gray-900 bg-opacity-75 backdrop-blur-sm" @click="deleteModal = false"></div>
        
        <!-- Dialog -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="deleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-xl max-w-sm w-full overflow-hidden">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-rose-100 dark:bg-rose-900/30 text-rose-500 dark:text-rose-400 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Hapus Transaksi</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Apakah Anda yakin ingin menghapus transaksi ini? Data yang dihapus tidak dapat dikembalikan.</p>
                    <div class="flex space-x-3">
                        <button @click="deleteModal = false" class="flex-1 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">
                            Batal
                        </button>
                        <button @click="$wire.delete(itemToDelete); deleteModal = false" class="flex-1 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-lg font-medium transition-colors shadow-sm focus:ring-4 focus:ring-rose-200 dark:focus:ring-rose-900">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script toast dan redirect --}}
<script>
    window.addEventListener('notify', event => {
        // Ambil index 0
        const payload = Array.isArray(event.detail) ? event.detail[0] : event.detail;
        const {
            type,
            message,
            redirect
        } = payload;

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toastEl) => {
                toastEl.addEventListener('mouseenter', Swal.stopTimer);
                toastEl.addEventListener('mouseleave', Swal.resumeTimer);
            },
            willClose: () => {
                if (redirect) {
                    Livewire.navigate(redirect);
                }
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    });
</script>
