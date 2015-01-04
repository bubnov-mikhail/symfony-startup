<?php

namespace App\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    /**
     * Builds top menu
     *
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return MenuItem
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'nav navbar-nav']);

        $translator = $this->container->get('translator');
        $securityContext = $this->container->get('security.context');

        if ($securityContext->isGranted('ROLE_USER')) {
            //some childs for registered users
        }

        //$menu->addChild($translator->trans('navigation.news', [], 'navigation'), ['route' => 'news']);
        //$menu->addChild($translator->trans('navigation.help', [], 'navigation'), ['route' => 'help']);

        return $menu;
    }
}
