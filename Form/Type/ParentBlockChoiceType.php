<?php
namespace Iphp\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Doctrine\ORM\EntityManager;

use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;


use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParentBlockChoiceType extends AbstractType
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

            'empty_value' => '',
            'choice_list' => function (Options $options, $previousValue) use ($em)
            {

                $qb = $em->getRepository('ApplicationIphpCoreBundle:Block')->createQueryBuilder('b');
                $qb->where(
                    $qb->expr()->like('b.type', $qb->expr()->literal('%container%'))
                )->orderBy('b.title');


                return new  EntityChoiceList (
                    $em,
                    'Application\Iphp\CoreBundle\Entity\Block',
                    null,
                    new ORMQueryBuilderLoader ($qb));
            },


        ));

    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'parentblock_choice';
    }
}
