<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RubricController extends RubricAwareController
{

    /**
     * @Template()
     */
    public function indexSubrubricsAction()
    {
        $rubrics = $this->getCurrentRubric()->getChildren();

        return array('rubrics' => $rubrics);
    }


    /**
     * @Template()
     */
    public function indexSiteAction()
    {
        return array(


        );
    }


    public function redirectAction()
    {
        $rubric = $this->getCurrentRubric();

        if (!$rubric->getRedirectUrl()) {
            throw new \Exception ('redirect url not setted');
        }

        return new \Symfony\Component\HttpFoundation\RedirectResponse($rubric->getRedirectUrl());

    }
}
