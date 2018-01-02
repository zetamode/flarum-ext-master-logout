<?php

namespace ZetaMode\MasterLogout\Controllers;

use Flarum\Core\Exception\PermissionDeniedException;
use Flarum\Core\Guest;
use Flarum\Event\UserLoggedOut;
use Flarum\Http\Controller\ControllerInterface;
use Flarum\Http\Rememberer;
use Flarum\Http\SessionAuthenticator;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\TextResponse;
use ZetaMode\MasterLogout\LogoutUrlSigner;

class PixelLogoutController implements ControllerInterface
{
    protected $signer;
    protected $events;
    protected $authenticator;
    protected $rememberer;

    public function __construct(LogoutUrlSigner $signer, Dispatcher $events, SessionAuthenticator $authenticator, Rememberer $rememberer)
    {
        $this->signer = $signer;
        $this->events = $events;
        $this->authenticator = $authenticator;
        $this->rememberer = $rememberer;
    }

    public function handle(ServerRequestInterface $request)
    {
        $user = $request->getAttribute('actor');

        if ($user instanceof Guest) {
            return $this->success();
        }

        $username = Arr::get($request->getQueryParams(), 'username');

        if (!$this->signer->validate((string) $request->getUri()) || $user->username !== $username) {
            throw new PermissionDeniedException();
        }

        // from LogOutController
        $this->authenticator->logOut($request->getAttribute('session'));
        $user->accessTokens()->delete();
        $this->events->fire(new UserLoggedOut($user));

        return $this->rememberer->forget($this->success());
    }

    protected function success()
    {
        return new TextResponse(base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='), 200, [
            'Content-Type' => 'image/gif',
        ]);
    }
}
