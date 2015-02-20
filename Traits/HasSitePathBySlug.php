<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\CoreBundle\Traits;


use Iphp\CoreBundle\Routing\EntityRouter;

trait HasSitePathBySlug {


    public function getSitePath(EntityRouter $entityRouter, $action = 'view')
    {

        return $entityRouter->generateEntityActionPath($this, $action, array(
            'id' => $this->getSlug()
        ));
    }
} 