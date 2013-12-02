<?php

namespace Iphp\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


abstract class BaseEntityRepository extends EntityRepository
{


    protected function getDefaultQueryBuilder(\Doctrine\ORM\EntityManager $em)
    {
        return new BaseEntityQueryBuilder ($em);
    }


    /**
     * @param string $alias
     * @return \Iphp\CoreBundle\Entity\BaseEntityQueryBuilder
     */
    public function createQueryBuilder($alias = '', \Closure $prepareQueryBuidler = null)
    {


        $qb = $this->getDefaultQueryBuilder($this->_em)
            ->setEntityName($this->_entityName)
            ->setCurrentAlias($alias)
            ->prepareDefaultQuery();

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
        return $this->createQueryBuilder($alias, $prepareQueryBuidler)->getQuery();
    }


    public function countRows($alias = '', \Closure $prepareQueryBuidler = null, $distinct = false)
    {
        $qb = $this->createQueryBuilder($alias, $prepareQueryBuidler);

 

        return $this->countQueryRows($qb, $distinct );
    }


    public function countQueryRows(BaseEntityQueryBuilder $qb, $distinct = false)
    {
        $qbCount = clone $qb;
        $qbCount->resetDQLPart('orderBy');


        $res = $qbCount->select('COUNT('.($distinct ? 'DISTINCT ' : ''). $qbCount->getCurrentAlias() . '.id) as rownum')
            ->getQuery()->getOneOrNullResult();

        return $res['rownum'];
    }

}