<?php

namespace Iphp\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RoutingController extends Controller
{
    public function processRequestAction(Request $request, $rubricPath, $module, $path)
    {

        if ($path == '') $path = '/';
        $moduleObj = new $module();



        //RequestContext нужен только для проверки ограничений по типу запроса GET POST (если они есть в роуте)
        $matcher = new UrlMatcher($moduleObj->getRoutes(), new RequestContext());

        try {
          $matchResult = $matcher->match($path);
        }
        catch (ResourceNotFoundException  $e)
        {
          die ('Путь '.$rubricPath.$path.' не найден:'. $e->getMessage());
        }



        $controller = $matchResult['_controller'];


        return $this->forward($matchResult['_controller'], $matchResult );
    }
}
