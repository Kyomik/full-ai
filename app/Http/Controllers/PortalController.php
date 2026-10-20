<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\CulturalEvent;
use App\Models\GalleryItem;
use App\Repositories\Contracts\AnnouncementRepositoryInterface;
use App\Services\IndonesiaRegionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PortalController extends Controller
{
    protected $announcementRepo;

    public function __construct(AnnouncementRepositoryInterface $announcementRepo)
    {
        $this->announcementRepo = $announcementRepo;
    }

    public function home()
    {
        $announcements = $this->announcementRepo->latestPublished(3);

        return view('portal.home', [
            'events' => CulturalEvent::published()->with(['category', 'agency'])->orderBy('starts_on')->take(5)->get(),
            'categories' => Category::withCount(['events as published_events_count' => fn ($query) => $query->published()])->orderBy('name')->take(6)->get(),
            'announcements' => $announcements,
            'articles' => Article::whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }

    public function events(Request $request)
    {
        $events = CulturalEvent::published()
            ->with(['category', 'agency'])
            ->when($request->search, fn ($query, $term) => $query->where('title', 'like', "%$term%"))
            ->when($request->category, fn ($query, $id) => $query->where('category_id', $id))
            ->orderBy('starts_on')
            ->paginate(10)
            ->withQueryString();

        return view('portal.events', ['events' => $events, 'categories' => Category::orderBy('name')->get()]);
    }

    public function event(CulturalEvent $event)
    {
        abort_unless($event->status === 'approved', 404);

        return view('portal.event', ['event' => $event->load(['category', 'agency', 'documents'])]);
    }

    public function news(Request $request)
    {
        $published = Article::whereNotNull('published_at')
            ->where('published_at', '<=', now());

        $articles = (clone $published)
            ->when($request->search, fn ($query, $term) => $query->where(fn ($search) => $search->where('title', 'like', "%$term%")->orWhere('excerpt', 'like', "%$term%")))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('portal.news', [
            'articles' => $articles,
            'popular' => (clone $published)->latest('published_at')->take(5)->get(),
        ]);
    }

    public function article(Article $article)
    {
        abort_unless($article->published_at && $article->published_at->lte(now()), 404);

        $related = Article::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereKeyNot($article->getKey())
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('portal.article', compact('article', 'related'));
    }

    public function gallery(Request $request)
    {
        $items = GalleryItem::where('is_published', true)
            ->with('event.category')
            ->when($request->search, fn ($query, $term) => $query->where('title', 'like', "%$term%"))
            ->when($request->category, fn ($query, $id) => $query->whereHas('event', fn ($event) => $event->where('category_id', $id)))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $albums = CulturalEvent::published()
            ->with(['galleryItems' => fn ($query) => $query->where('is_published', true)->oldest()])
            ->withCount(['galleryItems as photos_count' => fn ($query) => $query->where('is_published', true)])
            ->having('photos_count', '>', 0)
            ->latest('starts_on')
            ->take(5)
            ->get();

        $categories = Category::withCount(['events as photos_count' => fn ($query) => $query->whereHas('galleryItems', fn ($items) => $items->where('is_published', true))])
            ->orderBy('name')
            ->get();

        return view('portal.gallery', compact('items', 'albums', 'categories'));
    }

    public function announcements()
    {
        $items = $this->announcementRepo->latestPublished(12);
        return view('portal.announcements', compact('items'));
    }

    public function calendar(Request $request)
    {
        $requestedMonth = (string) $request->query('month');
        $month = preg_match('/^\d{4}-\d{2}$/', $requestedMonth)
            ? Carbon::createFromFormat('Y-m', $requestedMonth)->startOfMonth()
            : now()->startOfMonth();

        $start = $month->copy()->startOfMonth()->startOfWeek();
        $end   = $month->copy()->endOfMonth()->endOfWeek();

        $events = CulturalEvent::published()
            ->with('category')
            ->whereDate('starts_on', '<=', $end)
            ->whereDate('ends_on', '>=', $start)
            ->orderBy('starts_on')
            ->get();

        $days = collect();

        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $days->push([
                'date' => $day->copy(),
                'events' => $events->filter(fn ($event) => $event->starts_on->copy()->startOfDay()->lte($day) && $event->ends_on->copy()->endOfDay()->gte($day))->values(),
            ]);
        }

        return view('portal.calendar', compact('events', 'days', 'month'));
    }

    public function contact()
    {
        return view('portal.contact');
    }

    public function regions(string $level, IndonesiaRegionService $regions, ?string $parent = null)
    {
        return response()->json($regions->get($level, $parent));
    }
}
