<?php

namespace ZetaMode\MasterLogout\Middlewares;

use Flarum\Core\Guest;
use Flarum\Settings\SettingsRepositoryInterface;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use ZetaMode\MasterLogout\LogoutUrlSigner;

class RedirectLogoutToMaster implements ServerMiddlewareInterface
{
    protected $settings;
    protected $signer;

    public function __construct(SettingsRepositoryInterface $settings, LogoutUrlSigner $signer)
    {
        $this->settings = $settings;
        $this->signer = $signer;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $user = $request->getAttribute('actor');

        if ($request->getUri()->getPath() !== '/logout' || $user instanceof Guest) {
            return $delegate->process($request);
        }

        $url = $this->signer->sign($this->settings->get('zetamode.master-logout.url') . '/logout/' . $user->username, 5);

        return $delegate->process($request->withQueryParams(array_merge(
            $request->getQueryParams(),
            [
                'return' => $url,
            ]
        )));
    }
}
