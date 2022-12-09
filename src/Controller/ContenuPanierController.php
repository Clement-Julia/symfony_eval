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
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('{_locale}/contenu/panier')]
class ContenuPanierController extends AbstractController
{

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

        
        $this->addFlash('success', 'panier.ajout');
        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/edit/{status}/{id}', name: 'contenu_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ContenuPanier $contenuPanier, ContenuPanierRepository $contenuPanierRepository, TranslatorInterface $translator): Response
    {
        $routeParams = (object)$request->attributes->get('_route_params');
        $status = $routeParams->status;

        if($contenuPanier->getQuantite() > 1 || $status == "add"){
            if($status == "add"){
                $quantite = $contenuPanier->getQuantite() + 1;
            }elseif($status == "less"){
                $quantite = $contenuPanier->getQuantite() - 1;
            }
            $contenuPanier->setQuantite($quantite);
            $contenuPanierRepository->save($contenuPanier, true);
            
            $this->addFlash('success', $translator->trans('panier.edit_quantite'));
        }else{
            if($contenuPanier->getQuantite() <= 1 && $status == "less"){
                $contenuPanierRepository->remove($contenuPanier, true);
                $this->addFlash('success', $translator->trans('produit.supprimer'));
            }
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/delete/{id}', name: 'contenu_panier_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, ContenuPanier $contenuPanier, ContenuPanierRepository $contenuPanierRepository, TranslatorInterface $translator): Response
    {
        $contenuPanierRepository->remove($contenuPanier, true);
        $this->addFlash('success', $translator->trans('produit.supprimer'));

        return $this->redirect($request->headers->get('referer'));
    }
}
