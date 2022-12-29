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

namespace MonsieurBiz\SyliusCmsPagePlugin\Routing;

use Exception;
use Sylius\Component\Locale\Context\LocaleContextInterface;
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
     * @var LocaleContextInterface
     */
    private $localeContext;

    private RequestStack $requestStack;

    /**
     * RequestContext constructor.
     */
    public function __construct(
        BaseRequestContext $decorated,
        PageSlugConditionChecker $pageSlugConditionChecker,
        LocaleContextInterface $localeContext,
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
        $this->localeContext = $localeContext;
        $this->requestStack = $requestStack;
    }

    public function checkPageSlug(Request $request): bool
    {
        if ($request !== $this->requestStack->getMainRequest()) {
            return false;
        }

        return $this->pageSlugConditionChecker->isPageSlug($this->prepareSlug($request->getPathInfo()));
    }

    private function prepareSlug(string $slug): string
    {
        $slug = ltrim($slug, '/');
        $localeCode = $this->localeContext->getLocaleCode();

        if (false === strpos($slug, $localeCode)) {
            return $slug;
        }

        return str_replace(sprintf('%s/', $localeCode), '', $slug);
    }

    /**
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
