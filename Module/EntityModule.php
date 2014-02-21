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

    protected $controllerName;


    protected $entityActions = array(
        'index' => '/',
        'view' => '/{id}/'
    );

    protected function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
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
        foreach ($this->entityActions as $action => $pattern) {
            $this->addRoute(
                $this->getEntityRouteName($this->entityName, $action),
                $pattern,
                array('_controller' => $this->entityName . ':' . $action)
            );
        }
    }


    protected function getEntityRouteName ($entity, $action = 'view')
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