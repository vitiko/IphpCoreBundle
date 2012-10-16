<?
//НЕ ВКЛЮЧЕН!

namespace Iphp\CoreBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;


class UpdatedByListener
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();


       // var_dump ($entity);

        if (method_exists($entity, 'setUpdatedBy')) {
            $currentUser = $this->container->get('security.context')->getToken()->getUser();
            $entity->setUpdatedBy($currentUser);
        }
    }
}
