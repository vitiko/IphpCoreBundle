<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\CoreBundle\Traits;


use Iphp\CoreBundle\Routing\EntityRouter;

trait HasSitePathById {


    public function getSitePath(EntityRouter $entityRouter, $action = 'view')
    {
        return $entityRouter->generateEntityActionPath($this, $action, array(
            'id' => $this->getId()
        ));
    }
} 