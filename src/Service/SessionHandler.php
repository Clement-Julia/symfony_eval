<?php

namespace App\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionHandler
{

    private $doctrine;
    private $router;
    private $tokenStorage;

    /**
     * Méthode construct qui défini les propriétés
     * Nécessaire car sinon les propriétés sont mal lues
    */
    public function __construct(Registry $doctrine, Router $router, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Méthode de création de session
     * Initialement créé pour définir une valeur à une propriété dans la session,
     * Elle ne sert maintenant qu'à définir le panier dans la session
    */
    public function setSession(RequestEvent $responseEvent)
    {
        $paniers = $this->doctrine->getRepository(Panier::class)->findAll();
        if ($paniers) {
            $session = $responseEvent->getRequest()->getSession();
            if($this->tokenStorage->getToken()){
                $user = $this->tokenStorage->getToken()->getUser();
                $panier = $this->doctrine->getRepository(Panier::class)->findOneBy(["user" => $user->getId(), "etat" => false]);
    
                if ($panier != null) {
                    $session->set('panier', $panier);
                }
            }

        }
    }

    /**
     * Méthode de nettoyage de la session
     * Contrairement au logout la session n'est pas détruite
    */
    public function ClearSession(RequestEvent $responseEvent)
    {
        $session = $responseEvent->getRequest()->getSession();
        $session->clear();
    }
}
