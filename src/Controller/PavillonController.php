<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Entity\Pavillon;
use App\Form\PavillonType;
use App\Repository\ChambreRepository;
use App\Repository\PavillonRepository;
use App\Repository\TypeChambreRepository;
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

class PavillonController extends AbstractController
{
    #[Route('/addpavillon', name: 'addpavillon')]
    #[Route('/editpavillon/{id}', name: 'editpavillon')]

    public function addPavillon(Pavillon $pavillon = null , Request $request , ManagerRegistry $manager , TypeChambreRepository $typeChambre): Response
    {   
        $data = $request->request;

        $entityManager = $manager->getManager();
        $allType = $typeChambre->findAll();

        if(!$pavillon){
            $pavillon = new Pavillon();  
        }
        
        $chambre = new Chambre();
       

        $form = $this->createForm(PavillonType::class , $pavillon); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
           
            $entityManager->persist($pavillon);
            $entityManager->flush();

            if ($data->get('typeChambre')!= null && $data->get('numEtage')!= null && $data->get('typeChambre') != null ) {

                $oneType = $typeChambre->find($data->get('typeChambre'));
                $chambre->setNumChambre($data->get('numChambre'))
                        ->setNumEtage($data->get('numEtage'))
                        ->setEtat("non-archivee")
                        ->setOccupation("non-occupee")
                        ->setPavillon($pavillon)
                        ->setTypeChambre($oneType);
    
                $entityManager->persist($chambre);
                $entityManager->flush();
            }

            return $this->redirectToRoute('showpavillon');
        }
        


        return $this->render('pavillon/addPavillon.html.twig', [
            'controller_name' => 'PavillonController',
            'form'            => $form->createView(),
            'typeChambre'     => $allType
        ]);
    }


    


    #[Route('/showpavillon/{page?1}/{nbre?7}', name: 'showpavillon')]
    public function showPavillon(PavillonRepository $pavillon , $page , $nbre): Response
    {       
        $pavillons = $pavillon->findBy([] , [], $nbre , ($page - 1) * $nbre );
        $nbPavillon =  $pavillon->count([]);
        $nbPage = ceil($nbPavillon / $nbre)  ;
 
        return $this->render('pavillon/showPavillon.html.twig', [
            'pavillons'     => $pavillons,
            'isPaginated'   => true,
            'nbPage'        => $nbPage,
            'page'          => $page,
            'nbre'          => $nbre
        ]);
    }



    #[Route('/showChambrePavillon/{id}', name: 'showChambrePavillon')]

    public function showChambre( Request $request ,  ChambreRepository $chambreRepo , PavillonRepository $pavillon): Response
    {
        $id = $request->attributes->get("id");
        $requet = $request->attributes->get("_route");

        $chambresPavillon = $chambreRepo->findBy(["pavillon" => $id]);

        return $this->render('chambre/showChambre.html.twig', [
           
            'chambresPavillon'        => $chambresPavillon,
            'requet'        => $requet,
            'isPaginated'   => true,


        ]);
    }



}




/* 

namespace App\Controller;

use App\Entity\Boursier;
use App\Entity\User;
use App\Entity\Etudiant;
use App\Repository\ChambreRepository;
use App\Repository\EtudiantRepository;
use App\Repository\TypeBourseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EtudiantController extends AbstractController
{
    #[Route('/getform', name: 'getform')]
    public function form( TypeBourseRepository $typeBourse , ChambreRepository $chambreRepo): Response
    {       
        $bourses = $typeBourse->findAll();
        $chambres = $chambreRepo->findAll();

        return $this->render('etudiant/addEtudiant.html.twig', [
            'controller_name' => 'EtudiantController',
            'bourses'         =>  $bourses,
            'chambres'         =>  $chambres
        ]);
    }



    #[Route('/addetudiant', name: 'addetudiant')]
    public function addEtudiant( Request $request , ManagerRegistry $manager , TypeBourseRepository $typeBourse , ChambreRepository $chambreRepo): Response
    {       
        $data = $request->request;
        $boursier = new Boursier();
        $user = new User();
        $etudiant = new Etudiant();
        $bourse = $typeBourse->find($data->get("typeBourse"));
        $chambre = $chambreRepo->find($data->get("chambre"));


        $entityManager = $manager->getManager();
        
                $etudiant
                        ->setNom($data->get("nom"))   
                        ->setPrenom($data->get("prenom"))  
                        ->setLogin($data->get("login"))    
                        ->setPassword("");
                $etudiant->setMatricule(uniqid())
                        ->setTelephone($data->get("telephone"))
                        ->setDateNaissance(new \DateTime());

            $user->setNom($data->get("nom"))   
                 ->setPrenom($data->get("prenom"))  
                 ->setLogin($data->get("login"))    
                 ->setPassword("")
                 ->addEtudiant($etudiant);
       
            
            
        //     $boursier->setBourse($bourse)
        //               ->setChambre($chambre)
        //               ->setEtudiant($etudiant)
        //               ->setNom($data->get("nom"))   
        //               ->setPrenom($data->get("prenom"))  
        //               ->setLogin($data->get("login"))    
        //               ->setPassword("");
        //    $boursier->setMatricule(uniqid())
        //                 ->setTelephone($data->get("telephone"))
        //                 ->setDateNaissance(new \DateTime())
        //                 ->setUsers($user);


                



            $entityManager->persist($user);
            $entityManager->persist($etudiant);
            // $entityManager->persist($boursier);
            $entityManager->flush();
            
            return $this->redirectToRoute('showetudiant');
        
    }




    #[Route('/showetudiant', name: 'showetudiant')]
    public function showEtudiant(EtudiantRepository $etudiant): Response
    {
        //$etudiants =  $etudiant->findAll();
        return $this->render('etudiant/showEtudiant.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }
}
 */