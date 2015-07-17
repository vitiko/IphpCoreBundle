<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Todo: move to trait
 */
class RubricAwareController extends Controller
{

    /**
     * @return \Iphp\CoreBundle\Entity\BaseRubricRepository
     */
    function getRubricsRepository()
    {
        return $this->getDoctrine()->getRepository('ApplicationIphpCoreBundle:Rubric');
    }


    /**
     * @return \Iphp\CoreBundle\Manager\RubricManager
     */
    protected function getRubricManager()
    {
        return $this->container->get('iphp.core.rubric.manager');
    }

    /**
     * @return \Application\Iphp\CoreBundle\Entity\Rubric;
     */
    protected function getCurrentRubric(Request $request  = null)
    {
        return $this->getRubricManager()->getRubricFromRequest($request);
    }


    function getPaginator()
    {
        return $this->get('knp_paginator');
    }

    /**
     * @param $query
     * @param int $itemPerPage
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    function paginate($query, $itemPerPage = 15)
    {
        return $this->getPaginator()->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            $itemPerPage/*limit per page*/,

            array('distinct' => true)
        );
    }


}
