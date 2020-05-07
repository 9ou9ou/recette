<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    
   
    /**
     * @Route("/home", name="home")
     * @Route("/")
     */
  
    public function home(RecipeRepository $repo)
    {    $repo=$repo->findAll();
        $nbr=count($repo);
        return $this->render('recipe/home.html.twig',[
             'recipes'=> $repo,
             'nombre'=> $nbr
        ]);
       
       
    }
    /**
     * @Route("/rechercheNom", name="rechercheNom")
     */
    public function rechercheNom(RecipeRepository $repo){
        $repo=$repo->findAll();
        $nom=$_POST['search'];
        return $this->render('recipe/search.html.twig',[
            'recipes'=> $repo,
            'nomRecette'=> $nom
       ]);


   }
     
    /**
     * @Route("/recipe", name="recipe")
     */
    public function ListReceipe(RecipeRepository $repo)
    {       $repo=$repo->findAll();

        return $this->render('recipe/list.html.twig',[
             'recipes'=> $repo
        ]);
    }
    /**
     * @Route("/recipe/{id}", name="recipe_show")
     */
    public function show (Recipe $rec)
    {      
           $steps=explode('\r\n',$rec->getSteps());
        return $this->render('recipe/show.html.twig',[
             'recipe'=> $rec,
             'nbPerson'=> $rec->getNbPerson(),
             'steps'=> $steps
        ]);
    }
    
    

}
