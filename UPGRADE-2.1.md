# Upgrade from 2.0.X 2.1.X

In the 2.1 we changed the route definition for the route `monsieurbiz_cms_page_show`.

Update the file `config/routes/monsieurbiz_sylius_cms_page_plugin.yaml` with the new condition for `monsieurbiz_cms_page_show`:

```yaml
monsieurbiz_cms_page_admin:
    resource: "@MonsieurBizSyliusCmsPagePlugin/Resources/config/routing/admin.yaml"
    prefix: /%sylius_admin.path_name%

# Show page
monsieurbiz_cms_page_show:
    path: /{_locale}/{slug}
    methods: [GET]
    requirements:
        slug: .+
        _locale: ^[A-Za-z]{2,4}(_([A-Za-z]{4}|[0-9]{3}))?(_([A-Za-z]{2}|[0-9]{3}))?$
    defaults:
        _controller: monsieurbiz_cms_page.controller.page::showAction
        _sylius:
            template: "@MonsieurBizSyliusCmsPagePlugin/shop/page/show.html.twig"
            repository:
                method: findOneEnabledAndPublishedBySlugAndChannelCode
                arguments:
                    - $slug
                    - "expr:service('sylius.context.locale').getLocaleCode()"
                    - "expr:service('sylius.context.channel').getChannel().getCode()"
                    - "expr:service('monsieurbiz.cms_page.datetime_provider').now()"
    condition: "not(context.getPathInfo() matches '`^%sylius.security.api_route%`') and service('monsieurbiz.cms_page.route_checker').checkPageSlug(request)"
```

We now use the PageSlugConditionChecker directly as a #[AsRoutingConditionService()]
Also, changing the condition allows using the Symfony PHP attribute #[AsRoutingConditionService()] for other classes.
