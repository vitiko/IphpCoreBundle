<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\CoreBundle\Traits\Field;


trait HasUrl {

    /**
     * @var string url
     */
    protected $url;

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */



} 