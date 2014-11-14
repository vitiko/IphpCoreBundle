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



            new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),



            new \Sonata\AdminBundle\SonataAdminBundle(),
            new \Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            //new \Sonata\jQueryBundle\SonatajQueryBundle(),
            new \Sonata\BlockBundle\SonataBlockBundle(),
            new \Sonata\CacheBundle\SonataCacheBundle(),
            new \Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new \Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),



            new \Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new \FOS\UserBundle\FOSUserBundle(),
            new \Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),



            new \Iphp\CoreBundle\IphpCoreBundle(),

            new \Iphp\TreeBundle\IphpTreeBundle()

        );


        $optionBundles = array(


           '\Sonata\CoreBundle\SonataCoreBundle',

           '\\JMS\\AopBundle\\JMSAopBundle',
         //   '\\JMS\DiExtraBundle\\JMSDiExtraBundle($kernel),
           '\\JMS\\SecurityExtraBundle\\JMSSecurityExtraBundle',


            '\\Iphp\\ContentBundle\\IphpContentBundle',
            '\\Iphp\\FileStoreBundle\\IphpFileStoreBundle',

            '\\Application\\Iphp\\CoreBundle\\ApplicationIphpCoreBundle',
            '\\Application\\Iphp\\ContentBundle\\ApplicationIphpContentBundle',

            '\\Application\\Sonata\\UserBundle\\ApplicationSonataUserBundle'
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
