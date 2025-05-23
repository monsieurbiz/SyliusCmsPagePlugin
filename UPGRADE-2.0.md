# Upgrade from 1.X to 2.X

In the 2.x we changed the route definition for the shop route.

The file `@MonsieurBizSyliusCmsPagePlugin/Resources/config/routing/shop.yaml` does not exist anymore.

Update the file `config/routes/monsieurbiz_sylius_cms_page_plugin.yaml` : 

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
    condition: "not(context.getPathInfo() matches '`^%sylius.security.api_route%`') and context.checkPageSlug(request)"
```

We upgraded also the [Rich Editor to the 3.0 version](https://github.com/monsieurbiz/SyliusRichEditorPlugin/blob/3.x/UPGRADE-3.0.md).  
