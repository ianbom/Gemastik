<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Mission;
use App\Models\Quiz;
use App\Models\Report;
use Illuminate\Support\Str;

class ChatbotDataService
{
    /**
     * Mencari laporan berdasarkan berbagai kriteria.
     */
    public function searchReports(
        ?string $keyword = null,
        ?string $status = null,
        ?string $category = null,
        ?string $provinceName = null,
        ?string $cityName = null
    ): string {
        $query = Report::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($category) {
            $query->where('category', 'like', "%{$category}%");
        }
        if ($provinceName) {
            $query->whereHas('province', function ($q) use ($provinceName) {
                $q->where('name', 'like', "%{$provinceName}%");
            });
        }

        if ($cityName) {
            $query->whereHas('city', function ($q) use ($cityName) {
                $q->where('name', 'like', "%{$cityName}%");
            });
        }

        $count = $query->count();
        if ($count === 0) {
            return "Maaf, saya tidak menemukan laporan yang cocok dengan kriteria tersebut.";
        }

        $reports = $query->latest()->take(5)->get(['title', 'status']);
        $reportTitles = $reports->pluck('title')->implode("\n- ");

        $responseText = "Saya menemukan {$count} laporan yang cocok. Berikut adalah 5 yang terbaru:\n- {$reportTitles}";
        if ($count > 5) {
            $responseText .= "\n... dan " . ($count - 5) . " lainnya.";
        }
        return $responseText;
    }

    /**
     * Mencari misi berdasarkan berbagai kriteria.
     */
    public function searchMissions(
        ?string $keyword = null,
        ?string $status = 'open',
        ?string $provinceName = null,
        ?string $cityName = null
    ): string {
        $query = Mission::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($provinceName) {
            $query->whereHas('province', function ($q) use ($provinceName) {
                $q->where('name', 'like', "%{$provinceName}%");
            });
        }
        if ($cityName) {
            $query->whereHas('city', function ($q) use ($cityName) {
                $q->where('name', 'like', "%{$cityName}%");
            });
        }

        $count = $query->count();
        if ($count === 0) {
            return "Maaf, tidak ada misi yang cocok dengan kriteria yang Anda cari.";
        }

        $missions = $query->latest('scheduled_date')->take(5)->get(['title', 'status', 'scheduled_date']);
        $missionList = $missions->map(fn($m) => "{$m->title} (Status: {$m->status}, Dijadwalkan: {$m->scheduled_date->format('d M Y')})")
                                 ->implode("\n- ");

        $responseText = "Tentu! Ditemukan {$count} misi. Berikut 5 di antaranya:\n- {$missionList}";
        if ($count > 5) {
            $responseText .= "\n... dan " . ($count - 5) . " lainnya.";
        }
        return $responseText;
    }

    /**
     * Mencari konten atau artikel berdasarkan kata kunci atau tipe.
     */
    public function searchContents(?string $keyword = null, ?string $type = null): string
    {
        $query = Content::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('body', 'like', "%{$keyword}%");
            });
        }
        if ($type) {
            $query->where('content_type', $type);
        }

        $count = $query->count();
        if ($count === 0) {
            return "Saya tidak dapat menemukan konten yang Anda cari.";
        }

        $contents = $query->latest()->take(3)->get(['title']);
        $contentTitles = $contents->pluck('title')->implode("\n- ");
        return "Saya menemukan {$count} konten yang relevan. Ini 3 yang paling baru:\n- {$contentTitles}";
    }


}
