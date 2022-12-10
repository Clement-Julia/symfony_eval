<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('{_locale}/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/add', name: 'produit_add', methods: ['GET', 'POST'])]
    public function add(Request $request, ProduitRepository $produitRepository, TranslatorInterface $translator): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('photo')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', $translator->trans('produit.errorUpld'));
                    return $this->redirectToRoute('produit_index');
                }
                $produit->setPhoto($newFilename);
            }

            $produitRepository->save($produit, true);
            $this->addFlash('success', $translator->trans('produit.successAjout'));
            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/view/{id}', name: 'produit_view', methods: ['GET'])]
    public function view(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/edit/{id}', name: 'produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('photo')->getData();
            $oldFile = $produit->getPhoto();
           
            if (!empty($imageFile)) {

                if($oldFile && file_exists(__DIR__ . '/../../public/uploads/' . $oldFile)) {
                    unlink(__DIR__ . '/../../public/uploads/' . $oldFile);
                }

                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', $translator->trans('produit.errorUpld'));
                    return $this->redirectToRoute('produit_index');
                }
                $produit->setPhoto($newFilename);
            }
            $produitRepository->save($produit, true);
            $this->addFlash('success', $translator->trans('produit.successModif'));
            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
            $this->addFlash('success', $translator->trans('produit.successSuppr'));
        }

        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
