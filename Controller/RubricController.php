<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        return array();
    }


    public function redirectAction()
    {
        $rubric = $this->getCurrentRubric();

        if (!$rubric)  return new RedirectResponse('/');

        $redirectUrl = $rubric->getRedirectUrl();

        if (!$redirectUrl) {
            throw new \Exception ('redirect url not setted');
        }

        return new RedirectResponse(
            $redirectUrl[0] == '/' ?   $this->getRubricManager()->generatePath($redirectUrl ) :
            $rubric->getRedirectUrl());
    }
}
