<?php

namespace Iphp\CoreBundle\Entity;

use Doctrine\ORM\QueryBuilder;

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


            case (substr($method, 0, 8) == 'searchLeft'):
                $fieldName = lcfirst(substr($method, 8, strlen($method)));
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

            return $this->andWhere($this->currentAlias . '.' . $fieldName . ' = :' . $fieldName)
                ->setParameter($fieldName, $arguments[0]);
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
}


