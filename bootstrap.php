<?php

namespace ZetaMode\MasterLogout;

use Flarum\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events, Application $app) {
    $events->subscribe(Listeners\AddPixelLogoutRoute::class);
    $events->subscribe(Listeners\AddRedirectOnLogout::class);

    $app->register(Providers\UrlSignerProvider::class);
};
