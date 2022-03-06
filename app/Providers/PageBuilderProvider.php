<?php

namespace App\Providers;

use App\Models\Paginator as MyPaginator;
use Closure;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class PageBuilderProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('page', function ($perPage = null, Closure $callback = null) {
            /** @var Builder $this */
            $pageName = 'page';
            $currentPage = Paginator::resolveCurrentPage($pageName);

            if ($perPage instanceof Closure) {
                $callback = $perPage;
                $perPage = null;
            }

            if (!$perPage) {
                $perPage = (int)request('per_page', 10);
            }

            $items = $this->forPage($currentPage, $perPage)->get();

            $options = [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ];

            /** @var MyPaginator $paginator */
            $paginator = Container::getInstance()
                ->makeWith(MyPaginator::class, compact(
                    'items', 'perPage', 'currentPage', 'options'
                ));

            if ($callback) {
                $paginator->map($callback);
            }

            return $paginator;
        });
    }
}
