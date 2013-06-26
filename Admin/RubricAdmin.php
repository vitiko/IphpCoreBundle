<?php


namespace Iphp\CoreBundle\Admin;


use Iphp\TreeBundle\Admin\TreeAdmin;
use Iphp\CoreBundle\Model\RubricInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;


use Knp\Menu\ItemInterface as MenuItemInterface;


class RubricAdmin extends TreeAdmin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var \Iphp\CoreBundle\Manager\RubricManager
     */
    protected $rubricManager;


    public function getNewInstance()
    {
        return parent::getNewInstance()->setStatus(true);
    }

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
                ->add('status')
                ->add('title')
                ->add('abstract');

    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $rubric = $this->getSubject();
        $formMapper->with('Base params');


        $this->addMenuRelatedFields($rubric,$formMapper);


        $formMapper->add('title');


        if (!$rubric->isRoot())
            $formMapper->add('parent', 'rubricchoice', array('label' => 'Parent Rubric'))
                    ->add('path',  'slug_text', array(
                           'source_field' => 'title',
                           'usesource_title' => 'Использовать название рубрики'
                     ))
                    ->setHelps(array('path' => 'На основе директорий строится адресация разделов сайта'));






        $formMapper->add('abstract', null, array('label' => 'Анонс'))
                ->add('redirectUrl', null, array('label' => 'URL редирект'))
                ->add('controllerName'

            /*, null, array('label' => 'Название контроллера или бандла'))
            ->add('module'*/
            , 'modulechoice',
            array('label' => 'Выберите модуль',
                'required' => false,
                'empty_value' => ' ',
            )
        )
                ->end()// ->with('Options', array('collapsed' => true))
            //  ->add('commentsCloseAt')
            //  ->add('commentsEnabled', null, array('required' => false))
            // ->add('commentsDefaultStatus', 'choice', array('choices' => Comment::getStatusList(), 'expanded' => true))
            //   ->end();

        ;


        $this->configureModuleFormFields($rubric, $formMapper);
    }


    protected  function addMenuRelatedFields( RubricInterface $rubric, FormMapper $formMapper)
    {
        if (!$rubric->isRoot())
            $formMapper->add('status', 'checkbox', array('required' => false, 'label' => 'Показывать в меню'));
    }


    protected function configureModuleFormFields(RubricInterface  $rubric, FormMapper $formMapper)
    {
        $module = $this->configurationPool->getContainer()->get('iphp.core.module.manager')
                ->getModuleFromRubric($rubric);

        if ($module) {
            $moduleAdminExtension = $module->getAdminExtension();
            if ($moduleAdminExtension) $moduleAdminExtension->configureFormFields($formMapper);
        }
    }


    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        // ->add('status', null, array('label' => 'Показывать', 'width' => '30px'))
                ->addIdentifier('title', null, array(
            'label' => 'Заголовок',
            'template' => 'IphpTreeBundle:CRUD:base_treelist_field.html.twig'))
                ->add('fullPath', null, array('label' => 'Путь', 'width' => '300px',
            'template' => 'IphpTreeBundle:CRUD:base_treelist_field.html.twig'))/* ->add('controllerName', null, array('label' => 'Контроллер',  'width' => '100px'))*/
        ;

    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('title');

    }


    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');


        $menu->addChild(
            $this->trans('sidemenu.link_list_blocks'),
            array('uri' => $admin->generateUrl('iphp.core.admin.block.list', array('id' => $id)))
        );


        $menu->addChild(
            $this->trans('view_rubric'),
            array('uri' => $this->getSubject()->getFullPath(), 'target' => '_blank')
        );

    }


    public function postUpdate($object)
    {
        $this->rubricManager->clearCache();
    }

    public function postPersist($object)
    {
        $this->rubricManager->clearCache();
    }


    public function setUserManager($userManager)
    {
        $this->userManager = $userManager;
    }

    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * @param \Iphp\CoreBundle\Manager\RubricManager $rubricManager
     * @return RubricAdmin
     */
    public function setRubricManager($rubricManager)
    {
        $this->rubricManager = $rubricManager;
        return $this;
    }

    /**
     * @return \Iphp\CoreBundle\Manager\RubricManager
     */
    public function getRubricManager()
    {
        return $this->rubricManager;
    }

}
