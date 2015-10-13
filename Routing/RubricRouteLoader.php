<?php

namespace Iphp\CoreBundle\Routing;

use Iphp\CoreBundle\Module\ModuleManager;
use Symfony\Component\Routing\RouteCollection;
use Exception;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;

class RubricRouteLoader implements LoaderInterface
{
    /**
     * \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Iphp\CoreBundle\Module\ModuleManager
     */
    protected $moduleManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var LoaderResolverInterface
     */
    private $resolver;

    /**
     * @param EntityManager   $em
     * @param ModuleManager   $moduleManager
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManager $em, ModuleManager $moduleManager, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->moduleManager = $moduleManager;
        $this->logger = $logger;
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

        $this->logger->info('Create route collection from database');

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

                $moduleError = '';
                try {
                    $module = $this->moduleManager->getModuleFromRubric($rubric);

                    if ($module) {
                        $rubricRoutes = $module->getRoutes();

                        if (count($resources = $module->getRouteResources())) {
                            $rubricRoutes->addCollection($this->importResources($resources));
                        }
                    }
                } catch (Exception $e) {
                    $moduleError = $e->getMessage();
                }

                $change = false;
                if ($moduleError)
                {
                    $change = true;
                    $rubric->setModuleError($moduleError);
                }
                else if ($rubric->getModuleError())
                {
                    $rubric->setModuleError(null);
                    $change = true;
                }

                if ($change)
                {
                    $this->em->persist($rubric);
                    $this->em->flush();
                }


            }

            if ($rubricRoutes) {

                if ($rubric->getLevel()) $rubricRoutes->addPrefix(substr($rubric->getFullPath(), 0, -1));
                $rubricRoutes->addDefaults(array('_rubric' => $rubric->getFullPath()));

                $routes->addCollection($rubricRoutes);
            }
        }

        //$this->em->flush();

        $b = microtime(true) - $a;
        $this->logger->info('Routes load time' . $b . ' с');

        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param array $resources
     *
     * @return RouteCollection
     */
    private function importResources(array $resources)
    {
        $collection = new RouteCollection();

        foreach ($resources as $resource => $type) {
            if (false !== $loader = $this->resolver->resolve($resource, $type)) {
                $collection->addCollection($loader->load($resource, $type));
            }
        }

        return $collection;
    }
}
