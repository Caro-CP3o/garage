<?php

namespace App\Controller;

// use App\Entity\User;
// use Symfony\Component\Form\FormError;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\Security\Http\Attribute\IsGranted;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;

class AccoutController extends AbstractController
{
    /**
     * Permet à l'utilisateur de se connecter
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    #[Route('/login', name: 'account_login')]
    public function index(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        $loginError = null;
        if ($error instanceof TooManyLoginAttemptsAuthenticationException) {
            // L'erreur est due à la limitation de la connexion (login throttling)
            $loginError = "Trop de tentatives de connexion. Réessayez plus tard";
        }


        return $this->render('account/index.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
            'loginError' => $loginError
        ]);
    }

    /**
     * Permet de se déconnecter, géré automatiquement par symfony
     *
     * @return void
     */
    #[Route('/logout', name: 'account_logout')]
    public function logout(): void
    {
    }
}
