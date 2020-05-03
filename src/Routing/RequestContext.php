<?php
declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Routing;

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
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->decorated, $name], $arguments);
    }

}
