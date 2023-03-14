<?php

namespace App\Controller\Admin;

use App\Entity\Style;
use App\Form\StyleType;
use App\Repository\StyleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StyleController extends AbstractController
{
    #[Route('/admin/styles', name: 'admin_styles',methods: ["GET"])]
    public function index(StyleRepository $repository): Response
    {
        $styles=$repository->listeStyleComplete();
        return $this->render('admin/style/listeStyles.html.twig', [
            'lesStyles' => $styles,
        ]);
    }
    #[Route('/admin/styles/ajout', name: 'admin_style_ajout', methods: ["GET", "POST"])]
    #[Route('/admin/style/modif/{id}', name: 'admin_style_modif', methods: ["GET", "POST"])]
    public function ajoutModifStyles(Style $style = null, Request $request, EntityManagerInterface $manager): Response
    {
        $mode = null;
        if ($style == null) {
            $style = new Style();
            $mode = "ajouté";
        } else {
            $mode = "modifié";
        }

        $form = $this->createForm(StyleType::class, $style);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($style);
            $manager->flush();
            $this->addFlash('success', "le style a été $mode");
            return $this->redirectToRoute('admin_styles');
        }
        return $this->render('admin/style/formAjoutModifStyles.html.twig', [
            'formStyle' => $form->createView()
        ]);
    }

    #[Route('/admin/style/suppression/{id}', name: 'admin_style_suppression', methods: ["GET"])]
    public function suppressionStyle(Style $style, EntityManagerInterface $manager): Response
    {
        $nbAlbums=$style->getAlbums()->count();

        $messageAlerte= $nbAlbums>1 ? "L'artiste ne peux pas être supprimé 
            car $nbAlbums albums lui sont associés":"L'artiste ne peux pas être supprimé 
            car $nbAlbums album lui est associé";

        if($nbAlbums>0){
            $this->addFlash('danger', $messageAlerte);
        }else{
            $manager->remove($style);
            $manager->flush();
            $this->addFlash('success', "le style a été supprimé");
        }

        return $this->redirectToRoute('admin_styles');

    }
}
