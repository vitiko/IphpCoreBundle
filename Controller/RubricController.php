<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RubricController extends RubricAwareController
{
    public function indexSubrubricsAction()
    {
        $rubrics = $this->getCurrentRubric()->getChildren();

        return $this->render('IphpCoreBundle::indexSubrubrics.html.twig', array('rubrics' => $rubrics));
    }


    public function indexSiteAction()
    {
        return $this->render('IphpCoreBundle::indexSite.html.twig', array(



        ));
    }


    public function redirectAction()
    {
        $rubric = $this->getCurrentRubric();

       if (!$rubric->getRedirectUrl())
       {
           throw new \Exception ('redirect url not setted');
       }

        return new \Symfony\Component\HttpFoundation\RedirectResponse($rubric->getRedirectUrl());

    }
}
