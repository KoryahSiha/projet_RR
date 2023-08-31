<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // récupère l'objet utilisateur associé au token d'authentification de l'utilisateur connecté
        $user = $token->getUser();
        // récupère la liste des rôles attribués à l'utilisateur
        $roles = $user->getRoles();
        
        if (in_array('ROLE_ADMIN', $roles)) {
            // si l'utilisateur est un admin, une fois la connexion validée, renvoie vers le dashboard
            return new RedirectResponse($this->urlGenerator->generate
            ('admin'));
        }  elseif (in_array('ROLE_GESTIONNAIREDOM', $roles)) {
            // si l'utilisateur est gestionnaire de domaine, une fois la connexion validée, renvoie vers la page des salles
            return new RedirectResponse($this->urlGenerator->generate
            ('app_salle_index'));
        } elseif (in_array('ROLE_GESTIONNAIRESAL', $roles)) {
            // si l'utilisateur est gestionnaire de salle, une fois la connexion validée, renvoie vers la page des réservations
            return new RedirectResponse($this->urlGenerator->generate
            ('app_reservation_index'));
        } else {
            // les autres utilisateurs, une fois la connexion validée, renvoie vers la page de profil
            return new RedirectResponse($this->urlGenerator->generate
            ('app_user_show', [
                'id' => $user->getId(),
            ]));
        }

        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
