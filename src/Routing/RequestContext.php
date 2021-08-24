<?php

/*
 * This file is part of Monsieur Biz' Cms Page plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Routing;

use Exception;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RequestContext as BaseRequestContext;

final class RequestContext extends BaseRequestContext
{
    /**
     * @var BaseRequestContext
     */
    private $decorated;

    /**
     * @var PageSlugConditionChecker
     */
    private $pageSlugConditionChecker;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /** @var RequestStack */
    private $requestStack;

    /**
     * RequestContext constructor.
     *
     * @param BaseRequestContext $decorated
     * @param PageSlugConditionChecker $pageSlugConditionChecker
     * @param LocaleProviderInterface $localeProvider
     * @param RequestStack $requestStack
     */
    public function __construct(
        BaseRequestContext $decorated,
        PageSlugConditionChecker $pageSlugConditionChecker,
        LocaleProviderInterface $localeProvider,
        RequestStack $requestStack
    ) {
        parent::__construct(
            $decorated->getBaseUrl(),
            $decorated->getMethod(),
            $decorated->getHost(),
            $decorated->getScheme(),
            $decorated->getHttpPort(),
            $decorated->getHttpsPort(),
            $decorated->getPathInfo(),
            $decorated->getQueryString()
        );
        $this->decorated = $decorated;
        $this->pageSlugConditionChecker = $pageSlugConditionChecker;
        $this->localeProvider = $localeProvider;
        $this->requestStack = $requestStack;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function checkPageSlug(Request $request): bool
    {
        $mainRequest = $this->requestStack->getMainRequest();
        if ($mainRequest !== $request) {
            return false;
        }
        $locale = $this->getLocale($request->getPathInfo());
        $slug = $this->getSlug($request->getPathInfo(), $locale);

        return $this->pageSlugConditionChecker->isPageSlug($locale, $slug);
    }

    /**
     * Retrieve locale from URL because is not set yet on the request.
     *
     * @param string $pathInfo
     *
     * @return string
     */
    private function getLocale(string $pathInfo): string
    {
        $availableLocaleCodes = $this->localeProvider->getAvailableLocalesCodes();
        $parts = explode('/', trim($pathInfo, '/'));
        foreach ($parts as $part) {
            if (\in_array($part, $availableLocaleCodes, true)) {
                return $part;
            }
        }

        return $this->localeProvider->getDefaultLocaleCode();
    }

    /**
     * @param string $pathInfo
     * @param string $locale
     *
     * @return string
     */
    private function getSlug(string $pathInfo, string $locale): string
    {
        $pathInfo = ltrim($pathInfo, '/');

        if (false === strpos($pathInfo, $locale)) {
            return $pathInfo;
        }

        return str_replace(sprintf('%s/', $locale), '', $pathInfo);
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $callback = [$this->decorated, $name];
        if (\is_callable($callback)) {
            return \call_user_func($callback, $arguments);
        }

        throw new Exception(sprintf('Method %s not found for class "%s"', $name, \get_class($this->decorated)));
    }
}
