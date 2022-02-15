<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;


class GithubOauthController extends AbstractController
{
    /**
     * @Route("/connect/github", name="app_connect_github")
     */
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        $client = $clientRegistry->getClient('github');
        return $client->redirect(['read:user', 'user:email']);
    }
}
