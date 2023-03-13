<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ArtisteController extends AbstractController
{
    #[Route('/admin/artistes', name: 'admin_artistes', methods: ["GET"])]
    public function listeArtistes(ArtisteRepository $artisteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $artistes = $paginator->paginate(
            $artisteRepository->listeArtistesCompletePaginee(),
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );
        return $this->render('admin/artiste/listeArtistes.html.twig', [
            'lesArtistes' => $artistes
        ]);
    }

    #[Route('/admin/artistes/ajout', name: 'admin_artiste_ajout', methods: ["GET", "POST"])]
    public function ajoutArtistes(Request $request, EntityManagerInterface $manager): Response
    {
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($artiste);
            $manager->flush();
            $this->addFlash('success',"l'artiste a été ajouté");
            return $this->redirectToRoute('admin_artistes');
        }
        return $this->render('admin/artiste/formAjoutArtistes.html.twig', [
            'formArtiste' => $form->createView()
        ]);
    }
}
