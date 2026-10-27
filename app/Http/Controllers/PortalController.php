<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\CulturalEvent;
use App\Models\GalleryItem;
use App\Repositories\Contracts\PublicAnnouncementRepositoryInterface;
use App\Services\IndonesiaRegionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PortalController extends Controller
{
    protected PublicAnnouncementRepositoryInterface $announcementRepo;

    public function __construct(PublicAnnouncementRepositoryInterface $announcementRepo)
    {
        $this->announcementRepo = $announcementRepo;
    }

    public function home()
    {
        $announcements = $this->announcementRepo->latestPublished(3);

        return view('portal.home', [
            'events' => CulturalEvent::published()->with(['category', 'agency'])->orderBy('starts_on')->take(5)->get(),
            'categories' => Category::where('status', 'active')->withCount(['events as published_events_count' => fn ($query) => $query->published()])->orderBy('name')->take(6)->get(),
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

        return view('portal.events', ['events' => $events, 'categories' => Category::where('status', 'active')->orderBy('name')->get()]);
    }

    public function event(CulturalEvent $event)
    {
        $event->load([
            'category',
            'agency',
            'documents',
            'galleryItems' => fn ($query) => $query->where('is_published', true)->latest(),
        ]);

        abort_unless(
            $event->status === 'approved'
                && $event->category?->status === 'active'
                && $event->agency?->status === 'active',
            404,
        );

        return view('portal.event', ['event' => $event]);
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
            'popular' => (clone $published)
                ->orderByDesc('view_count')
                ->orderByDesc('published_at')
                ->take(5)
                ->get(),
        ]);
    }

    public function article(Article $article)
    {
        abort_unless($article->published_at && $article->published_at->lte(now()), 404);
        $article->increment('view_count');

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
        $items = $this->publishedGalleryItems($request)
            ->latest('created_at')
            ->take(20)
            ->get();

        $albums = $this->publishedAlbums()
            ->take(5)
            ->get();
        $this->attachAlbumPreviewItems($albums);

        $popularAlbums = $this->publishedAlbums()
            ->reorder()
            ->orderByDesc('gallery_views_count')
            ->orderByDesc('latest_gallery_item_at')
            ->take(5)
            ->get();

        $categories = $this->galleryCategories();

        return view('portal.gallery', compact('items', 'albums', 'popularAlbums', 'categories'));
    }

    public function galleryItems(Request $request)
    {
        $items = $this->publishedGalleryItems($request)
            ->latest('created_at')
            ->paginate(24)
            ->withQueryString();
        $categories = $this->galleryCategories();

        return view('portal.gallery-items', compact('items', 'categories'));
    }

    public function albums()
    {
        $albums = $this->publishedAlbums()->paginate(15);
        $this->attachAlbumPreviewItems($albums);

        return view('portal.gallery-albums', compact('albums'));
    }

    private function attachAlbumPreviewItems(iterable $albums): void
    {
        foreach ($albums as $album) {
            $previewItems = $album->galleryItems()
                ->where('is_published', true)
                ->latest('created_at')
                ->latest('id')
                ->take(3)
                ->get();

            $album->setRelation('previewGalleryItems', $previewItems);
        }
    }

    public function album(CulturalEvent $event)
    {
        abort_unless(
            CulturalEvent::published()
                ->whereKey($event->getKey())
                ->whereHas('galleryItems', fn ($query) => $query->where('is_published', true))
                ->exists(),
            404,
        );

        $event->increment('gallery_views_count');
        $event->load('category');
        $items = GalleryItem::where('cultural_event_id', $event->getKey())
            ->where('is_published', true)
            ->latest('created_at')
            ->paginate(24);

        return view('portal.gallery-album', compact('event', 'items'));
    }

    private function publishedAlbums()
    {
        return CulturalEvent::published()
            ->whereHas('galleryItems', fn ($query) => $query->where('is_published', true))
            ->with('latestPublishedGalleryItem')
            ->withCount(['galleryItems as media_count' => fn ($query) => $query->where('is_published', true)])
            ->withMax(['galleryItems as latest_gallery_item_at' => fn ($query) => $query->where('is_published', true)], 'created_at')
            ->orderByDesc('latest_gallery_item_at');
    }

    private function publishedGalleryItems(Request $request)
    {
        return GalleryItem::where('is_published', true)
            ->whereHas('event', fn ($event) => $event->published())
            ->with('event.category')
            ->when($request->search, fn ($query, $term) => $query->where('title', 'like', "%$term%"))
            ->when($request->category, fn ($query, $id) => $query->whereHas('event', fn ($event) => $event->where('category_id', $id)));
    }

    private function galleryCategories()
    {
        return Category::where('status', 'active')
            ->withCount(['events as photos_count' => fn ($query) => $query->whereHas('galleryItems', fn ($items) => $items->where('is_published', true))])
            ->orderBy('name')
            ->get();
    }

    public function announcements()
    {
        $items = $this->announcementRepo->paginatePublished(10);
        $categories = Category::where('status', 'active')
            ->withCount(['events as published_events_count' => fn ($query) => $query->published()])
            ->orderBy('name')
            ->get();

        return view('portal.announcements', compact('items', 'categories'));
    }

    public function calendar(Request $request)
    {
        $view = $request->query('view');
        $view = in_array($view, ['month', 'week', 'day'], true) ? $view : 'month';

        $requestedDate = (string) $request->query('date');
        $selectedDate = preg_match('/^\d{4}-\d{2}-\d{2}$/', $requestedDate)
            ? Carbon::createFromFormat('Y-m-d', $requestedDate)->startOfDay()
            : now()->startOfDay();

        $requestedMonth = (string) $request->query('month');
        $month = preg_match('/^\d{4}-\d{2}$/', $requestedMonth)
            ? Carbon::createFromFormat('Y-m', $requestedMonth)->startOfMonth()
            : $selectedDate->copy()->startOfMonth();

        if ($view === 'month' && ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $requestedDate) && preg_match('/^\d{4}-\d{2}$/', $requestedMonth)) {
            $selectedDate = $month->format('Y-m') === now()->format('Y-m')
                ? now()->startOfDay()
                : $month->copy();
        }

        [$start, $end] = match ($view) {
            'week' => [$selectedDate->copy()->startOfWeek(), $selectedDate->copy()->endOfWeek()],
            'day' => [$selectedDate->copy()->startOfDay(), $selectedDate->copy()->endOfDay()],
            default => [$month->copy()->startOfMonth()->startOfWeek(), $month->copy()->endOfMonth()->endOfWeek()],
        };

        $previousPeriod = match ($view) {
            'week' => $selectedDate->copy()->subWeek(),
            'day' => $selectedDate->copy()->subDay(),
            default => $month->copy()->subMonth(),
        };
        $nextPeriod = match ($view) {
            'week' => $selectedDate->copy()->addWeek(),
            'day' => $selectedDate->copy()->addDay(),
            default => $month->copy()->addMonth(),
        };

        $events = CulturalEvent::published()
            ->with('category')
            ->whereDate('starts_on', '<=', $end)
            ->whereDate('ends_on', '>=', $start)
            ->orderBy('starts_on')
            ->orderBy('starts_at')
            ->orderBy('id')
            ->get();

        $days = collect();

        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $days->push([
                'date' => $day->copy(),
                'events' => $events->filter(fn ($event) => $event->starts_on->copy()->startOfDay()->lte($day) && $event->ends_on->copy()->endOfDay()->gte($day))->values(),
            ]);
        }

        return view('portal.calendar', compact('events', 'days', 'month', 'view', 'selectedDate', 'previousPeriod', 'nextPeriod'));
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
