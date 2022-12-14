<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('{_locale}/panier')]
class PanierController extends AbstractController
{
    #[Route('/view/{id}', name: 'panier_view', methods: ['GET'])]
    public function view(Panier $panier): Response
    {
        $produitsPanier = $panier->getContenuPaniers();

        return $this->render('panier/view.html.twig', [
            'panier' => $panier,
            'produitsPanier' => $produitsPanier
        ]);
    }

    #[Route('/list', name: 'panier_list', methods: ['GET'])]
    public function list(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/list.html.twig', [
            'paniers' => $panierRepository->findAllCurrentsBaskets(),
        ]);
    }

    #[Route('/edit/{id}', name: 'panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, PanierRepository $panierRepository, TranslatorInterface $translator): Response
    {
        // On valide le panier en le faisant passer à un etat true
        $panier->setEtat(true);
        $panierRepository->save($panier, true);

        $this->addFlash('success', $translator->trans('panier.complete'));
        return $this->redirect($request->headers->get('referer'));

    }
}
