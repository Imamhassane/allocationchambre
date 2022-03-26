<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use App\Repository\PavillonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/")
 *
 * @IsGranted("ROLE_USER")
 * 
 */

class ChambreController extends AbstractController
{

    #[Route('/addchambre', name: 'addchambre')]
    #[Route('editchambre/{id}', name: 'editchambre')]
    public function addChambre(Chambre $chambre = null,  Request $request , ManagerRegistry $manager): Response
    {
         
        $entityManager = $manager->getManager();

        if(!$chambre){
            $chambre = new Chambre(); 
        }
        $form = $this->createForm(ChambreType::class , $chambre); 
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $chambre->setEtat("non-archivee");
            $chambre->setOccupation("non-occupee");

            $entityManager->persist($chambre);
            $entityManager->flush();
            
            return $this->redirectToRoute('showchambre');
        }

        return $this->render('chambre/addChambre.html.twig', [
            'controller_name' => 'ChambreController',
            'form'            =>  $form->createView(),
            'editMode'        =>  $chambre->getId() !== null 
        ]);
    }

    #[Route('/showchambre/{page?1}/{nbre?7}', name: 'showchambre')]
    #[Route('/showChambrePavillon/{id}', name: 'showChambrePavillon')]

    public function showChambre($page , $nbre , Request $request ,  ChambreRepository $chambreRepo , PavillonRepository $pavillon): Response
    {
        $requet = $request->attributes->get("_route");
        $id = $request->attributes->get("id");

        $pavillons = $pavillon->findAll();

        $chambres = $chambreRepo->findBy(["etat" => "non-archivee"] , [], $nbre , ($page - 1) * $nbre );
        $nbChambre =  $chambreRepo->count([]);
        $nbPage = ceil($nbChambre / $nbre)  ;


        if ($request->getMethod() == "POST"){
            if( $request->request->get("pavillon") != "" ) {
                $chambres  =  $chambreRepo->findBy(['pavillon' => $request->request->get("pavillon")]);
            }
        }
        return $this->render('chambre/showChambre.html.twig', [
            'chambres'      =>  $chambres,
            'pavillons'     => $pavillons,
            'requet'        => $requet,
            'isPaginated'   => true,
            'nbPage'        => $nbPage,
            'page'          => $page,
            'nbre'          => $nbre

        ]);
    }


   




    #[Route('/archiver/{id}', name: 'archiver')]
    public function archiver(Request $request , ManagerRegistry $manager , Chambre $chambre): Response
    {
        $entityManager = $manager->getManager();

        $chambre->setEtat("archivee");

        $entityManager->persist($chambre);
        $entityManager->flush();

        return $this->redirectToRoute("showchambre");
    }


}
