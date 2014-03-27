<?php

namespace Iphp\CoreBundle\Entity;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;

class BaseEntityQueryBuilder extends QueryBuilder
{
    protected $currentAlias;

    protected $entityName;

    public function getDefaultAlias()
    {
        return 'e';
    }

    public function setCurrentAlias($alias = '')
    {
        $this->currentAlias = $alias ? $alias : $this->getDefaultAlias();
        return $this;
    }


    public function getCurrentAlias()
    {
        return $this->currentAlias;
    }

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }


    public function prepareDefaultQuery()
    {
        return $this->select($this->currentAlias)
            ->from($this->entityName, $this->currentAlias);
    }


    /**
     * Поддержка magic методом whereXXX и joinXXX
     * @param $method
     * @param $arguments
     */
    public function  __call($method, $arguments)
    {
        switch (true) {
            case (substr($method, 0, 5) == 'where'):
                $fieldName = lcfirst(substr($method, 5, strlen($method)));
                $method = 'where';
                break;

            case (substr($method, 0, 4) == 'join'):
                $fieldName = lcfirst(substr($method, 4, strlen($method)));
                $method = 'join';
                break;

            case (substr($method, 0, 8) == 'searchBy'):
                $fieldName = lcfirst(substr($method, 8, strlen($method)));
                $method = 'searchBy';
                break;


            case (substr($method, 0, 10) == 'searchLeft'):
                $fieldName = lcfirst(substr($method, 10, strlen($method)));
                $method = 'searchLeft';
                break;

            default:
                throw new \BadMethodCallException(
                    "Undefined method '$method'. The method name must start with " .
                    "either where , join, searchBy!"
                );
        }

        if (empty($arguments) && $method == 'where') {
            throw new \BadMethodCallException(
                "Method '$method' need arguments field name and field value (for whereXXX)!"
            );
        }

        //$fieldName = lcfirst(\Doctrine\Common\Util\Inflector::classify($by));

        if ($method == 'where' /*&& $this->_class->hasField($fieldName) || $this->_class->hasAssociation($fieldName)*/) {

            return (is_array($arguments[0])) ?
                $this->andWhere($this->expr()->in($this->currentAlias . '.' . $fieldName, $arguments[0]))
                :
                (is_null($arguments[0]) ?
                    $this->andWhere($this->currentAlias . '.' . $fieldName . ' IS NULL') :
                    $this->andWhere($this->currentAlias . '.' . $fieldName . ' = :' . $fieldName)
                        ->setParameter($fieldName, $arguments[0]));
        } else if ($method == 'join' /*&& $this->_class->hasField($fieldName) || $this->_class->hasAssociation($fieldName)*/) {

            //return $this;
            return $this->innerJoin($this->currentAlias . '.' . $fieldName, $fieldName);
        } else if ($method == 'searchBy') {
            return $this->andWhere($this->expr()->like($this->currentAlias . '.' . $fieldName, $this->expr()->literal('%' . $arguments[0] . '%')));
        } else if ($method == 'searchLeft') {
            return $this->andWhere($this->expr()->like($this->currentAlias . '.' . $fieldName, $this->expr()->literal($arguments[0] . '%')));
        }


        throw new \BadMethodCallException(
            "Method '$method' not found!"
        );
    }


    protected function getSearchFields($params = array())
    {
        $fields = isset($params['fields']) && $params['fields'] ? $params['fields'] : array('id', 'title');

        $searchFields = array();
        foreach ($fields as $field)
            $searchFields[] = (strpos($field, '.') === false) ? $this->currentAlias . '.' . $field : $field;

        return $searchFields;

    }

    public function search($searchStr, $fields = array())
    {
        if (!$searchStr) return $this;
        $searchExpr = $this->expr()->orx();

        foreach ($this->getSearchFields(array('fields' => $fields)) as $field)
            $searchExpr->add($this->expr()->like($field, $this->expr()->literal('%' . $searchStr . '%')));

        $this->andWhere($searchExpr);
        return $this;
    }

    public function mapFromForm(FormInterface $form)
    {
        FormQueryBuilderMapper::create($form, $this)->addAll()->process();
        return $this;
    }

    public function getQueryWithRowNumHint($hintName, $distinct = false)
    {
        $query = $this->getQuery();
        $res = $this->getCountClone($distinct)->getQuery()->getOneOrNullResult();
        $query->setHint($hintName, $res['rownum']);

        return $query;
    }


    public function getCountClone($distinct = false)
    {
        $qbCount = clone $this;
        $qbCount->resetDQLPart('orderBy')
            ->select('COUNT(' . ($distinct ? 'DISTINCT ' : '') . $qbCount->getCurrentAlias() . '.id) as rownum');

        return $qbCount;
    }

}


