<?php

namespace App\Providers;

use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResponseFactory::macro('api', function ($data  , $message = 'success', $error=0, $status = 200) {


            $format = [
                'status' => $status,
                'error' => $error,
                'message' => $message,
                'data' => $data,
            ];
            return response()->json($format, $status);
        });

        ResponseFactory::macro('apiError', function ($message = null, $error=1, $status = 200) {
            $format = [
                'status' => $status,
                'error' => $error,
                'message' => $message,
            ];
            return response()->json($format, $status);
        });
        ResponseFactory::macro('apiSuccess', function ($message = null, $error=0, $status = 200) {
            $format = [
                'status' => $status,
                'error' => $error,
                'message' => $message,
            ];
            return response()->json($format, $status);
        });



        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page'): LengthAwarePaginator {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,

                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
