<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Boursier;
use App\Entity\Etudiant;
use App\Entity\NonBoursier;

use App\Form\EBoursierType;
use App\Form\NonBoursierType;
use App\Repository\ChambreRepository;
use App\Repository\BoursierRepository;

use App\Repository\TypeBourseRepository;
use App\Repository\NonBoursierRepository;
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

class EtudiantController extends AbstractController
{
    #[Route('/addetudiant', name: 'addetudiant')]
    public function addEtudiant(Request $request , ManagerRegistry $manager): Response
    {   
        $matricule = substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(3/strlen($x)) )),1,3);    
        $boursier = new Boursier();
        $nonboursier = new NonBoursier();
        $user = new User();
        $etudiant = new Etudiant();

        $entityManager = $manager->getManager();
        
        
        $form = $this->createForm(EBoursierType::class , $boursier); 

        $formNb = $this->createForm(NonBoursierType::class , $nonboursier); 

        $form->handleRequest($request);

        $formNb->handleRequest($request);

        
        
        if($form->isSubmitted() && $form->isValid()){

            $adresse = $formNb->get('adresse')->getData();
            $boursecheck = $form->get('bourse')->getData();

            if($request->request->get("typeEtu") == "select"){
                $error = "Veuillez choisir le type d'étudiant";
                return $this->render('etudiant/addEtudiant.html.twig', [
                    'form'            =>  $form->createView(),
                    'formNb'            =>  $formNb->createView(),
                    'error'             => $error
                ]);
            }elseif($request->request->get("typeEtu") == "boursierNL" && $boursecheck == null){
                $errorbourse = "Veuillez choisir le type de bourse";
                return $this->render('etudiant/addEtudiant.html.twig', [
                    'form'            =>  $form->createView(),
                    'formNb'            =>  $formNb->createView(),
                    'errorbourse'             => $errorbourse
                ]);
            }elseif($request->request->get("typeEtu") == "nonBoursier"  && $adresse == ""){
                $erroradresse = "Veuillez ajouter l'adresse ";
                return $this->render('etudiant/addEtudiant.html.twig', [
                    'form'            =>  $form->createView(),
                    'formNb'            =>  $formNb->createView(),
                    'erroradresse'             => $erroradresse
                ]);
            }
            

            $boursier
                ->setMatricule($boursier->getNom().$matricule)
                ->setPassword("")
                ->setEtudiant($etudiant);
            
            $user
                ->setNom($boursier->getNom())   
                ->setPrenom($boursier->getPrenom())  
                ->setEmail($boursier->getLogin())    
                ->setPassword("")
                ->addEtudiant($etudiant);


            $etudiant
                ->setNom($boursier->getNom())   
                ->setPrenom($boursier->getPrenom())  
                ->setLogin($boursier->getLogin())    
                ->setPassword("")
                ->setMatricule($boursier->getMatricule())
                ->setTelephone($boursier->getTelephone())
                ->setDateNaissance($boursier->getDateNaissance())
                ->setUser($user);
                if ($adresse != "") {
                    $etudiant->addNonBoursier($nonboursier);
                }else{
                    $etudiant->addBoursier($boursier);
                }
                
                
            if ($adresse != "") {
                $nonboursier
                ->setNom($boursier->getNom())   
                ->setPrenom($boursier->getPrenom())  
                ->setLogin($boursier->getLogin())    
                ->setPassword("")
                ->setMatricule($boursier->getNom().$matricule)
                ->setTelephone($boursier->getTelephone())
                ->setDateNaissance($boursier->getDateNaissance())
                ->setAdresse($adresse)
                ->setEtudiant($etudiant);
            }
                        

            if ($adresse != "") {
                $entityManager->persist($nonboursier);
            }elseif ($adresse == ""){
                $entityManager->persist($boursier);
            }

            //dd($boursier);
            $entityManager->persist($user);
            $entityManager->persist($etudiant);

            $entityManager->flush();
            
            return $this->redirectToRoute('showetudiant');
        }

        return $this->render('etudiant/addEtudiant.html.twig', [
            'controller_name' => 'EtudiantController',
            'form'            =>  $form->createView(),
            'formNb'            =>  $formNb->createView(),

        ]);
    }

    #[Route('/showetudiant/{page?1}/{nbre?4}', name: 'showetudiant')]
    public function showEtudiant($page = null, $nbre , Request $request , BoursierRepository $etudiantb , NonBoursierRepository $etudiantnb ,TypeBourseRepository $bourse , ChambreRepository $chambre): Response
    {
        $chambres   =   $chambre->findAll();
        $bourses    =   $bourse-> findAll();

        $etudiants  =  $etudiantb->findBy([] , [], $nbre , ($page - 1) * $nbre );
        $etudiantNbs = $etudiantnb->findBy([] , [], $nbre , ($page - 1) * $nbre );
        
        $nbEtudiants =  $etudiantb->count([]);
        $nbEtudiantNbs =  $etudiantnb->count([]);
        $total_records = $nbEtudiants + $nbEtudiantNbs;
        $records = $total_records / $nbre;
        $nbPage = ceil($records / 2)  ;


        if ($request->getMethod() == "POST"){
            if( $request->request->get("chambre") == ""  &&  $request->request->get("bourse") != "" ) {
                $etudiants  =  $etudiantb->findBy(['bourse' => $request->request->get("bourse")] , [], $nbre , ($page - 1) * $nbre ) ;
                $etudiantNbs = "";
            }elseif ($request->request->get("chambre") != ""  && $request->request->get("bourse") == "" ) {
                $etudiants  =  $etudiantb->findBy(['chambre' => $request->request->get("chambre")]  );
                $etudiantNbs = "";

            }
        }
       
       
        return $this->render('etudiant/showEtudiant.html.twig', [
            'controller_name' => 'EtudiantController',
            'etudiants'       => $etudiants,
            'etudiantNbs'     => $etudiantNbs,
            'chambres'        => $chambres,
            'bourses'         => $bourses,
            'isPaginated'   => true,
            'nbPage'        => $nbPage,
            'page'          => $page,
            'nbre'          => $nbre
        ]);
    }



   





}
