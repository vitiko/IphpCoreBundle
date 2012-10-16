<?php
namespace Iphp\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Iphp\CoreBundle\Module\ModuleManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModuleChoiceType extends AbstractType
{

    /**
     * @var Iphp\CoreBundle\Module\ModuleManager
     */
    protected $moduleManager;

    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $moduleOptions = array();
        foreach ($this->moduleManager->modules() as $module)
        {
            $moduleOptions[get_class($module)] = $module->getName();
        }

        $resolver->setDefaults(array(
            'choices' => $moduleOptions
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