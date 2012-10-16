<?php
/**
 * Created by Vitiko
 * Date: 02.08.12
 * Time: 10:26
 */

namespace Iphp\CoreBundle\Routing;

use Iphp\CoreBundle\Model\Rubric;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class EntityRouter
{

    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }


    public function generateEntityActionPath($entity, $action = 'view', $params = array())
    {
        if ($action == 'view' && !$params) {
            $params = array('id' => method_exists($entity, 'getSlug') ? $entity->getSlug() : $entity->getId());
        }

        $routeName = $this->routeNameForEntityAction($entity, $action);
        $path = 'route-name-not-found-' . $routeName;

        try {
            $path = $this->router->generate($routeName, $params);
        } catch (\Exception $e) {
        }

        return $path;
    }

    public function routeNameForEntityAction($entity, $action, Rubric $rubric = null)
    {

        if (is_object($entity)) {
            $entityPart = str_replace('\\', '', str_replace('Entity\\', '', get_class($entity)));
        } else {
            //Todo: Хак, нужно использовать kernel->getBundle(..)->getNamespace() но доступа к kernel пока нет
            //list ($bundleName, $entityName) = explode (':',$entity);

            $entityPart = str_replace(':', '', $entity);
        }

        return $entityPart . '_' . lcfirst($action);
    }
}
