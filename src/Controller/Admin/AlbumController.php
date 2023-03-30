<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Artiste;
use App\Form\AlbumType;
use App\Form\ArtisteType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    #[Route('/admin/albums', name: 'admin_album',methods: ["GET"])]
    public function listeDesAlbums(AlbumRepository $albumRepository,PaginatorInterface $paginator,Request $request): Response
    {

        $listeAlbum = $paginator->paginate(
            $albumRepository->listeAlbumsComplete(),
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );
        return $this->render('admin/album/listeAlbums.html.twig', [
            'lesAlbums'=>$listeAlbum,
        ]);
    }
    #[Route('/admin/album/ajout', name: 'admin_album_ajout', methods: ["GET", "POST"])]
    #[Route('/admin/album/modif/{id}', name: 'admin_album_modif', methods: ["GET", "POST"])]
    public function ajoutModifAlbum(Album $album = null, Request $request, EntityManagerInterface $manager): Response
    {
        $mode = null;
        if ($album == null) {
            $album = new Album();
            $mode = "ajouté";
        } else {
            $mode = "modifié";
        }

        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère l'image sélectionné
            $fichierImage=$form->get('imageFile')->getData();
            if($fichierImage!=null){
                //On supprime l'ancien fichier si le fichier n'est pas la pochette par défaut
                if($album->getImage()!="pochettevierge.png"){
                    unlink($this->getParameter('imagesAlbumDestinations').$album->getImage());
                }

                // On crée le nom du nouveau fichier
                $fichier=md5(uniqid()).".".$fichierImage->guessExtension();
                //On déplace le fichier chargé dans le dossier public
                $fichierImage->move($this->getParameter('imagesAlbumDestinations'),$fichier);
                //on enregistre le nom du nouveau fichier en bd
                $album->setImage($fichier);

            }
            $manager->persist($album);
            $manager->flush();
            $this->addFlash('success', "l'album a été $mode");
            return $this->redirectToRoute('admin_album');
        }
        return $this->render('admin/album/formAjoutModifAlbum.html.twig', [
            'formAlbum' => $form->createView()
        ]);
    }
    #[Route('/admin/album/suppression/{id}', name: 'admin_album_suppression', methods: ["GET"])]
    public function suppressionAlbum(Album $album, EntityManagerInterface $manager): Response
    {
        $nbMorceaux=$album->getMorceaux()->count();
        $messageAlerte= $nbMorceaux>1 ? "L'album ne peux pas être supprimé 
            car $nbMorceaux morceaux lui sont associés":"L'album ne peux pas être supprimé 
            car $nbMorceaux morceau lui est associé";

        if($nbMorceaux>0){
            $this->addFlash('danger', $messageAlerte);
        }else{
            $manager->remove($album);
            $manager->flush();
            $this->addFlash('success', "l'album a été supprimé");
        }

        return $this->redirectToRoute('admin_album');

    }
}
