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
use Sylius\Component\Locale\Context\LocaleContextInterface;
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

    /**
     * RequestContext constructor.
     *
     * @param BaseRequestContext $decorated
     * @param PageSlugConditionChecker $pageSlugConditionChecker
     * @param LocaleContextInterface $localeContext
     */
    public function __construct(
        BaseRequestContext $decorated,
        PageSlugConditionChecker $pageSlugConditionChecker,
        LocaleContextInterface $localeContext
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
    }

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function checkPageSlug(string $slug): bool
    {
        return $this->pageSlugConditionChecker->isPageSlug($this->prepareSlug($slug));
    }

    /**
     * @param string $slug
     *
     * @return string
     */
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
