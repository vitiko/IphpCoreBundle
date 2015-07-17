<?php

namespace Iphp\CoreBundle\Manager;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

class RubricManager extends ContainerAware
{
    protected $em;

    protected $currentRubricByPath = [];

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->setContainer($container);
    }


    public function getByPath($rubricPath)
    {
        $key = 'rubricCache_' . $rubricPath;

        /*   if (apc_exists($key)) {
              echo "Foo exists: ";
               return unserialize (apc_fetch($key));
           }*/

        $rubric = $this->getRepository()->findOneByFullPath($rubricPath);

        //  apc_store($key, serialize($rubric ));
        return $rubric;
    }


    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('ApplicationIphpCoreBundle:Rubric');
    }


    public function getBlockRepository()
    {
        return $this->em->getRepository('ApplicationIphpCoreBundle:Block');
    }


    /**
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    protected function  getRequestFromContainer()
    {
        if ($this->container->has('request_stack')) {
            return $this->container->get('request_stack')->getCurrentRequest();
        } else if ($this->container->hasScope('request') && $this->container->isScopeActive('request'))
           return $this->container->get('request');

        return null;
    }


    /**
     * @return null|\Application\Iphp\CoreBundle\Entity\Rubric
     */
    public function getRubricFromRequest(Request $request = null)
    {
        if (!$request) $request = $this->getRequestFromContainer();

        if (!$request || !$request->get('_rubric')) return null;

        if (isset($this->currentRubricByPath[$request->get('_rubric')]))
            return $this->currentRubricByPath[$request->get('_rubric')];

        $this->currentRubricByPath[$request->get('_rubric')] =   $this->getByPath($request->get('_rubric'));

        return $this->currentRubricByPath[$request->get('_rubric')];
    }


    public function generatePath($rubric, $absolute = false)
    {
        return $this->getBaseUrl() . (is_string($rubric) ? $rubric : $rubric->getFullPath());
    }


    /**
     * Base url from request (with app_dev.php/ if in dev mode)
     */
    public function getBaseUrl()
    {
        return $this->getRequestFromContainer() ? $this->getRequestFromContainer()->getBaseUrl() : null;
    }


    /**
     * TODO : Use cache method from api
     * TODO: What if many requests
     */
    function clearCache()
    {
        $cacheDir = $this->container->getParameter('kernel.cache_dir');

        $kernel = $this->container->get('kernel');

        $cacheDir = realpath($cacheDir . '/../');


        foreach (array( /*'frontdev','frontprod',*/
                     'dev', 'prod') as $env) {
            foreach (array('UrlGenerator', 'UrlMatcher') as $file) {
                $cachedFile = $cacheDir . '/' . $env . '/app' . ucfirst($env) . $file . '.php';
                //print '<br>'.$cachedFile;
                if (file_exists($cachedFile)) @unlink($cachedFile);


                $cachedFile = $cacheDir . '/' . $env . '/app' . $env . $file . '.php';
                //print '<br>'.$cachedFile;
                if (file_exists($cachedFile)) @unlink($cachedFile);
            }
        }


        if (function_exists('apc_clear_cache')) apc_clear_cache('user');
        //exit();

    }

}