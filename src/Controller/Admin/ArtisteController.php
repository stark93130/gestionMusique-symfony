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
    #[Route('/admin/artistes/modif/{id}', name: 'admin_artiste_modif', methods: ["GET", "POST"])]
    public function ajoutModifArtistes(Artiste $artiste = null, Request $request, EntityManagerInterface $manager): Response
    {
        $mode = null;
        if ($artiste == null) {
            $artiste = new Artiste();
            $mode = "ajouté";
        } else {
            $mode = "modifié";
        }

        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($artiste);
            $manager->flush();
            $this->addFlash('success', "l'artiste a été $mode");
            return $this->redirectToRoute('admin_artistes');
        }
        return $this->render('admin/artiste/formAjoutModifArtistes.html.twig', [
            'formArtiste' => $form->createView()
        ]);
    }

    #[Route('/admin/artistes/suppression/{id}', name: 'admin_artiste_suppression', methods: ["GET"])]
    public function suppressionArtistes(Artiste $artiste, EntityManagerInterface $manager): Response
    {
        $nbAlbums=$artiste->getAlbums()->count();
        $messageAlerte= $nbAlbums>1 ? "L'artiste ne peux pas être supprimé 
            car $nbAlbums albums lui sont associés":"L'artiste ne peux pas être supprimé 
            car $nbAlbums album lui est associé";

        if($nbAlbums>0){
            $this->addFlash('danger', $messageAlerte);
        }else{
            $manager->remove($artiste);
            $manager->flush();
            $this->addFlash('success', "l'artiste a été supprimé");
        }

        return $this->redirectToRoute('admin_artistes');

    }

}
