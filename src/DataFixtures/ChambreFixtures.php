<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Chambre;
use App\Entity\Boursier;
use App\Entity\Etudiant;
use App\Entity\Pavillon;
use App\Entity\NonBoursier;
use App\Entity\TypeBourse;
use App\Entity\TypeChambre;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ChambreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=0; $i < 2 ; $i++) { 

            $typeChambre = new TypeChambre();
            $typeChambre->setType("double");

            $bourse = new TypeBourse();
            $bourse->setType("bourse_entiere");

            $manager->persist($typeChambre);
            $manager->persist($bourse);


        }
        for ($i=1; $i < 51 ; $i++) { 
            $pavillon = new Pavillon;
            $pavillon->setNumPavillon("A".$i)
                    ->setNbreEtage(4);
            $manager->persist($pavillon);
       
            $chambre = new Chambre();
            $chambre->setNumChambre($i)
                    ->setNumEtage(1)
                    ->setOccupation("non-occupee")
                    ->setEtat("non-archivee")
                    ->setPavillon($pavillon)
                    ->setTypeChambre($typeChambre);
                    $manager->persist($chambre);
       

        $matricule = substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(3/strlen($x)) )),1,3);    

        $boursier = new Boursier();
        $nonboursier= new NonBoursier();
        $etudiant = new Etudiant();
        $user = new User();

        $boursier
                ->setPassword("")
                ->setEtudiant($etudiant)
                ->setNom("nom".$i)   
                ->setPrenom("prenom".$i)  
                ->setLogin("email.$i.@gmail.com")
                ->setMatricule($boursier->getNom().$matricule)
                ->setTelephone(772314554)
                ->setDateNaissance(new \DateTime())
                ->setBourse($bourse);
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
                ->setUser($user)
                ->addNonBoursier($nonboursier)
                ->addBoursier($boursier);
                
                
                
                $nonboursier
                ->setNom($boursier->getNom())   
                ->setPrenom($boursier->getPrenom())  
                ->setLogin($boursier->getLogin())    
                ->setPassword("")
                ->setMatricule($boursier->getNom().$matricule)
                ->setTelephone($boursier->getTelephone())
                ->setDateNaissance($boursier->getDateNaissance())
                ->setAdresse("Dakar")
                ->setEtudiant($etudiant);
            
                        
                $manager->persist($nonboursier);
                $manager->persist($boursier);
                $manager->persist($user);
                $manager->persist($etudiant);


            }



















        $manager->flush();



    }
}
