<?php

namespace Iphp\CoreBundle\Entity;

/**
 * @author Vitiko <vitiko@mail.ru>
 */
use Doctrine\ORM\QueryBuilder;
use Traversable;
use Symfony\Component\Form\Form;
use Iphp\CoreBundle\Entity\BaseEntityQueryBuilder;

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
    protected $mapping;

    /**
     * @var array
     */
    protected $defaultMapping;


    /**
     * @var array
     */
    protected $mappingOptions = array();

    function __construct(Form $form, QueryBuilder $qb)
    {
        $this->form = $form;
        $this->qb = $qb;

        $this->defaultMapping = $this->getDefaultMapping();
    }


    static function create(Form $form, QueryBuilder $qb)
    {
        return new static ($form, $qb);
    }


    protected function getDefaultMapping()
    {
        return array(
            'search' => function ($qb, $value) {
                    if ($value) $qb->search($value);
                }
        );
    }


    public function add($formField, $mapping = null, $default = null, $options = array())
    {
        $this->mapping[$formField] = $mapping;
        $this->mappingDefault[$formField] = $default;
        $this->mappingOptions[$formField] = $options;

        return $this;
    }


    public function addAll()
    {
        foreach ($this->form->all() as $fieldName => $field) {
            if (!in_array($field->getConfig()->getType()->getName(), array ('submit','button')))
                $this->add($fieldName);
        }

        return $this;
    }

    public function getDefaultValue($formField)
    {
        if (is_callable($this->mappingDefault[$formField])) {
            $default = $this->mappingDefault[$formField];
            return $default();
        } else return $this->mappingDefault[$formField];
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
        if (is_null($mapping) && isset($this->defaultMapping[$formField]))
            $mapping = $this->defaultMapping[$formField];

        $this->processMapping($formField, $value, $mapping);
    }


    protected function processMapping($formField, $value, $mapping)
    {
        if (is_callable($mapping)) {
            $mapping ($this->qb, $value);
        } else if ($value) {


            if (method_exists($this->qb, 'where' . ucfirst($formField)))
            {
                $this->qb->{'where' . ucfirst($formField)}($value);
            }
            else {


            $multi = is_array($value) || $value instanceof Traversable;
            $holder = 'map_' . $formField;



            if (!$mapping && $this->qb instanceof BaseEntityQueryBuilder)
              $mapping = $this->qb->getCurrentAlias().'.'.$formField;


            $this->qb->andWhere($mapping . ' ' . ($multi ? 'IN (:' . $holder . ')' : ' = :' . $holder))
                ->setParameter($holder, $value);

            }
        }
    }


    public function processField($formField)
    {
        $value = $this->getValue($formField);
        $this->setQbCondition($formField, $value);
        return $value;
    }
}
