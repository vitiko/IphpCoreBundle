<?php
namespace Iphp\CoreBundle\Model;

use Iphp\TreeBundle\Model\TreeNodeWrapper;
/**
 * @author Vitiko <vitiko@mail.ru>
 */
class MenuRubricWrapper extends TreeNodeWrapper
{

    protected $active;


    function isActive()
    {
        return $this->active;
    }


    function setIsActive ($active)
    {
        $this->active = $active;
    }
}
