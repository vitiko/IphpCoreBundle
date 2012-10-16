<?php
namespace Iphp\CoreBundle\Module;
use Iphp\CoreBundle\Module\Module;

/**
 * Модуль - список подрубрик текущей рубрики
 */
class RubricIndexModule extends Module
{
    function __construct()
    {
        $this->setName('Рубрика - список подрубрик');
        $this->allowMultiple = true;
    }

    protected function registerRoutes()
    {
        $this->addRoute('index', '/', array('_controller' => 'IphpCoreBundle:Rubric:indexSubrubrics'));
    }
}
