<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('{_locale}/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
    }

    #[Route('/add', name: 'panier_add', methods: ['GET', 'POST'])]
    public function add(PanierRepository $panierRepository): Response
    {
        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/view/{id}', name: 'panier_view', methods: ['GET'])]
    public function view(Panier $panier): Response
    {
        $produitsPanier = $panier->getContenuPaniers();

        return $this->render('panier/view.html.twig', [
            'panier' => $panier,
            'produitsPanier' => $produitsPanier
        ]);
    }

    #[Route('/edit/{id}', name: 'panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, PanierRepository $panierRepository): Response
    {
        $panier->setEtat(true);
        $panierRepository->save($panier, true);

        $this->addFlash('success', 'Votre commande a bien été finalisée');
        return $this->redirect($request->headers->get('referer'));

    }

    #[Route('/delete/{id}', name: 'panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, PanierRepository $panierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $panierRepository->remove($panier, true);
        }

        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
