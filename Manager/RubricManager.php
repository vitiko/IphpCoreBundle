<?php

namespace Iphp\CoreBundle\Manager;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class RubricManager extends ContainerAware
{
    protected $em;
    protected $request;


    protected $currentRubric = -1;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->request = $container->hasScope('request') && $container->isScopeActive('request') ?
                $container->get('request') : null;
        $this->setContainer($container);
    }


    public function getByPath($rubricPath)
    {
        $key = 'rubricCache_'.$rubricPath;

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
     * @return \Application\Iphp\CoreBundle\Entity\Rubric
     */
    public function getCurrent()
    {
        if ($this->currentRubric === -1)
            $this->currentRubric = $this->request && $this->request->get('_rubric') ?
                    $this->getByPath($this->request->get('_rubric')) : null;
        return $this->currentRubric;
    }


    public function generatePath($rubric, $absolute = false)
    {
        return  $this->getBaseUrl().(is_string($rubric) ? $rubric : $rubric->getFullPath());
    }



    //Базовый url с именем контроллера (app_dev.php/ например)
    public function getBaseUrl()
    {
        return $this->request->getBaseUrl();
    }


    /**
     * TODO : использовать стандартные методы очистки кэша
     * TODO: если большой поток запросов что будет
     */
    function clearCache()
    {
        $cacheDir = $this->container->getParameter('kernel.cache_dir');

        $kernel = $this->container->get('kernel');

        $cacheDir = realpath($cacheDir.'/../');


        foreach (array (/*'frontdev','frontprod',*/'dev','prod')  as $env)
        {
            foreach (array ('UrlGenerator','UrlMatcher') as $file)
            {
                $cachedFile = $cacheDir.'/'.$env.'/app'.ucfirst($env).$file.'.php';
                //print '<br>'.$cachedFile;
                if (file_exists($cachedFile)) @unlink ($cachedFile);


                $cachedFile = $cacheDir.'/'.$env.'/app'.$env.$file.'.php';
                //print '<br>'.$cachedFile;
                if (file_exists($cachedFile)) @unlink ($cachedFile);
            }
        }


        if (function_exists('apc_clear_cache')) apc_clear_cache('user');
        //exit();

    }

}