<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Pour tester la page erreur
        // return $this->render('bundles/TwigBundle/Exception/error.html.twig', []);
        
        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
