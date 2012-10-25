<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\CoreBundle\Kernel;

class RegisterBundles
{

    static function register(\Symfony\Component\HttpKernel\Kernel $kernel, $currentBundles = array(), $options = array())
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \JMS\AopBundle\JMSAopBundle(),
            new \JMS\DiExtraBundle\JMSDiExtraBundle($kernel),
            new \JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),


            new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),



            new \Sonata\AdminBundle\SonataAdminBundle(),
            new \Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new \Sonata\jQueryBundle\SonatajQueryBundle(),
            new \Sonata\BlockBundle\SonataBlockBundle(),
            new \Sonata\CacheBundle\SonataCacheBundle(),
            new \Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),



            new \Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new \FOS\UserBundle\FOSUserBundle(),
            new \Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),




            new \Iphp\CoreBundle\IphpCoreBundle(),
            new \Iphp\ContentBundle\IphpContentBundle(),


        );


        $optionBundles = array(
            '\\Application\\Iphp\\CoreBundle\\ApplicationIphpCoreBundle',
            '\\Application\\Iphp\\ContentBundle\\ApplicationIphpContentBundle',
        );

        foreach ($optionBundles as $bundleClass) {
            if (class_exists($bundleClass))
            {
                $bundles[] = new $bundleClass();
            }


        }

        return $bundles;

    }

}
