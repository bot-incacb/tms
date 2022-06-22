<?php

namespace App\Http\Controllers;

use App\Enums\ErrCode;
use App\Exceptions\CException;
use App\Http\Requests\SyncAnchorRequest;
use App\Models\Anchor;
use App\Models\Entry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class OpenapiController extends Controller
{
    /**
     * 登录
     *
     * @param Request $request
     * @return array
     */
    public function login(Request $request): array
    {
        /** @var JWTAuth $auth */
        $auth = Auth::guard('openapi');

        $auth->factory()->setTTL(config('jwt.openapi_ttl'));

        if (!$token = $auth->attempt($request->only(['username', 'password']))) {
            throw new CException(ErrCode::UNAUTHORIZED, '授权失败，应用信息错误！');
        }

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expired_at' => now()->addSeconds($auth->factory()->getTTL() * 60)->timestamp,
        ];
    }

    public function getEntries(Request $request)
    {
        return Entry::select(['id', 'key'])
            ->with([
                'translates' => function (HasMany $builder) use ($request) {
                    $builder->select(['entry_id', 'lang', 'published_content'])
                        ->when($request->filled('langs'), function (Builder $builder) use ($request) {
                            $builder->whereIn('lang', explode(',', $request->input('langs')));
                        });
                },
            ])
            ->when($request->filled('tags'), function (Builder $builder) use ($request) {
                $builder->whereHas('tags', function (Builder $builder) use ($request) {
                    $builder->whereIn('content', explode(',', $request->input('tags')));
                });
            })
            ->oldest('id')
            ->page(200, function (Entry $entry) {
                $entry->makeHidden('id');
                $entry->translates->makeHidden('is_unpublished');
            });
    }

    public function syncAnchors(SyncAnchorRequest $request)
    {
        $anchorData = $request->all();

        $syncData = [];
        foreach ($anchorData as $anchorDatum) {
            $anchor = Anchor::updateOrCreate(['key' => $anchorDatum['key']], array_filter($anchorDatum));
            $syncData[$anchorDatum['entry_key']][] = $anchor->id;
        }

        Entry::whereIn('key', array_keys($syncData))
            ->get()
            ->map(function (Entry $entry) use ($syncData) {
                $entry->anchors()->syncWithoutDetaching($syncData[$entry->key]);
            });
    }
}
