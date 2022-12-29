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

namespace MonsieurBiz\SyliusCmsPagePlugin\Context;

use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class LastChanceLocaleContext implements LocaleContextInterface
{
    private RequestStack $requestStack;

    private LocaleProviderInterface $localeProvider;

    public function __construct(
        RequestStack $requestStack,
        LocaleProviderInterface $localeProvider
    ) {
        $this->requestStack = $requestStack;
        $this->localeProvider = $localeProvider;
    }

    public function getLocaleCode(): string
    {
        if (null === $request = $this->requestStack->getMainRequest()) {
            throw new LocaleNotFoundException('Main request not found, therefore no locale found…');
        }
        $pathInfo = $request->getPathInfo();
        $availableLocaleCodes = $this->localeProvider->getAvailableLocalesCodes();
        $parts = explode('/', trim($pathInfo, '/'));
        foreach ($parts as $part) {
            if (\in_array($part, $availableLocaleCodes, true)) {
                return $part;
            }
        }

        return $this->localeProvider->getDefaultLocaleCode();
    }
}
