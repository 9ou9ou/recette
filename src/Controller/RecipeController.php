<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RecipeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @Route("/")
     */
    public function home(RecipeRepository $repo)
    {
        $repo=$repo->findAll();
        $nbr=count($repo);
        return $this->render('recipe/home.html.twig',[
             'recipes'=> $repo,
             'nombre'=> $nbr
        ]);    }

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
     * show a receipe in details.
     * @param Recipe $rec
     * @return void
     */
    public function show (Recipe $rec, Request $request , EntityManagerInterface $manager)
    {     
           // steps reformat
           $steps=explode('\r\n',$rec->getSteps());
           //ingredient handling 

           //comments handling
           $com = new Comments();
           $form = $this->createForm(CommentType::class, $com);
           $form->handleRequest($request);
          
           if ($form->isSubmitted() && $form->isValid()){
            
                $com->setCreatedAt(new \DateTime())
                    ->setRelation($rec);
                $manager->persist($com);
                $manager->flush();
                return $this->redirectToRoute('recipe_show',['id'=> $rec->getId()]);
           }
         

        return $this->render('recipe/show.html.twig',[
             'recipe'=> $rec,
             'nbPerson'=> $rec->getNbPerson(),
             'steps'=> $steps,
             'form'  => $form->createView()
                    ]);
    }

      /**
     * @Route("/rechercheNom", name="rechercheNom")
     */
    public function rechercheNom( RecipeRepository $repo,  Request $request){

        
        $selected_search = $request->request->get('search');
        $selected_value = $request->request->get('search_choice');
      //  dd($selected_search);
        if ($selected_value =='name'){
        $result = $repo->findByName($selected_search);
        }
        else if($selected_value =='origin'){
            $result = $repo->findByOrigine($selected_search);

        }
        else {
            $result= [];
        }
        return $this->render('recipe/list.html.twig',[
            'recipes'=> $result,
            'searched' => $selected_search
       ]);


   }

}
