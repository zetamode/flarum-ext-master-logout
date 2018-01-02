<?php

namespace ZetaMode\MasterLogout\Listeners;

use Flarum\Event\ConfigureForumRoutes;
use Illuminate\Contracts\Events\Dispatcher;
use ZetaMode\MasterLogout\Controllers\PixelLogoutController;

class AddPixelLogoutRoute
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureForumRoutes::class, [$this, 'routes']);
    }

    public function routes(ConfigureForumRoutes $routes)
    {
        $routes->get(
            '/pixel-logout/{username:[0-9A-Za-z_-]+}',
            'zetamode.master-logout.pixel',
            PixelLogoutController::class
        );
    }
}
