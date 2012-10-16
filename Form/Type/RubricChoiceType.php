<?php
namespace Iphp\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Doctrine\ORM\EntityManager;

use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

use Symfony\Component\Form\Exception\TransformationFailedException;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RubricChoiceType extends AbstractType
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $em = $this->em;


        $resolver->setDefaults(array(
            'transform_to_id' => false,
            'module_likecondition' => false,
            'display_method' => 'titleLevelIndented',
            'display_transformer' => null,
            'choices_afterload' => null,


            'empty_value' => '',
            'choice_list' => function (Options $options, $previousValue) use ($em)
            {


                $qb = $em->getRepository('ApplicationIphpCoreBundle:Rubric')
                        ->createQueryBuilder('r')
                        ->orderBy('r.left');

                if ($options['module_likecondition']) {
                    $qb->where($qb->expr()->like('r.controllerName', $qb->expr()->literal($options['module_likecondition'])));
                }


                $entityChoiceList = new  EntityChoiceList ($em,
                    'Application\Iphp\CoreBundle\Entity\Rubric',
                    $options['display_method'],
                    new ORMQueryBuilderLoader ($qb));


                $transformToSimple = $options['transform_to_id'];


                $displayTransformer = $options['display_transformer'];
                $choicesAfterLoad = $options['choices_afterload'];

                if ($displayTransformer) $transformToSimple = true;



                if ($transformToSimple) {
                    $choices = $entityChoiceList->getChoices();
                    foreach ($choices as $key => $choice)
                        if (is_object($choice)) $choices[$key] =
                                $displayTransformer ?
                                        $displayTransformer($choice) : $choice->{'get' . $options['display_method']}();

                    if ($choicesAfterLoad) $choicesAfterLoad ($choices);
                    return new SimpleChoiceList ($choices);
                }
                else
                {
                    if ($choicesAfterLoad) $choicesAfterLoad ($entityChoiceList);
                    return $entityChoiceList;
                }
            },


        ));

    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'rubricchoice';
    }


}

