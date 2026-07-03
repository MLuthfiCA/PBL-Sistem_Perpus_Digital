<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HolidayHelper
{
    /**
     * Fetch Indonesian holidays for a given year (cached for 30 days).
     */
    public static function getIndonesianHolidays($year)
    {
        return Cache::remember("indo_holidays_{$year}", 86400 * 30, function () use ($year) {
            try {
                // Fetch national holidays and collective leaves from api-hari-libur.vercel.app
                $response = Http::timeout(5)->get("https://api-hari-libur.vercel.app/api?year={$year}");
                if ($response->successful()) {
                    $body = $response->json();
                    if (isset($body['data']) && is_array($body['data'])) {
                        return collect($body['data'])->pluck('date')->toArray();
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Failed to fetch holidays for {$year}: " . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Calculate return date with automatic holiday & Sunday extension.
     */
    public static function calculateReturnDate($tanggalPinjam)
    {
        $date = $tanggalPinjam;
        
        $year = date('Y', strtotime($date));
        $holidays = self::getIndonesianHolidays($year);
        $nextYear = $year + 1;
        $nextHolidays = self::getIndonesianHolidays($nextYear);
        $allHolidays = array_merge($holidays, $nextHolidays);
        
        $extended = false;
        $originalDate = date('Y-m-d', strtotime($tanggalPinjam . ' + 5 days'));
        $addedDays = 0;
        
        while ($addedDays < 5) {
            $date = date('Y-m-d', strtotime($date . ' + 1 day'));
            $dayOfWeek = date('N', strtotime($date)); // 1 (Mon) - 7 (Sun)
            $isWeekend = ($dayOfWeek >= 6);
            $isHoliday = in_array($date, $allHolidays);
            
            if (!$isWeekend && !$isHoliday) {
                $addedDays++;
            } else {
                $extended = true;
            }
        }
        
        return [
            'return_date' => $date,
            'extended' => $extended,
            'original_date' => $originalDate
        ];
    }
}
