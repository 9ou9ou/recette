<?php

namespace App\Repository;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByName($value)
    {    
        return $this->createQueryBuilder('r')
        ->andWhere('r.name like :val')
        ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            
        ;
    }
    public function findByOrgine($value)
    {    
        return $this->createQueryBuilder('r')
        ->andWhere('r.origine like :val')
        ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()           
        ;
    }

    public function findByIngredientRecipes($value)
    {
        return $this->createQueryBuilder('r')
            ->select('i.name, u.quantity')
            ->join('App\Entity\IngredientRecipe','u')
            ->join('App\Entity\Ingredient','i')
            ->where('r.name = :value')
            ->setParameter('value',$value->getName())
            ->andWhere('r.id = u.recipe')
            ->andWhere('u.ingredient = i.id')
            ->getQuery()
            ->getResult()
        ;
    }
}
