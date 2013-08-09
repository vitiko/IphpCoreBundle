<?php


namespace Iphp\CoreBundle\Model;

use Sonata\BlockBundle\Model\BlockInterface;


abstract class Rubric implements RubricInterface, \Iphp\TreeBundle\Model\TreeNodeInterface
{
    protected $title;

    protected $abstract;

    protected $path;

    protected $fullPath;

    protected $redirectUrl;

    protected $status;

    protected $controllerName;

    protected $moduleError;

    protected $createdAt;

    protected $updatedAt;


    protected $left;

    protected $right;

    protected $root;

    protected $level;

    protected $parent;

    protected $parentId;

    protected $children;

    protected $blocks;


    protected $contents;


    public function __construct()
    {
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection;

    }

    /**
     * Set title
     *
     * @param string $title
     * @return \Iphp\CoreBundle\Model\Rubric
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }


    public function getSitePath()
    {
        return $this->getFullPath();
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }


    public function getTitleLevelIndented()
    {
        return str_repeat('...', $this->getLevel() * 3) . $this->getTitle();
    }


    /**
     * Set abstract
     *
     * @param text $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * Get abstract
     *
     * @return text $abstract
     */
    public function getAbstract()
    {
        return $this->abstract;
    }


    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getPath()
    {
        return (string) $this->path;
    }

    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;
        return $this;
    }

    public function getFullPath()
    {
        return (string) $this->fullPath;
    }


    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer  $status
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime);
        $this->setUpdatedAt(new \DateTime);
    }

    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime);
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get lft
     *
     * @return integer
     */
    public function getLeft()
    {
        return $this->left;
    }


    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRight()
    {
        return $this->right;
    }


    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    public function isRoot()
    {
        return $this->level === 0;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }


    public function setParent(\Iphp\TreeBundle\Model\TreeNodeInterface $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }


    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add children
     *
     * @param Iphp\TreeBundle\Model\TreeNodeInterface $children
     */
    public function addChildren(\Iphp\TreeBundle\Model\TreeNodeInterface $children)
    {
        $this->children[] = $children;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTitle();
    }

    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
        return $this;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Add blocks
     *
     * @param \Sonata\BlockBundle\Model\BlockInterface $blocs
     */
    public function addBlocks(BlockInterface $blocs)
    {
        $this->blocks[] = $blocs;
    }


    public function getBlocks()
    {
        return $this->blocks;
    }


    public function getBlock($blockName)
    {

        foreach ($this->getBlocks() as $block)
            if ($block->getTitle() == $blockName) return $block;
    }


    public function setContents($contents)
    {
        $this->contents = $contents;
        return $this;
    }

    public function getContents()
    {
        return $this->contents;
    }


    function addContents(\Application\Iphp\ContentBundle\Entity\Content $content)
    {
        $content->setRubric($this);
        $this->contents[] = $content;
    }

    public function setModuleError($moduleError)
    {
        $this->moduleError = $moduleError;
        return $this;
    }

    public function getModuleError()
    {
        return $this->moduleError;
    }


}
