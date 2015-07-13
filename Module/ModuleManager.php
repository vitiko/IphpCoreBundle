<?php
/**
 * Created by Vitiko
 * Date: 02.02.12
 * Time: 13:18
 */


namespace Iphp\CoreBundle\Module;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;


use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Finder\Finder;

class ModuleManager extends ContainerAware
{


    protected $modulesPath = 'Module';


    function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }


    /**
     * @return \Iphp\CoreBundle\Routing\EntityRouter
     */
    public function getEntityRouter()
    {
        return $this->container->get('iphp.core.entity.router');
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader
     */
    public function getRoutingLoader()
    {
        return $this->container->get('routing.loader');
    }


    /**
     * @return \Symfony\Component\HttpKernel\KernelInterface
     */
    protected function getKernel()
    {
        return $this->container->get('kernel');
    }


    /**
     * @return \Symfony\Component\Translation\TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->container->get('translator');
    }



    public function modules($byBundle = false, $trans = false)
    {
        $modules = array();
        foreach ($this->getKernel()->getBundles() as $bundle)
        {
            $bundleModules =  $this->bundleModules($bundle);

            if (!$bundleModules) continue;


            if ($trans)
            {
                $translatedTitles = array();
                foreach ($bundleModules as $module)
                {
                  $moduleName = $module->getName();
                  if (is_null($moduleName)) $moduleName = \Doctrine\Common\Util\ClassUtils::getClass($module);

                  $translatedTitles[get_class($module)] = $this->getTranslator()->trans ($moduleName);
               }

                $bundleModules = $translatedTitles;
            }


           uasort ($bundleModules, function ($a, $b) {

               if ((string) $a ==  (string) $b) {
                   return 0;
               }
               return ((string) $a < (string) $b) ? -1 : 1;
           });


            $bundleTitle = $bundle->getName().' Modules';
            if ($trans) $bundleTitle =  $this->getTranslator()->trans ($bundleTitle);

            if ($byBundle) $modules[$bundleTitle ] = $bundleModules;
            else $modules = array_merge($modules, $bundleModules);
        }


        return $modules;
    }

    public function loadRoutes($resource, $type = null)
    {
        return $this->getRoutingLoader()->load($resource, $type);
    }


    function bundleModuleDir($bundle)
    {
        return $bundle->getPath() . DIRECTORY_SEPARATOR . $this->modulesPath;
    }





    function getModuleByControllerName($controllerName)
    {
       return $this->getModuleInstance($controllerName);
    }

    /**
     * @param $bundle
     * @return \Iphp\CoreBundle\Module\Module[]
     */
    function bundleModules($bundle)
    {
        $dir = $this->bundleModuleDir($bundle);
        if (!file_exists($dir) || !is_dir($dir) || !is_readable($dir)) return array();

        $finder = new Finder();

        $modules = array();
        foreach ($finder->files()->in($dir)->name('/.+Module\.php$/') as $file) {
            $moduleClass = $bundle->getNamespace() . '\\' . $this->modulesPath . '\\' .
                    substr($file->getRealpath(), strlen($dir) + 1, -4);

            if (class_exists($moduleClass)) {


                $classInfo= new \ReflectionClass($moduleClass);
                if (!$classInfo->isAbstract()) $modules[] = new $moduleClass();
            }
        }
        return $modules;
    }


    /**
     * @param \Application\Iphp\CoreBundle\Entity\Rubric $rubric
     * @return \Iphp\CoreBundle\Module\Module
     */
    function getModuleFromRubric(\Application\Iphp\CoreBundle\Entity\Rubric $rubric)
    {
        $moduleClassName = $rubric->getControllerName();
        if (!$moduleClassName) return null;

        $module = $this->getModuleInstance($moduleClassName);
        if (!$module) return null;

        return $module->setRubric($rubric);
    }

    /**
     * @param $moduleClassName
     * @return \Iphp\CoreBundle\Module\Module
     */
    function getModuleInstance($moduleClassName)
    {
        if (!class_exists($moduleClassName, true)) return null;
        $module = new $moduleClassName ();
        $module->setManager($this);
        return $module;
    }
}
