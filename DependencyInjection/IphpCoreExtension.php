<?php



namespace Iphp\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

//use Symfony\Component\DependencyInjection\Definition;

use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;

class IphpCoreExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('twig.xml');
        $loader->load('services.xml');

        $loader->load('admin.xml');
        $loader->load('front.xml');
        $loader->load('block.xml');
        $this->registerDoctrineMapping($config);

        $this->setContainerParameters ($container, $config);
    }


    protected function setContainerParameters(ContainerBuilder $container, array $config)
    {
        if (!$container->has('iphp.web_dir') || !$container->getParameter('iphp.web_dir'))
            $container->setParameter('iphp.web_dir',
                str_replace('\\', '/', realpath($container->getParameter('kernel.root_dir') . '/../web/')));

        $container->setParameter('iphp.user.class', $config['class']['user']);
        $container->setParameter('iphp.usergroup.class', $config['class']['usergroup']);
    }


    /**
     * @param array $config
     * @return void
     */
    public function registerDoctrineMapping(array $config)
    {
        // print 'Extends!';

        if (!class_exists($config['class']['rubric'])) {
            return;
        }

        $collector = DoctrineCollector::getInstance();


        $collector->addAssociation($config['class']['rubric'], 'mapOneToMany', array(
            'fieldName' => 'children',
            'targetEntity' => $config['class']['rubric'],
            'cascade' => array(
                'remove',
                'persist',
                'refresh',
                'merge',
                'detach',
            ),
            'mappedBy' => 'parent',
            'orphanRemoval' => false,
            'orderBy' => array(
                'left' => 'ASC',
            ),
        ));


        //ИНДЕКСЫ
        $collector->addIndex($config['class']['rubric'], 'lftrgt', array('lft', 'rgt'));
        $collector->addIndex($config['class']['rubric'], 'lvl', array('lvl'));
        $collector->addIndex($config['class']['rubric'], 'created_at', array('created_at'));
        $collector->addIndex($config['class']['rubric'], 'updated_at', array('updated_at'));
        $collector->addIndex($config['class']['rubric'], 'full_path', array('full_path'));
        /*
        $collector->addAssociation($config['class']['rubric'], 'mapManyToOne', array(
            'fieldName' => 'parent',
            'targetEntity' => $config['class']['rubric'],
            'cascade' => array(
            ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' => array(
                array(
                    'name' => 'parent_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'SET NULL',
                ),
            ),
            'orphanRemoval' => false,
        ));




        <many-to-one field="parent" target-entity="Application\Iphp\CoreBundle\Entity\Rubric">
                    <join-column name="parent_id" referenced-column-name="id" on-delete="SET NULL"/>
                    <gedmo:tree-parent/>
                </many-to-one>

      */


        $collector->addAssociation($config['class']['rubric'], 'mapOneToMany', array(
            'fieldName' => 'blocks',
            'targetEntity' => $config['class']['block'],
            'cascade' => array(
                'remove',
                'persist',
                'refresh',
                'merge',
                'detach',
            ),
            'mappedBy' => 'rubric',
            'orphanRemoval' => false,
            'orderBy' => array(
                'position' => 'ASC',
            ),
        ));


        $collector->addAssociation($config['class']['block'], 'mapOneToMany', array(
            'fieldName' => 'children',
            'targetEntity' => $config['class']['block'],
            'cascade' => array(
                'remove',
                'persist',
            ),
            'mappedBy' => 'parent',
            'orphanRemoval' => true,
            'orderBy' => array(
                'position' => 'ASC',
            ),
        ));

        $collector->addAssociation($config['class']['block'], 'mapManyToOne', array(
            'fieldName' => 'parent',
            'targetEntity' => $config['class']['block'],
            'cascade' => array(),
            'mappedBy' => NULL,
            'inversedBy' => 'children',
            'joinColumns' => array(
                array(
                    'name' => 'parent_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'CASCADE',
                ),
            ),
            'orphanRemoval' => false,
        ));

        $collector->addAssociation($config['class']['block'], 'mapManyToOne', array(
            'fieldName' => 'rubric',
            'targetEntity' => $config['class']['rubric'],
            'cascade' => array(
                'persist',
            ),
            'mappedBy' => NULL,
            'inversedBy' => 'blocks',
            'joinColumns' => array(
                array(
                    'name' => 'rubric_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'CASCADE',
                ),
            ),
            'orphanRemoval' => false,
        ));


        if ($config['class']['user'] && class_exists($config['class']['user'])) {

            $collector->addAssociation($config['class']['rubric'], 'mapManyToOne', array(
                'fieldName' => 'createdBy',
                'targetEntity' => $config['class']['user'],
                'cascade' => array(
                    'persist',
                ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' => array(
                    array(
                        'name' => 'createdby_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'SET NULL',
                    ),
                ),
                'orphanRemoval' => false,
            ));


            $collector->addAssociation($config['class']['rubric'], 'mapManyToOne', array(
                'fieldName' => 'updatedBy',
                'targetEntity' => $config['class']['user'],
                'cascade' => array(
                    'persist',
                ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' => array(
                    array(
                        'name' => 'updatedby_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'SET NULL',
                    ),
                ),
                'orphanRemoval' => false,
            ));
        }

    }
}