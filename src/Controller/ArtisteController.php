<?php

namespace App\Controller;
use App\Entity\Artiste;
use App\Repository\ArtisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtisteController extends AbstractController
{
    #[Route('/artistes', name: 'artistes',methods: ["GET"])]
    public function listeArtistes(ArtisteRepository $artisteRepository): Response
    {
        $listeArtistes=$artisteRepository->listeArtistesComplete();
        return $this->render('artiste/listeArtistes.html.twig',[
            'lesArtistes'=>$listeArtistes
        ]);
    }
    #[Route('/artiste/{id}', name: 'ficheArtiste',methods: ["GET"])]
    public function ficheArtistes(Artiste $artiste): Response
    {

        return $this->render('artiste/ficheArtiste.html.twig',[
            'leArtiste'=>$artiste
        ]);
    }
}
