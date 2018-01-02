<?php

namespace ZetaMode\MasterLogout\Listeners;

use Flarum\Event\ConfigureMiddleware;
use Illuminate\Contracts\Events\Dispatcher;
use ZetaMode\MasterLogout\Middlewares\RedirectLogoutToMaster;

class AddRedirectOnLogout
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureMiddleware::class, [$this, 'routes']);
    }

    public function routes(ConfigureMiddleware $middleware)
    {
        $middleware->pipe->pipe('/logout', app(RedirectLogoutToMaster::class));
    }
}
