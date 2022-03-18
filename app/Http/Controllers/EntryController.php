<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntrySaveRequest;
use App\Models\Entry;
use App\Models\Tag;
use App\Services\EntryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function index(Request $request)
    {
        return Entry::with('currentTranslate', 'tags')
            ->when($request->filled('tags'), function (Builder $builder) use ($request) {
                $builder->whereHas('tags', function (Builder $builder) use ($request) {
                    $builder->whereIn('content', explode(',', $request->input('tags')));
                });
            })
            ->when($request->filled('key'), function (Builder $builder) use ($request) {
                $value = '%' . $request->input('key') . '%';
                $builder->where('key', 'like', $value);
            })
            ->page();
    }

    public function show(Entry $entry)
    {
        $entry->translates;
        $entry->tags;
        return $entry;
    }

    public function store(EntrySaveRequest $request, EntryService $service)
    {
        $service->store(
            $request->input('key'),
            $request->input('lang'),
            $request->input('content'),
            $request->input('tags'),
        );
    }

    public function update(EntrySaveRequest $request, Entry $entry, EntryService $service)
    {
        $service->update(
            $entry,
            $request->input('key'),
        );
    }

    public function destroy(Entry $entry)
    {
        $entry->delete();
        $entry->translates()->delete();
        $entry->tags()->sync(null);
    }

    public function storeTags(Entry $entry, Request $request, EntryService $service)
    {
        $service->storeTags($entry, $request->input('tags'));
    }

    public function destroyTags(Entry $entry, Tag $tag)
    {
        $entry->tags()->detach($tag);
    }

    /**
     * 发布
     *
     * @param Entry $entry
     * @param EntryService $service
     * @return void
     */
    public function publish(Entry $entry, EntryService $service)
    {
        $service->publish($entry);
    }

    /**
     * 撤销
     *
     * @param Entry $entry
     * @param EntryService $service
     * @return void
     */
    public function revoke(Entry $entry, EntryService $service)
    {
        $service->revoke($entry);
    }

    /**
     * 发布历史
     *
     * @param Entry $entry
     * @return array
     */
    public function histories(Entry $entry)
    {
        return ['items' => $entry->histories];
    }
}
