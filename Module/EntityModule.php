<?php
/**
 * Created by Vitiko
 * Date: 02.08.12
 * Time: 11:55
 */

namespace Iphp\CoreBundle\Module;

abstract class EntityModule extends Module
{

    protected $entityName;


    protected $baseController;


    protected $entityActions = array(
        'index' => '/',
        'view' => '/{id}/'
    );


    protected function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }

    /**
     * @param mixed $baseController
     */
    public function setBaseController($baseController)
    {
        $this->baseController = $baseController;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBaseController()
    {
        return $this->baseController;
    }


    protected function addEntityRoute($entityName, $action, $pattern = null,
                                      array $defaults = array(), array $requirements = array(),
                                      array $options = array())
    {

        if (!$pattern && isset($this->entityActions[$action])) $pattern = $this->entityActions[$action];

        if (!isset($defaults['_controller']))
        {

            if ($this->getBaseController() == $this->getEntityName()) $controllerAction = $action;
            else
            {
                list ($bundleName,$entityClassName) = explode (':', $entityName);
                $controllerAction = lcfirst($entityClassName).ucfirst($action);
            }

            $defaults['_controller'] = $this->getController() . ':' . $controllerAction;

        }

        $this->addRoute($this->getEntityRouteName($entityName, $action),
            $pattern,
            $defaults,
            $requirements,
            $options);

        return $this;
    }


    protected function getController()
    {
        return $this->getBaseController() ? $this->getBaseController() : $this->getEntityName();
    }

    protected function addEntityAction($name, $path = null)
    {
        $this->entityActions[$name] = $path ? $path : '/' . $name . '/';
        return $this;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }


    protected function registerRoutes()
    {
        foreach ($this->entityActions as $action => $pattern)
            $this->addEntityRoute($this->entityName, $action, $pattern);

    }


    protected function getEntityRouteName($entity, $action = 'view')
    {
        $entityName = is_object($entity) ? get_class($entity) : $entity;
        return $this->moduleManager->getEntityRouter()->routeNameForEntityAction($entityName, $action);
    }

    public function setEntityActions($entityActions)
    {
        $this->entityActions = $entityActions;
        return $this;
    }

    public function getEntityActions()
    {
        return $this->entityActions;
    }
}