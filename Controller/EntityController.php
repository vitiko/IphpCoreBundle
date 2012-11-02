<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\CoreBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Todo: move to entityController trait
 */
class EntityController extends RubricAwareController
{


    protected $entityName;


    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $searchForm = $this->getSearchForm($request);
        return array(
            'searchForm' => $searchForm ? $searchForm->createView() : null,
            'entities' => $this->paginate($this->getIndexQuery($searchForm))
        );
    }


    /**
     * Not Using Param converter cos can't use type hinting
     * @Template()
     */
    public function viewAction($id)
    {
        $entity = $this->getEntityBySlugOrId($id);

        if (!$this->viewCheckStatus($entity)) throw  $this->createNotFoundException();

        return array(

            'title' => $entity ? (string)$entity : null,
            'entity' => $entity);
    }




    protected function viewCheckStatus ($entity)
    {
        return $entity ? true : false;
    }


    protected function getSearchForm(Request $request)
    {
        return null;
    }


    protected function getEntityBySlugOrId($id)
    {
        $field = method_exists($this->getRepository()->getClassName(), 'getSlug') ? 'slug' : 'id';
        $entity = $this->getRepository()->findOneBy(array($field => $id));

        if (!$entity) throw $this->createNotFoundException();
        return $entity;
    }


    protected function getIndexQuery($searchForm = null)
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
