<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\News;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            ['label' => 'Total News', 'value' => News::count(), 'icon' => 'bi-newspaper', 'color' => 'navy', 'route' => 'admin.news.index'],
            ['label' => 'Published News', 'value' => News::published()->count(), 'icon' => 'bi-broadcast', 'color' => 'success', 'route' => 'admin.news.index'],
            ['label' => 'Gallery Images', 'value' => GalleryImage::count(), 'icon' => 'bi-images', 'color' => 'gold', 'route' => 'admin.gallery.index'],
            ['label' => 'Resources', 'value' => Resource::count(), 'icon' => 'bi-folder2-open', 'color' => 'info', 'route' => 'admin.resources.index'],
            ['label' => 'FAQs', 'value' => Faq::count(), 'icon' => 'bi-question-circle', 'color' => 'navy', 'route' => 'admin.faqs.index'],
            ['label' => 'Contact Enquiries', 'value' => ContactEnquiry::count(), 'icon' => 'bi-envelope', 'color' => 'red', 'route' => 'admin.enquiries.index'],
            ['label' => 'Users', 'value' => User::count(), 'icon' => 'bi-people', 'color' => 'secondary', 'route' => 'admin.users.index'],
        ];

        $unread = ContactEnquiry::where('status', 'unread')->count();

        $recentEnquiries = ContactEnquiry::latest()->take(5)->get();
        $recentNews = News::with('author')->latest('updated_at')->take(5)->get();
        $topResources = Resource::orderByDesc('download_count')->take(5)->get(['id', 'title', 'slug', 'download_count', 'file_type']);

        return view('admin.dashboard', [
            'stats' => $stats,
            'unread' => $unread,
            'recentEnquiries' => $recentEnquiries,
            'recentNews' => $recentNews,
            'topResources' => $topResources,
            'enquiryChart' => $this->enquiriesPerMonth(),
        ]);
    }

    /** Enquiries received in each of the last six months, for the dashboard chart. */
    private function enquiriesPerMonth(): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths(5);

        $counts = ContactEnquiry::query()
            ->where('created_at', '>=', $start)
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"), DB::raw('COUNT(*) as total'))
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $labels = [];
        $data = [];
        for ($i = 0; $i < 6; $i++) {
            $month = $start->copy()->addMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = (int) ($counts[$month->format('Y-m')] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
