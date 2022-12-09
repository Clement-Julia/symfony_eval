<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\ContenuPanier;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\ContenuPanierType;
use App\Repository\ContenuPanierRepository;
use App\Repository\PanierRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Annotation\Route;

#[Route('{_locale}/contenu/panier')]
class ContenuPanierController extends AbstractController
{
    #[Route('/', name: 'contenu_panier_index', methods: ['GET'])]
    public function index(ContenuPanierRepository $contenuPanierRepository): Response
    {
        return $this->render('contenu_panier/index.html.twig', [
            'contenu_paniers' => $contenuPanierRepository->findAll(),
        ]);
    }

    #[Route('/add/{id}', name: 'contenu_panier_add', methods: ['GET', 'POST'])]
    public function add(Produit $Produit, PanierRepository $panierRepository, ContenuPanierRepository $contenuPanierRepository): Response
    {
        $user = $this->getUser();

        $Panier = $panierRepository->findCurrentBasket($user->getId());

        if (!$Panier) {
            $Panier = new Panier();
            $Panier->setUser($this->getUser());
            $Panier->setDate(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $Panier->setEtat(false);

            $panierRepository->save($Panier, true);
        }else{
            $Panier = (object)reset($Panier);
        }

        $ContenuPanier = $contenuPanierRepository->findOneBy(["Panier" => $Panier->getId(), "Produit" => $Produit->getId()]);

        if(!$ContenuPanier){
            $ContenuPanier = new ContenuPanier();
            $ContenuPanier->setPanier($Panier);
            $ContenuPanier->setProduit($Produit);
            $ContenuPanier->setQuantite(1);
            $ContenuPanier->setDate(new DateTime('now', new DateTimeZone('Europe/Paris')));
        }else{
            $quantite = $ContenuPanier->getQuantite() + 1;
            $ContenuPanier->setQuantite($quantite);
        }
        $contenuPanierRepository->save($ContenuPanier, true);

        
        $this->addFlash('success', 'Produit ajouté au panier');
        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/view/{id}', name: 'contenu_panier_view', methods: ['GET'])]
    public function view(ContenuPanier $contenuPanier): Response
    {
        return $this->render('contenu_panier/view.html.twig', [
            'contenu_panier' => $contenuPanier,
        ]);
    }

    #[Route('/edit/{id}', name: 'contenu_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ContenuPanier $contenuPanier, ContenuPanierRepository $contenuPanierRepository): Response
    {
        $form = $this->createForm(ContenuPanierType::class, $contenuPanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contenuPanierRepository->save($contenuPanier, true);

            return $this->redirectToRoute('contenu_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contenu_panier/edit.html.twig', [
            'contenu_panier' => $contenuPanier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'contenu_panier_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, ContenuPanier $contenuPanier, ContenuPanierRepository $contenuPanierRepository): Response
    {
        if($contenuPanier->getQuantite() > 1){
            $quantite = $contenuPanier->getQuantite() - 1;
            $contenuPanier->setQuantite($quantite);
            $contenuPanierRepository->save($contenuPanier, true);
            
            $this->addFlash('success', 'Produit supprimé du panier');
        }else{
            if ($this->isCsrfTokenValid('delete' . $contenuPanier->getId(), $request->request->get('_token'))) {
                $contenuPanierRepository->remove($contenuPanier, true);
                $this->addFlash('success', 'Produit supprimé du panier');
            }
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
