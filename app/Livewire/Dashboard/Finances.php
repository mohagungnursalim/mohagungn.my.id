<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Finance;
use App\Helpers\PostsCacheHelper;
use App\Helpers\FinanceCacheHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

#[Title('Manajemen Keuangan')]
class Finances extends Component
{
    // Load More Optimization
    public $perPage = 10;

    public function loadMore()
    {
        $this->perPage += 10;
    }

    // --- Form fields ---
    public $type = 'in';
    public $amount;
    public $description;
    public $date;
    public $financeId;

    // --- Filter ---
    public $search = '';
    public $filterMonth; // format: Y-m, default bulan berjalan

    public function mount()
    {
        $this->date        = now()->format('Y-m-d');
        $this->filterMonth = now()->format('Y-m');
    }

    // Reset pagination saat filter berubah
    public function updatedSearch(): void    { $this->perPage = 10; }
    public function updatedFilterMonth(): void { $this->perPage = 10; }

    // ----------------------------------------------------------------
    // FORM HELPERS
    // ----------------------------------------------------------------

    public function resetFields(): void
    {
        $this->financeId   = null;
        $this->type        = 'in';
        $this->amount      = '';
        $this->description = '';
        $this->date        = now()->format('Y-m-d');
    }

    public function save(): void
    {
        $this->financeId ? $this->update() : $this->store();
    }

    // ----------------------------------------------------------------
    // CRUD
    // ----------------------------------------------------------------

    public function store(): void
    {
        $this->validate();

        Finance::create([
            'type'        => $this->type,
            'amount'      => $this->amount,
            'description' => $this->description,
            'date'        => $this->date,
        ]);

        FinanceCacheHelper::invalidate();
        PostsCacheHelper::refreshAll();   // dashboard card juga ikut di-refresh

        $this->resetFields();
        $this->dispatch('close-modal');
        

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Added Successfully'
        ]);
    }

    public function update(): void
    {
        $this->validate();

        if ($this->financeId) {
            Finance::findOrFail($this->financeId)->update([
                'type'        => $this->type,
                'amount'      => $this->amount,
                'description' => $this->description,
                'date'        => $this->date,
            ]);

            FinanceCacheHelper::invalidate();
            PostsCacheHelper::refreshAll();

            $this->resetFields();
            $this->dispatch('close-modal');
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Updated Successfully'
            ]);
        }
    }

    public function delete(int $id): void
    {
        Finance::findOrFail($id)->delete();

        FinanceCacheHelper::invalidate();
        PostsCacheHelper::refreshAll();

        
         $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Deleted Successfully'
        ]);
    }

    // ----------------------------------------------------------------
    // RENDER
    // ----------------------------------------------------------------

    protected array $rules = [
        'type'        => 'required|in:in,out',
        'amount'      => 'required|numeric|min:0',
        'description' => 'nullable|string|max:255',
        'date'        => 'required|date',
    ];

    public function render()
    {
        // Tentukan range bulan yang dipilih
        $start = Carbon::parse($this->filterMonth . '-01')->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        // Query dengan filter bulan — index date+type dipakai di sini
        $query = Finance::query()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()]);

        if ($this->search) {
            $query->where('description', 'like', '%' . $this->search . '%');
        }

        $rawFinances = $query->orderBy('date', 'desc')
                             ->orderBy('id', 'desc')
                             ->take($this->perPage + 1)
                             ->get();
        
        $hasMore = $rawFinances->count() > $this->perPage;
        $finances = $rawFinances->take($this->perPage);

        // Summary bulan berjalan dari cache (single query, tidak ada N+1)
        $summary = FinanceCacheHelper::monthlySummary($this->filterMonth);

        return view('livewire.dashboard.finances', [
            'finances' => $finances,
            'hasMore'  => $hasMore,
            'summary'  => $summary,
            'filterMonth' => $this->filterMonth,
        ])->layout('layouts.dashboard.main');
    }
}


