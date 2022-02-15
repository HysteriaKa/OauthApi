<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GithubAuthenticator extends SocialAuthenticator

{

    private $router;
    public function __construct(RouterInterface $router, ClientRegistry $clientRegistry)
    {

        $this->router = $router;
        $this->clientRegistry = $clientRegistry;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {

        return new RedirectResponse($this->router->generate('app_connect_github'));
    }

    public function supports(Request $request)
    {
        //verify if we are in good url
        return 'oauth_check' === $request->attributes->get('route') && $request->get('service') === 'github';
        //if true trigger process for this url

    }
    public function getCredentials(Request $request)
    {
        // recupere infos a envoyer au getUser
        //on recupere le code reçu et on le converti en accessToken
        return $this->fetchAccessToken($this->clientRegistry->getClient('github'));
    }
    public function getUser($credientials, UserProviderInterface $userProvider)
    {
        dd($credientials);
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('app_homepage');

        return new RedirectResponse($targetUrl);
    
        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}
