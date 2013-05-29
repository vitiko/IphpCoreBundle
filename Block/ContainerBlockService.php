<?php


namespace Iphp\CoreBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;


use Sonata\BlockBundle\Block\BaseBlockService;

//use Sonata\PageBundle\Model\PageInterface;
//use Sonata\PageBundle\Generator\Mustache;


class ContainerBlockService extends BaseBlockService
{
    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $block, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        $response = $this->renderResponse('IphpCoreBundle:Block:block_container.html.twig', array(
            'container' => $block,
            'settings'  => $settings,
        ), $response);

        //$response->setContent('Контент контейнера'

            /*Mustache::replace($settings['layout'], array(
            'CONTENT' => $response->getContent()
        ))*/
    //);

        return $response;
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        // TODO: Implement validateBlock() method.
    }
    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
      //  $formMapper->add('enabled', null, array('required' => false));

      /*  $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('layout', 'textarea', array()),
//                array('orientation', 'choice', array(
//                    'choices' => array('block' => 'Block', 'left' => 'Left')
//                )),
            )
        ));*/

        $formMapper->add('children', 'sonata_type_collection', array('label' => 'Children Blocks'), array(
            'edit'   => 'inline',
            'inline' => 'table',
            'sortable' => 'position'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Группа блоков';
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultSettings()
    {
        return array(
            'name'        => '',
            'layout'      => '{{ CONTENT }}',
            'orientation' => 'block',
        );
    }
}
