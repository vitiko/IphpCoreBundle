<?php

namespace Iphp\CoreBundle\Form\Type;

use  Sonata\BlockBundle\Form\Type\ServiceListType as BaseServiceListType;

use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Sonata\BlockBundle\Block\BlockServiceManagerInterface;

class BlockServiceListType extends BaseServiceListType
{

    protected $blocksSource;

    function setBlocksSource($blocksSource)
    {
        $this->blocksSource = $blocksSource;
    }


    public function __construct(BlockServiceManagerInterface $manager, array $contexts = array())
    {
        parent::__construct($manager, $contexts);
        $this->contexts = array ('cms' => 1,'admin' => 1);
    }

    /**
     * @param array $options
     * @return array
     */
/*    public function getDefaultOptions()
    {
        //TODO: наигрязнейший хак
        $this->contexts = array ('cms' => 1,'admin' => 1);
        $options = parent::getDefaultOptions();

        if (!isset($options['value_strategy'])) $options['value_strategy'] = 1;
        if (!isset($options['index_strategy'])) $options['index_strategy'] = 1;


        return $options;
    }*/

    /**
     * @return array
     */
    protected function getBlockTypes($context)
    {

        return $this->blocksSource->getBlockTypes($context);
        /*      $types = array();
       foreach ($this->contexts[$context] as $service) {
           $types[$service] = sprintf('%s -! %s', $this->manager->getService($service)->getName(), $service);
       }

       return $types;*/
    }
}