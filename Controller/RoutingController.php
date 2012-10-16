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

        print '--"' . $rubricPath . ' -- ' . $module . '"';
        //$module = new \Iphp\ContentBundle\Module\ContentIndexModule();

        if ($path == '') $path = '/';
        $moduleObj = new $module();


        // $context->fromRequest($request);



        //RequestContext нужен только для проверки ограничений по типу запроса GET POST (если они есть в роуте)
        $matcher = new UrlMatcher($moduleObj->getRoutes(), new RequestContext());

        try {
          $matchResult = $matcher->match($path);
        }
        catch (ResourceNotFoundException  $e)
        {
          die ('Путь '.$rubricPath.$path.' не найден:'. $e->getMessage());
        }


        //$generator = new \Symfony\Component\Routing\Generator\UrlGenerator($moduleObj->getRoutes(), new RequestContext($rubricPath));
        //echo "---->".$generator->generate('index');



        print_r($matchResult);

        $controller = $matchResult['_controller'];
        print '<br>Форвардим на '. $controller;

        return $this->forward($matchResult['_controller'], $matchResult );



    }
}
