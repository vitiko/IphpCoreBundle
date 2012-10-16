<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\CoreBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Todo: move to entityController trait
 */
class EntityController extends RubricAwareController
{


    protected $entityName;


    /**
     * @Template()
     */
    public function indexAction()
    {
        return array('entities' => $this->paginate($this->getIndexQuery()));
    }


    /**
     * Not Using Param converter cos can't use type hinting
     * @Template()
     */
    public function viewAction($id)
    {
        return array('entity' => $this->getEntityBySlugOrId($id));
    }


    protected function getEntityBySlugOrId($id)
    {

        $field = method_exists($this->getRepository()->getClassName(), 'getSlug') ? 'slug' : 'id';

        $entity = $this->getRepository()->findOneBy (array ($field => $id));

        if (!$entity) throw $this->createNotFoundException();

        return $entity;
    }


    protected function getIndexQuery()
    {
        return $this->getRepository()->createQueryBuilder('e')->getQuery();
    }


    protected function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }

    protected function getEntityName()
    {
        return $this->entityName;
    }


    /**
     * @return \Doctrine\Orm\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository($this->entityName);
    }

    /**
     * @param $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createQueryBuilder($alias)
    {
        return $this->getRepository()->createQueryBuilder($alias);
    }


    /**
     * Todo: to paginationController trait
     */
    protected function paginate($query, $itemPerPage = 15)
    {
        $paginator = $this->get('knp_paginator');
        return $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            $itemPerPage
        );
    }

}
