<?php

namespace Iphp\CoreBundle\Routing;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;


use Symfony\Component\HttpKernel\Kernel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RubricRouteLoader implements LoaderInterface
{

    /**
     * \Symfony\Component\HttpKernel\Kernel
     */
    protected $kernel;

    /**
     * \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $container;

    /**
     * @var \Iphp\CoreBundle\Module\ModuleManager
     */
    protected $moduleManager;

    public function __construct(Kernel $kernel, EntityManager $em, ContainerInterface $container)
    {
        $this->kernel = $kernel;
        $this->em = $em;
        $this->container = $container;
        $this->moduleManager = $container->get('iphp.core.module.manager');
    }


    /**
     * @param string $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return ($type == 'iphp_rubric');
    }

    /**
     * @param string $resource
     * @param null $type
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function load($resource, $type = null)
    {
        $routes = new RouteCollection();

        $logger = $this->container->get('logger');
        $logger->info('Create route collection from database');

        $a = microtime(true);
        $rubrics = $this->em->getRepository('ApplicationIphpCoreBundle:Rubric')
            ->createQueryBuilder('r')
            ->orderBy('r.level', 'DESC')
            ->getQuery()->getResult();


        foreach ($rubrics as $rubric) {

            $controller = $rubric->getControllerName();
            $rubricRoutes = null;

            //В контроллере можеть быть: Класс модуля
            if ($controller && substr($controller, -6) == 'Module') {
                $module = $this->moduleManager->getModuleFromRubric($rubric);
                if ($module) $rubricRoutes = $module->getRoutes();
            }

            if ($rubricRoutes) {

                if ($rubric->getLevel())  $rubricRoutes->addPrefix(  substr($rubric->getFullPath(), 0, -1));
                $rubricRoutes->addDefaults( array('_rubric' => $rubric->getFullPath()));

                $routes->addCollection($rubricRoutes);
            }
        }
 
        $b = microtime(true) - $a;
        $logger->info('Routes load time' . $b . ' с');

        return $routes;
    }


    public function getResolver()
    {
    }

    public function setResolver(LoaderResolverInterface $resolver)
    { // irrelevant to us, since we don't need a resolver
    }


}