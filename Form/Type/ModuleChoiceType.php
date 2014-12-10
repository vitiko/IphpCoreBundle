<?php
namespace Iphp\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Iphp\CoreBundle\Module\ModuleManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModuleChoiceType extends AbstractType
{

    /**
     * @var \Iphp\CoreBundle\Module\ModuleManager
     */
    protected $moduleManager;

    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        //Modules by bundle and translated

        $resolver->setDefaults(array(
            'choices' => $this->moduleManager->modules(true, true)
        ));
    }


    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'modulechoice';
    }
}