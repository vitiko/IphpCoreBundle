<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Iphp\CoreBundle\Module;

use Iphp\CoreBundle\Model\RubricInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;


/**
 * Module in web site rubric
 */
abstract class Module
{

    /**
     * @var string Module Name
     */
    protected $name;


    /**
     * Access to external resources via ModuleManage
     * @var \Iphp\CoreBundle\Module\ModuleManager
     */
    protected $moduleManager;


    /**
     * Allow multiples module instances in rubrics
     * @var bool
     */
    protected $allowMultiple = false;

    /**
     * Module route collection
     * @var \Symfony\Component\Routing\RouteCollection
     */
    protected $routeCollection = null;


    /**
     * Rubric where module placed
     * @var \Iphp\CoreBundle\Model\Rubric
     */
    protected $rubric = null;


    abstract protected function registerRoutes();

    function __toString()
    {
       return (string) $this->getName();
    }


    function buildRouteCollection()
    {
        if ($this->routeCollection) return;

        $this->routeCollection = new RouteCollection();
        $this->registerRoutes();
    }


    public function setManager (ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
        return $this;
    }

    public function setRubric(RubricInterface $rubric)
    {
        $this->rubric = $rubric;
        return $this;
    }


    /**
     * @todo Broken in Symfony 2.7. See: vendor/symfony/symfony/src/Symfony/Bundle/FrameworkBundle/Routing/DelegatingLoader.php:54
     *
     * Нужно отрефакторить логику "$this->moduleManager->loadRoutes($resource, $type)"
     */
    protected function importRoutes ($resource, $type = null)
    {
      $routes = $this->moduleManager->loadRoutes($resource, $type);

      if ($routes) {
          foreach ($routes->all()  as $name => $route) {
              $this->routeCollection->add($name, $route);
          }
      }
    }



    protected function addRoute($name, $pattern, array $defaults = array(), array $requirements = array(),
                                array $options = array())
    {
        $route = new Route ($pattern,$defaults,$requirements,$options);
        $this->routeCollection->add ( $this->prepareRouteName ($name) ,    $route);
        return $this;
    }

    /**
     * Return route name. If route can be multiples - it prefixes with rubricFullPathCode
     * rubric: /some/path/, route : index , route name will be some_path_index
     * @param $name
     * @return string
     */
    protected  function prepareRouteName ($name)
    {
      return $this->allowMultiple && $this->rubric ?
          $this->moduleManager->getEntityRouter()->routeNameForRubricAction($this->rubric, $name)  : $name;
    }


    public function getRoutes()
    {
        $this->buildRouteCollection();
        return $this->routeCollection;
    }


    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }


    /**
     * Allow multiple module instances
     * @return bool
     */
    public function isAllowMultiple()
    {
        return $this->allowMultiple ? true : false;
    }


    public function getAdminExtension()
    {

    }
}
