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
    public function MenuAction($template = '', $rubric = null, $activeBranch = true)
    {


        $currentRubric = is_array($rubric) ? ''  : $rubric;
        unset ($rubric);

        $currentRubricFullPath = is_object(  $currentRubric) ? $currentRubric->getFullPath() :  $currentRubric;

        if (strpos($template, ':') === false) $template = 'IphpCoreBundle:Menu:' . $template;


        //print_r ($currentRubricFullPath);
        //exit();


        $rubrics = $this->getRubricsForMenu(array(
            'onCreate' => $currentRubric ? function ($rubric) use ($currentRubricFullPath, $activeBranch)
            {

                if (
                    $rubric->getFullPath() == $currentRubricFullPath ||
                    ($activeBranch &&
                      substr($currentRubricFullPath, 0, strlen($rubric->getFullPath())) == $rubric->getFullPath())
                )
                    $rubric->setIsActive(true);
            } : null));

        //$this->prepareActiveRubrics ($rubrics);


        return $this->render($template, array(
            'rubrics' => $rubrics,
            'currentRubric' => $currentRubric));
    }


    protected function  prepareActiveRubrics($rubrics)
    {


    }


    protected function getRubricsForMenu($options = array())
    {
        if (!isset($options['qb']) || $options['qb'] === null) $options['qb'] = $this->getDefaultQueryBuilder();

        $rubricsTree = $this->getRubricsRepository()->getTreeRecordset($options['qb'], array(
            'nodeClass' => '\\Iphp\\CoreBundle\\Model\\MenuRubricWrapper',
            'onCreate' => isset($options['onCreate']) && $options['onCreate'] ? $options['onCreate'] : null
        ));

        return $rubricsTree;
    }

    public function getDefaultQueryBuilder()
    {
        return function ($qb)
        {
            $qb->andWhere('r.level > 0')->andWhere('r.status = true')->orderBy('r.left', 'ASC');
        };
    }
}
