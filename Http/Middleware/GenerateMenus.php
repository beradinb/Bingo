<?php

namespace Modules\Bingo\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {
            // Separator: Module Management

            // Bingo Dropdown
            $bingo_menu = $menu->add('<i class="nav-icon fas fa-highlighter"></i> Bingo Rooms', [
                'class' => 'nav-item nav-dropdown',
            ])
            ->data([
                'order'         => 92,
                'activematches' => [
                    'admin/bingo/rooms*',
                    'admin/bingo/categories*'
                ],
                'permission'    => ['view_bingos','edit_bingos'],
            ]);
            $bingo_menu->link->attr([
                'class' => 'nav-link nav-dropdown-toggle',
                'href' => '#'
            ]);

            // Submenu: Bingo Rooms
            $bingo_menu->add('<i class="nav-icon fas fa-person-booth"></i> Rooms', [
                'route' => 'backend.bingo.rooms.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 93,
                'activematches' => 'admin/bingo/rooms*',
                // 'permission'    => ['edit_posts'],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);
            // Submenu: Bingo Categories
            $bingo_menu->add('<i class="nav-icon fas fa-list"></i> Categories', [
                'route' => 'backend.bingo.categories.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 94,
                'activematches' => 'admin/bingo/categories*',
                // 'permission'    => ['edit_categories'],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);
           
            //Submenu: API UPDATE
            $bingo_menu->add('<i class="nav-icon fas fa-list"></i> Update from API', [
                'route' => 'backend.bingo.rooms.update_bingo_api',
                'class' => 'nav-item',
                'onclick'=> "return confirm('Are you sure?')"
            ])
            ->data([
                'order'         => 95,
                'activematches' => 'admin/bingo/update_bingo_api/*',
                // 'permission'    => ['edit_categories'],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

        })->sortby;
        return $next($request);
    }
}
