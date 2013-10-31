<?php

namespace Iphp\CoreBundle\Entity;

/**
 * @author Vitiko <vitiko@mail.ru>
 */
use Doctrine\ORM\QueryBuilder;
use Traversable;
use Symfony\Component\Form\Form;

class FormQueryBuilderMapper
{

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $qb;


    /**
     * @var \Symfony\Component\Form\Form
     */
    protected $form;


    /**
     * @var array
     */
    protected $mapping = array();

    /**
     * @var array
     */
    protected $mappingOptions = array();

    function __construct(Form $form, QueryBuilder $qb)
    {
        $this->form = $form;
        $this->qb = $qb;
    }


    static function create(Form $form, QueryBuilder $qb)
    {
        return new static ($form, $qb);
    }


    public function add($formField, $mapping, $default = null, $options = array())
    {
        $this->mapping[$formField] = $mapping;
        $this->mappingDefault[$formField]  = $default;
        $this->mappingOptions[$formField] = $options;

        return $this;
    }


    public function getDefaultValue($formField)
    {
        if ( is_callable($this->mappingDefault[$formField])) {
            $default  = $this->mappingDefault[$formField];
            return  $default();
        }
        else return $this->mappingDefault[$formField];
    }

    public function getValue($formField)
    {
        $value = $this->form[$formField]->getData();


        if (!$value) $value = $this->getDefaultValue($formField);

        return $value;
    }


    public function process($returnWithValues = true)
    {
        $processed = array();
        foreach (array_keys($this->mapping) as $formField) {

            $value = $this->processField($formField);
            if ($value || !$returnWithValues) $processed[$formField] = $value;
        }

        return $processed;
    }


    protected function setQbCondition($formField, $value)
    {

        $mapping = $this->mapping[$formField];

        if (is_callable($mapping)) {
            $mapping ($this->qb, $value);
        } else if ($value) {

            $multi = is_array($value) || $value instanceof Traversable;
            $holder = 'map_' . $formField;

            $this->qb->andWhere($mapping . ' ' . ($multi ? 'IN (:' . $holder . ')' : ' = :' . $holder))
                ->setParameter($holder, $value);
        }
    }

    public function processField($formField)
    {
        $value = $this->getValue($formField);
        $this->setQbCondition($formField, $value);


        return $value;

    }
}
