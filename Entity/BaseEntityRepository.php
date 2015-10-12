<?php

namespace Iphp\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

abstract class BaseEntityRepository extends EntityRepository
{
    protected function getDefaultQueryBuilder(\Doctrine\ORM\EntityManager $em)
    {
        return new BaseEntityQueryBuilder ($em);
    }

    /**
     * @param string $alias
     * @param string $indexBy The index for the from.
     * @param \Closure $prepareQueryBuilder
     *
     * @return \Iphp\CoreBundle\Entity\BaseEntityQueryBuilder
     */
    public function createQueryBuilder($alias = '', $indexBy = null, \Closure $prepareQueryBuilder = null)
    {
        $qb = $this->getDefaultQueryBuilder($this->_em)
            ->setEntityName($this->_entityName)
            ->setCurrentAlias($alias)
            ->prepareDefaultQuery();

        if ($prepareQueryBuilder) $prepareQueryBuilder($qb);


        // print $qb->getDql();
        // exit();
        return $qb;
    }

    /**
     * @param \Closure $prepareQueryBuidler
     * @param string $alias
     * @return \Doctrine\ORM\Query
     */
    public function createQuery($alias = '', \Closure $prepareQueryBuidler = null)
    {
        return $this->createQueryBuilder($alias, null, $prepareQueryBuidler)->getQuery();
    }

    public function countRows($alias = '', \Closure $prepareQueryBuidler = null, $distinct = false)
    {
        $qb = $this->createQueryBuilder($alias, null, $prepareQueryBuidler);

 

        return $this->countQueryRows($qb, $distinct );
    }

    public function countQueryRows(BaseEntityQueryBuilder $qb, $distinct = false)
    {
        $res =   $qb->getCountClone($distinct)->getQuery()->getOneOrNullResult();
        return $res['rownum'];
    }
}
