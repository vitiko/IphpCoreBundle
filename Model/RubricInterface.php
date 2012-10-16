<?php



namespace Iphp\CoreBundle\Model;

interface RubricInterface
{
    /**
     * Set title
     *
     * @param string $title
     */
    function setTitle($title);

    /**
     * Get title
     *
     * @return string $title
     */
    function getTitle();

    /**
     * Set abstract
     *
     * @param text $abstract
     */
    function setAbstract($abstract);

    /**
     * Get abstract
     *
     * @return text $abstract
     */
    function getAbstract();



    function getPath();

    function getFullPath();

    /**
     * Set status
     *
     * @param boolean $status
     */
    function setStatus($status);

    /**
     * Get status
     *
     * @return boolean $status
     */
    function getStatus();


    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     */
    function setCreatedAt(\DateTime $createdAt = null);

    /**
     * Get created_at
     *
     * @return \DateTime $createdAt
     */
    function getCreatedAt();

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     */
    function setUpdatedAt(\DateTime $updatedAt = null);

    /**
     * Get updated_at
     *
     * @return datetime $updatedAt
     */
    function getUpdatedAt();
}