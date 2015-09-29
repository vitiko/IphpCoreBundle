<?php
namespace Iphp\CoreBundle\Module;
use Iphp\CoreBundle\Module\Module;

/**
 * Модуль индекс сайта
 */
class SiteIndexModule extends Module
{
    function __construct()
    {
        $this->setName('Website main page');
        $this->allowMultiple = false;
    }

    protected function registerRoutes()
    {
        $this->addRoute('site_index', '/', array('_controller' => 'IphpCoreBundle:Rubric:indexSite'));
    }
}
