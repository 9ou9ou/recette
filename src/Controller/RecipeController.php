<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\RecipeService;

class RecipeController extends AbstractController
{


    /**
     * @Route("/home", name="home")
     * @Route("/")
     * @param RecipeService $service
     * @return Response
     */
    public function home(RecipeService $service)
    {   
        $nbr= $service->countElement();
        $repo = $service ->listRecipe();
        return $this->render('recipe/home.html.twig',[
             'recipes'=> $repo,
             'nombre'=> $nbr
        ]);    }

    /**
     * @Route("/recipe", name="recipe")
     * @param RecipeService $service
     * @return Response
     */
    public function ListReceipe(RecipeService $service)
    {       $repo=$service->listRecipe();

        return $this->render('recipe/list.html.twig',[
             'recipes'=> $repo
        ]);
    }

    /**
     * @Route("/recipe/{id}", name="recipe_show")
     * show a recipe in details.
     * @param Recipe $rec
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param RecipeRepository $repo
     * @param RecipeService $service
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function show (Recipe $rec, Request $request , EntityManagerInterface $manager, RecipeRepository $repo,RecipeService $service )
    {     
        
           // steps reformat
           $steps=explode('\r\n',$rec->getSteps());
           //ingredient handling 
           $ingredients = $repo->findByIngredientRecipes($rec);

           //handling Calories
           $calories = $repo->CountCalories($rec)[0][1];


           //comments handling
           $com= new Comments();
           if ($this->getUser()){
              $com->setAuthor($this->getUser()->getUsername());
           }
           
           $form = $this->createForm(CommentType::class);
           $form->handleRequest($request);

           if ($form->isSubmitted() && $form->isValid()){
               $com->setCreatedAt(new \DateTime())
                   ->setRelation($rec);
                 dd($com);
                $manager->persist($com);
                $manager->flush();
                return $this->redirectToRoute('recipe_show',['id'=> $rec->getId()]);
           }

        return $this->render('recipe/show.html.twig',[
             'recipe'=> $rec,
             'nbPerson'=> $rec->getNbPerson(),
             'steps'=> $steps,
             'form'  => $form->createView(),
             'ingredients' => $ingredients,
             'calories'=> $calories,
                    ]);
    }

    /**
     * @Route("/rechercheNom", name="rechercheNom")
     * @param RecipeRepository $repoRECIPE
     * @param Request $request
     * @param IngredientRepository $repoING
     * @return Response
     */
    public function rechercheNom( RecipeRepository $repoRECIPE,  Request $request, IngredientRepository $repoING){

        $selected_search = $request->request->get('search');
        $selected_value = $request->request->get('search_choice');
       
        if ($selected_value =='name'){
        $result = $repoRECIPE->findByName($selected_search);
        }
        elseif($selected_value =='origin'){
            $result = $repoRECIPE->findByOrigine($selected_search);
        }
        elseif($selected_value =='ingredient'){
            $result = $repoING->findByName($selected_search);
            
        }
        else {
            $result= [];
        }
        return $this->render('recipe/list.html.twig',[
            'recipes'=> $result,
            'searched' => $selected_search
       ]);


   }
   /**
    * @Route("/search", name="search")
    */
    public function search(){
     return   $this->render('recipe/search.html.twig');
    }

}
