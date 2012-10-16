<?php


namespace Iphp\CoreBundle\Model;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Model\Block as BaseBlock;

use Iphp\CoreBundle\Model\RubricInterface;

abstract class Block extends BaseBlock
{
    protected $rubric;

    protected $title;

    protected $keywords;

    /**
     * Add children
     *
     * @param \Sonata\BlockBundle\Model\BlockInterface $child
     */
    public function addChildren(BlockInterface $child)
    {
        $this->children[] = $child;

        $child->setParent($this);
        $child->setRubric($this->getRubric());
    }

    /**
     * Set rubric
     *
     * @param Iphp\CoreBundle\Model\RubricInterface $rubric
     */
    public function setRubric(RubricInterface $rubric= null)
    {
        $this->rubric = $rubric;
    }



    /**
     * Get rubric
     *
     * @return Iphp\CoreBundle\Model\RubricInterface $rubric
     */
    public function getRubric()
    {
        return $this->rubric;
    }

    public function disableChildrenLazyLoading()
    {
        if (is_object($this->children)) {
            $this->children->setInitialized(true);
        }
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }


    public function __toString()
    {
            return $this->getTitle() ?  $this->getTitle() : 'block (id:'.$this->getId().')';
    }

}