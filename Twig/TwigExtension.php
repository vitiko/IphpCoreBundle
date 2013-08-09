<?php

namespace Iphp\CoreBundle\Twig;

use \Iphp\CoreBundle\Routing\EntityRouter;
use Iphp\CoreBundle\Manager\RubricManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $twigEnviroment;

    /**
     * @var \Iphp\CoreBundle\Manager\RubricManager
     */
    protected $rubricManager;

    /**
     * @var  \Iphp\CoreBundle\Routing\EntityRouter;
     */
    protected $entityRouter;


    /**
     * @var  \Symfony\Component\Security\Core\SecurityContextInterface
     */
    protected $securityContext;

    public function __construct(\Twig_Environment $twigEnviroment,
                                RubricManager $rubricManager,
                                EntityRouter $entityRouter,
                                SecurityContextInterface $securityContext)
    {
        $this->twigEnviroment = $twigEnviroment;
        $this->rubricManager = $rubricManager;
        $this->entityRouter = $entityRouter;
        $this->securityContext = $securityContext;

        $twigEnviroment->addGlobal('iphp', new TemplateHelper($rubricManager));
    }






    public function getFunctions()
    {
        return array(
            'iphp_block_by_name' => new \Twig_Function_Method($this, 'getBlockByName'),

            'entitypath' => new \Twig_Function_Method($this, 'getEntityPath'),
            'entityaction' => new \Twig_Function_Method($this, 'getEntityActionPath'),

            'inlineedit' => new \Twig_Function_Method($this, 'getInlineEditStr', array('is_safe' => array('html'))),

            'rpath' => new \Twig_Function_Method($this, 'getRubricPath'),
            'path_exists' => new \Twig_Function_Method($this, 'pathExists'),

        );
    }


    public function getRubricPath($rubric)
    {
        return $this->rubricManager->generatePath($rubric);
    }


    function pathExists($name)
    {

        return (null === $this->entityRouter->getRouter()->getRouteCollection()->get($name)) ? false : true;
    }

    public function getBlockByName($blockName, $rubric = null)
    {
        $blocks = $this->rubricManager->getBlockRepository()->findBy(
            array('title' => $blockName, 'enabled' => 1));
        if ($rubric == null) $rubric = $this->rubricManager->getCurrent();

        $commonBlock = null;
        foreach ($blocks as $block) {
            if ($rubric && $block->getRubric() && $block->getRubric()->getId() == $rubric->getId()) return $block;
            if (!$block->getRubric() && !$commonBlock) $commonBlock = $block;
        }

        if ($commonBlock) return $commonBlock;
        return null;
    }


    public function getEntityPath($entity, $arg1 = null, $arg2 = null, $arg3 = null)
    {
        $path = $this->entityRouter->entitySitePath($entity, $arg1, $arg2, $arg3);

        if (substr($path, 0, strlen($this->rubricManager->getBaseUrl())) != $this->rubricManager->getBaseUrl())
            $path = $this->rubricManager->getBaseUrl() . $path;

        return $path;
    }

    public function getEntityActionPath($entityName, $action = 'index')
    {
        return $this->entityRouter->generateEntityActionPath($entityName, $action);
    }


    public function getInlineEditStr($entity)
    {
        return $this->securityContext->isGranted(array('ROLE_ADMIN' /*,'ROLE_SUPER_ADMIN'*/)) ?
            '<a href="#" onClick="return inlineEdit (\'' . addslashes(get_class($entity)) . '\',' . $entity->getId() .
                ')">edit</a>' : '';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'iphpp';
    }

}



