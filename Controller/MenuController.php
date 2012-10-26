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
    public function MenuAction($template = '', $rubric = null, $activeBranch = false)
    {
        $currentRubric = $rubric;
        unset ($rubric);

        if (strpos($template, ':') === false) $template = 'IphpCoreBundle:Menu:' . $template;


        $rubrics = $this->getRubricsForMenu(array(
            'onCreate' => $currentRubric ? function ($rubric) use ($currentRubric, $activeBranch)
            {
                if (
                    $rubric->getId() == $currentRubric->getId() ||
                    ($activeBranch && substr($rubric->getFullPath(), 0, strlen($currentRubric->getFullPath())))
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
            $qb->andWhere('r.level > 0')->andWhere('r.status = 1')->orderBy('r.left', 'ASC');
        };
    }
}
