<?php

namespace Iphp\CoreBundle\Block;

use Symfony\Component\HttpFoundation\Response;



use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Block\Service\TextBlockService as BaseTextBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;

/**
 *
 * @author     Vitiko <vitiko@mail.ru>
 */
class TextBlockService extends BaseTextBlockService
{
    /**
     * {@inheritdoc}
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        return $this->renderResponse('IphpCoreBundle:Block:block_core_text.html.twig', array(
            'block'     => $block,
            'settings'  => $settings
        ), $response);
    }



    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'label' => 'Содержание блока',
            'keys' => array(
                array('content', 'genemu_tinymce', array('attr' => array ('class' => 'label_hidden'), 'label' => ' ', 'required' => false)),
                array('link', 'text', array('label' => 'Ссылка заголовка блока', 'required' => false))
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Текстовый блок';
    }



    function getDefaultSettings()
    {
        return array(
            'content' => '',
        );
    }

}