<?php

namespace Iphp\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;


abstract class BaseEntityRepository extends EntityRepository
{


   protected function getDefaultQueryBuilder (\Doctrine\ORM\EntityManager $em)
   {
       return new BaseEntityQueryBuilder ($em);
   }




    /**
     * @param string $alias
     * @return \Iphp\ContentBundle\Entity\ContentQueryBuilder
     */
    public function createQueryBuilder($alias = '', \Closure $prepareQueryBuidler = null)
    {


        $qb = $this->getDefaultQueryBuilder($this->_em)
                ->setEntityName($this->_entityName)
                ->setCurrentAlias($alias)
                ->prepareDefaultQuery ();

        if ($prepareQueryBuidler) $prepareQueryBuidler($qb);



       // print $qb->getDql();
       // exit();
        return $qb;
    }


    /**
     * @param Closure $prepareQueryBuidler
     * @param string $alias
     * @return \Doctrine\ORM\Query
     */
    public function createQuery($alias = '', \Closure $prepareQueryBuidler = null)
    {
        return $this->createQueryBuilder($alias,$prepareQueryBuidler)->getQuery();
    }


}