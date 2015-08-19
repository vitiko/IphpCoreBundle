<?php
/**
 * @author Vitiko <vitiko@mail.ru>
 */

namespace Iphp\CoreBundle\Kernel;

class RegisterBundles
{

    static function register(\Symfony\Component\HttpKernel\Kernel $kernel, $currentBundles = array(), $options = array())
    {
        $bundles = static::getRequiredBundles($kernel, $currentBundles, $options);

        foreach (static::getOptionBundles($kernel, $currentBundles, $options) as $bundleClass)
            if (class_exists($bundleClass))   $bundles[] = new $bundleClass();

        return $bundles;
    }


    protected static function getRequiredBundles(\Symfony\Component\HttpKernel\Kernel $kernel, $currentBundles = array(), $options = array())
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            new \Iphp\CoreBundle\IphpCoreBundle(),
            new \Iphp\TreeBundle\IphpTreeBundle(),


            new \FOS\UserBundle\FOSUserBundle(),


            new \Sonata\AdminBundle\SonataAdminBundle(),
            new \Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new \Sonata\BlockBundle\SonataBlockBundle(),
            new \Sonata\CacheBundle\SonataCacheBundle(),
            new \Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new \Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new \Sonata\IntlBundle\SonataIntlBundle(),

            new \Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new \Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        );
    }


    protected static function getOptionBundles(\Symfony\Component\HttpKernel\Kernel $kernel, $currentBundles = array(), $options = array())
    {
        return array(

            '\\Sonata\\CoreBundle\\SonataCoreBundle',

            '\\Iphp\\ContentBundle\\IphpContentBundle',
            '\\Iphp\\FileStoreBundle\\IphpFileStoreBundle',

            '\\Application\\Iphp\\CoreBundle\\ApplicationIphpCoreBundle',
            '\\Application\\Iphp\\ContentBundle\\ApplicationIphpContentBundle',

            '\\Application\\Sonata\\UserBundle\\ApplicationSonataUserBundle',





            '\\Sonata\\MediaBundle\\SonataMediaBundle',
            '\\Application\\Sonata\\MediaBundle\\ApplicationSonataMediaBundle',
            '\\JMS\\SecurityExtraBundle\\JMSSecurityExtraBundle',
        );
    }
}
