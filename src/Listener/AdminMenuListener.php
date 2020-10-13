<?php
declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Listener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{

    public function addAdminMenuItem(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        if (!$content = $menu->getChild('mbiz-cms')) {
            $content = $menu
                ->addChild('mbiz-cms')
                ->setLabel('monsieurbiz_cms_page.ui.cms_content')
            ;
        }

        $content->addChild('mbiz-cms-page', ['route' => 'monsieurbiz_cms_page_admin_page_index'])
            ->setLabel('monsieurbiz_cms_page.ui.pages')
            ->setLabelAttribute('icon', 'file alternate')
        ;
    }

}
