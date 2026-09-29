<?php

namespace App\Helpers;

use App\Models\Finance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class FinanceCacheHelper
{
    protected static $versionKey = 'cache_finance_version';
    protected static $ttl = 600; // 10 menit

    /**
     * Ambil versi cache saat ini (untuk invalidasi).
     */
    public static function getVersion(): int
    {
        return (int) Cache::get(self::$versionKey, 0);
    }

    /**
     * Bump versi → semua cache lama otomatis stale.
     */
    public static function invalidate(): void
    {
        $current = (int) Cache::get(self::$versionKey, 0);
        Cache::put(self::$versionKey, $current + 1, 60 * 60 * 24 * 7);
    }

    /**
     * Buat cache key yang sudah di-versioning.
     */
    public static function key(string $suffix): string
    {
        $v = self::getVersion();
        return "finance_v{$v}_{$suffix}";
    }

    /**
     * Ambil summary keuangan bulan berjalan dari cache.
     * Return: ['in' => total_pemasukan, 'out' => total_pengeluaran, 'balance' => selisih]
     */
    public static function monthlySummary(?string $yearMonth = null): array
    {
        $ym = $yearMonth ?? Carbon::now()->format('Y-m');
        $cacheKey = self::key("monthly_{$ym}");

        return Cache::remember($cacheKey, self::$ttl, function () use ($ym) {
            $start = Carbon::parse($ym . '-01')->startOfMonth();
            $end   = $start->copy()->endOfMonth();

            // Single query dengan GROUP BY type — tidak ada N+1
            $rows = Finance::query()
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->selectRaw('type, SUM(amount) as total')
                ->groupBy('type')
                ->pluck('total', 'type');

            $in  = (float) ($rows->get('in')  ?? 0);
            $out = (float) ($rows->get('out') ?? 0);

            return [
                'in'      => $in,
                'out'     => $out,
                'balance' => $in - $out,
            ];
        });
    }
}
