<?php
declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Listener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{

    public function addAdminMenuItem(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        if (!$content = $menu->getChild('monsieurbiz-cms')) {
            $content = $menu
                ->addChild('monsieurbiz-cms')
                ->setLabel('monsieurbiz_cms_page.ui.cms_content')
            ;
        }

        $content->addChild('monsieurbiz-cms-page', ['route' => 'monsieurbiz_cms_page_admin_page_index'])
            ->setLabel('monsieurbiz_cms_page.ui.pages')
            ->setLabelAttribute('icon', 'file alternate')
        ;
    }

}
