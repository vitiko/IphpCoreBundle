<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\CoreBundle\Admin\Traits;


use Sonata\AdminBundle\Form\FormMapper;

trait EntityInformationBlock
{


    function addInformationBlock(FormMapper $formMapper, $title = 'Status')
    {
        $formMapper
            ->with($title, array('class' => 'col-md-4'))->end()
            ->with($title)
            ->add('updatedAt', 'genemu_plain', ['required' => false, 'data_class' => 'DateTime'])
            ->add('createdAt', 'genemu_plain', ['required' => false, 'data_class' => 'DateTime']);


        if (method_exists($this->getSubject(), 'getCreatedBy'))
            $formMapper->add('createdBy', 'genemu_plain',
                ['required' => false, 'data_class' => '\\Application\\Sonata\\UserBundle\\Entity\\User']);

        if (method_exists($this->getSubject(), 'getUpdatedBy'))
            $formMapper->add('updatedBy', 'genemu_plain',
                ['required' => false, 'data_class' => '\\Application\\Sonata\\UserBundle\\Entity\\User']);


        $formMapper->end();
    }


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
} 