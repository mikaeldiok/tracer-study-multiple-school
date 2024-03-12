<?php

namespace Modules\Tracer\Http\Middleware;

use Closure;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {

            $menu->add('<i class="fas fa-file-alt c-sidebar-nav-icon"></i> '.trans('menu.tracer.records'), [
                'route' => 'backend.records.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 5,
                'activematches' => ['admin/records*'],
                'permission' => ['view_records'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        return $next($request);
    }
}
