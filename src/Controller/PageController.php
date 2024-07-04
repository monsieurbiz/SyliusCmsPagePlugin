<?php

/*
 * This file is part of Monsieur Biz' Cms Page plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Controller;

use MonsieurBiz\SyliusRichEditorPlugin\Switcher\SwitchAdminLocaleInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends ResourceController
{
    public function previewAction(Request $request, SwitchAdminLocaleInterface $switchAdminLocale): Response
    {
        // Switch the locale of the preview to the default locale of the channel to not take the locale of the admin
        if ($locale = $request->getDefaultLocale()) {
            $switchAdminLocale->switchLocale($locale);
        }
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);
        $resource = $this->findOr404($configuration);

        /** @var string $template */
        $template = $configuration->getRequest()->get('template');

        return $this->render($template, [
            'configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $resource,
            $this->metadata->getName() => $resource,
        ]);
    }
}
