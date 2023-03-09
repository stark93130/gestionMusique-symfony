<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    #[Route('/albums', name: 'album',methods: ["GET"])]
    public function listeDesAlbums(AlbumRepository $albumRepository): Response
    {
        $listeAlbum=$albumRepository->findBy(
            [],
            ['nom'=>'ASC']
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
