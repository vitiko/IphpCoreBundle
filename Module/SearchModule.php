<?php
/**
 * Created by Vitiko
 * Date: 25.07.12
 * Time: 15:29
 */

namespace Iphp\CoreBundle\Module;

use Iphp\CoreBundle\Module\Module;


/**
 * Поиск
 */
class SearchModule extends Module
{

    function __construct()
    {
        $this->setName('Поиск');
        $this->allowMultiple =  false;
    }

    protected function registerRoutes()
    {
        $this->addRoute('search', '/', array('_controller' => 'IphpCoreBundle:Search:search'));
    }

}
