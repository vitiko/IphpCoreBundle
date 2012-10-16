<?php
namespace Iphp\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class SlugTextType extends AbstractType
{

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'blank_title' => '',
            'source_field' => 'title',
            'usesource_title' => 'Использовать название'

        ));
    }


    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // print_r (array_keys($options));

        // print $options['source_field'];


        // var_dump ($form->getParent()->getData()  );

        $view->vars['blank_title'] = $options['blank_title'];
        $view->vars['source_field'] = $options['source_field'];
        $view->vars['usesource_title'] = $options['usesource_title'];
        $view->vars['is_new'] = $form->getParent()->getData()->getId() ? false : true;
        $view->vars['is_blank'] = !$form->getData();


         if ($options['source_field'])
         {
             $view->vars['source_field_id'] = $view->parent->children[$view->vars['source_field']]->vars['id'];
         }

    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'slug_text';
    }
}