<?php
namespace App\Service ;

use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;

class IngredientService {
     protected $em;
    public function __construct(EntityManagerInterface $em)
    { 
      $this->em =$em;
      
    }
    public function list(){
    $repoIngred = $this->em->getRepository(Ingredient::class);
      return  $repoIngred->findAll();
    }
}