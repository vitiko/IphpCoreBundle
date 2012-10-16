<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends RubricController
{
    /**
     * Вызывается в шаблоне с помощью render
     * @param string $template
     * @param string $rubric
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function MenuAction($template = '', $rubric = '')
    {

       // $key = 'menuCache' . $this->get('kernel')->getEnvironment();

        // print $key;
        /*     if (apc_exists($key)) {
           // echo "Foo exists: ";
            $content = apc_fetch($key);

            return new \Symfony\Component\HttpFoundation\Response( $content);
        }*/

        $response = $this->prepareResponse($template, $rubric);

        //$content = $response->getContent();
        //  apc_store($key, $content);

        return $response;
    }


    protected function prepareResponse($template, $rubric)
    {
        return $this->render($template, array(
            'rubrics' => $this->getRubricsForMenu(),
            'currentRubric' => $rubric));
    }


    protected function getRubricsForMenu($queryBuilder = null)
    {
        if ($queryBuilder === null) $queryBuilder = $this->getDefaultQueryBuilder();

        return $this->getRubricsRepository()->getTreeRecordset($queryBuilder);
    }

    public function getDefaultQueryBuilder()
    {
        return function ($qb)
        {
            $qb->andWhere('r.level > 0')->andWhere('r.status = 1')->orderBy('r.left', 'ASC');
        };
    }
}
