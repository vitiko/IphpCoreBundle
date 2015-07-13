<?php

namespace Iphp\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin as BaseAdmin;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Iphp\CoreBundle\Admin\Traits\EntityInformationBlock;

class Admin extends BaseAdmin
{

    use EntityInformationBlock;





}