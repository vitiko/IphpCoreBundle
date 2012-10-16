<?php

namespace Iphp\CoreBundle\Entity;

use Iphp\CoreBundle\Model\Block;

use Doctrine\Common\Collections\ArrayCollection;

abstract class BaseBlock extends Block
{
    public function __construct()
    {
        $this->children = new ArrayCollection;

        parent::__construct();
    }

    public function prePersist()
    {
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    public function preUpdate()
    {
        $this->updatedAt = new \DateTime;
    }

    public function setChildren($children)
    {
        $this->children = new ArrayCollection;

        foreach ($children as $child) {
            $this->addChildren($child);
        }
    }
}