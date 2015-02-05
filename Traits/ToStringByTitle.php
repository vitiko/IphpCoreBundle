<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\CoreBundle\Traits;


trait ToStringByTitle {

    function __toString()
    {
        return (string) $this->getTitle();
    }

} 