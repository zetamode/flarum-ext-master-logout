<?php

namespace ZetaMode\MasterLogout\Providers;

use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Settings\SettingsRepositoryInterface;
use ZetaMode\MasterLogout\LogoutUrlSigner;

class UrlSignerProvider extends AbstractServiceProvider
{
    public function register()
    {
        /**
         * @var $settings SettingsRepositoryInterface
         */
        $settings = $this->app->make(SettingsRepositoryInterface::class);

        $this->app->singleton(LogoutUrlSigner::class, function () use ($settings) {
            return new LogoutUrlSigner(
                $settings->get('zetamode.master-logout.key')
            );
        });
    }
}
