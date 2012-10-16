<?php

namespace Iphp\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin as BaseAdmin;
use Knp\Menu\ItemInterface as MenuItemInterface;

class Admin extends BaseAdmin
{
    public function prePersist($entity)
    {
        if (method_exists($entity, 'setUpdatedBy')) $entity->setUpdatedBy($this->getCurrentUser());
        if (method_exists($entity, 'setCreatedBy')) $entity->setCreatedBy($this->getCurrentUser());
        if (method_exists($entity, 'setCreatedAt')) $entity->setCreatedAt(new \DateTime);
        if (method_exists($entity, 'setUpdatedAt')) $entity->setUpdatedAt(new \DateTime);
    }


    public function preUpdate($entity)
    {
        if (method_exists($entity, 'setUpdatedBy')) $entity->setUpdatedBy($this->getCurrentUser());
        if (method_exists($entity, 'setUpdatedAt')) $entity->setUpdatedAt(new \DateTime);
    }

    protected function getCurrentUser()
    {
        return $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
    }

    /**
     * @param \Knp\Menu\ItemInterface $menu
     * @param $action
     * @param null|\Sonata\AdminBundle\Admin\Admin $childAdmin
     *
     * @return void
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, BaseAdmin $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }
        $admin = $this->isChild() ? $this->getParent() : $this;

        if (method_exists($admin->getSubject(), 'getCreatedAt'))
        {
            $menu->addChild($this->trans('Created At').':');
            $menu->addChild( $admin->getSubject()->getCreatedAt()->format ('d.m.Y H:i:s'));
        }

        if (method_exists($admin->getSubject(), 'getUpdatedAt'))
        {
            $menu->addChild($this->trans('Updated At').':');
            $menu->addChild( $admin->getSubject()->getUpdatedAt()->format ('d.m.Y H:i:s'));
        }


        if (method_exists($admin->getSubject(), 'getSitePath'))
            $menu->addChild(
                $this->trans('View on site'),
                array('uri' => $admin->getSubject()->getSitePath(), 'linkAttributes' => array('target' => '_blank'))
            );




    }

}