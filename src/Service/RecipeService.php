<?php
namespace App\Service ;

use App\Entity\Comments;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class RecipeService{
    protected $em;
    public function __construct(EntityManagerInterface $em)
    { 
      $this->em =$em;
      
    }
    public function  __init(){
        return $this->em->getRepository(Recipe::class);
    }

    public function listRecipe(){
        $repo=$this->__init()->findAll();
        return $repo;
    }
    public function countElement(){
        $repo=$this->__init();
        //dd($repo);
      $repo= $repo ->findAll();
        
        return count($repo);
    }

    public function completeComment(Recipe $rec, Comments $com){
        return  $com->setCreatedAt(new \DateTime())
                    ->setRelation($rec);
    }
}