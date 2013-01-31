<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Todo: move to trait
 */
class RubricAwareController extends Controller
{

    function getRubricsRepository()
    {
        return $this->getDoctrine()->getRepository('ApplicationIphpCoreBundle:Rubric');
    }


    protected function getRubricManager()
    {
        return $this->container->get('iphp.core.rubric.manager');
    }

    /**
     * @return \Application\Iphp\CoreBundle\Entity\Rubric;
     */
    protected function getCurrentRubric()
    {
        return $this->getRubricManager()->getCurrent();
    }


    function getPaginator()
    {
        return $this->get('knp_paginator');
    }

    function paginate($query, $itemPerPage = 15)
    {
        return $this->getPaginator()->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            $itemPerPage/*limit per page*/,

            array('distinct' => false)
        );
    }


}
