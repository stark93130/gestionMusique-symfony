<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class AlbumController extends AbstractController
{
    #[Route('/albums', name: 'album',methods: ["GET"])]
    public function listeDesAlbums(AlbumRepository $albumRepository,PaginatorInterface $paginator,Request $request): Response
    {

        $listeAlbum = $paginator->paginate(
            $albumRepository->listeAlbumsComplete(),
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );
        return $this->render('album/listeAlbums.html.twig', [
            'lesAlbums'=>$listeAlbum,
        ]);
    }
    #[Route('/album/{id}', name: 'FicheAlbum',methods: ["GET"])]
    public function ficheAlbum(Album $album): Response
    {

        return $this->render('album/FicheAlbums.html.twig', [
            'leAlbum'=>$album,
        ]);
    }
}
