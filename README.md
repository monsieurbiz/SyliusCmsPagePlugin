[![Banner of Sylius CMS Pages plugin](docs/images/banner.jpg)](https://monsieurbiz.com/agence-web-experte-sylius)

<h1 align="center">Sylius CMS Pages</h1>

[![CMS Page Plugin license](https://img.shields.io/github/license/monsieurbiz/SyliusCmsPagePlugin?public)](https://github.com/monsieurbiz/SyliusCmsPagePlugin/blob/master/LICENSE.txt)
[![Tests Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusCmsPagePlugin/tests.yaml?branch=master&logo=github)](https://github.com/monsieurbiz/SyliusCmsPagePlugin/actions?query=workflow%3ATests)
[![Recipe Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusCmsPagePlugin/recipe.yaml?branch=master&label=recipes&logo=github)](https://github.com/monsieurbiz/SyliusCmsPagePlugin/actions?query=workflow%3ASecurity)
[![Security Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusCmsPagePlugin/security.yaml?branch=master&label=security&logo=github)](https://github.com/monsieurbiz/SyliusCmsPagePlugin/actions?query=workflow%3ASecurity)

This plugins allows you to add manage CMS pages using the Rich Editor and the Media Manager.

If you want to know more about our editor, see the [Rich Editor Plugin](https://github.com/monsieurbiz/SyliusRichEditorPlugin)  
If you want to know more about our editor, see the [Media Manager Plugin](https://github.com/monsieurbiz/SyliusMediaManagerPlugin)

![Example of CMS Page display](screenshots/front-example.png)

## Compatibility

| Sylius Version | PHP Version     |
|----------------|-----------------|
| 2.0            | 8.2 - 8.3       |

ℹ️ For Sylius 1.x, see our [1.x branch](https://github.com/monsieurbiz/SyliusCmsPagePlugin/tree/1.x) and all 1.x releases.

## Installation

If you want to use our recipes, you can configure your composer.json by running:

```bash
composer config --no-plugins --json extra.symfony.endpoint '["https://api.github.com/repos/monsieurbiz/symfony-recipes/contents/index.json?ref=flex/master","flex://defaults"]'
```

```bash
composer require monsieurbiz/sylius-cms-page-plugin
```

If you do not use the recipes : 

Change your `config/bundles.php` file to add the line for the plugin : 

```php
<?php

return [
    //..
    MonsieurBiz\SyliusCmsPagePlugin\MonsieurBizSyliusCmsPagePlugin::class => ['all' => true],
];
```

Then create the config file in `config/packages/monsieurbiz_sylius_cms_page_plugin.yaml` :

```yaml
imports:
    - { resource: "@MonsieurBizSyliusCmsPagePlugin/Resources/config/config.yaml" }
```

Finally import the routes in `config/routes/monsieurbiz_sylius_cms_page_plugin.yaml` : 

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

### Migrations

First, please run legacy-versioned migrations by using command :

```bash
bin/console doctrine:migrations:migrate
```

After migration, please create a new diff migration :

```php
bin/console doctrine:migrations:diff
```

Then run it (if any) : 

```php
bin/console doctrine:migrations:migrate
```

## Example of complete CMS Page

### Admin grid

![Admin grid](screenshots/admin-grid.png)

### Admin form

![Admin form](screenshots/admin-form.png)

## Create custom elements

You can customize and create custom elements in your page.  
In order to do that, you can check the [Rich Editor custom element creation](https://github.com/monsieurbiz/SyliusRichEditorPlugin#create-your-own-elements)

## SEO Friendly

You can define for every page the meta title, meta description, meta 
keywords and meta image.

## Troubleshooting

### Locale not found

We've added a new LocaleContext (`LastChanceLocaleContext`) because the locale isn't set in the request when the
condition on the route is applied.  
Therefore, if you still have an issue with multiple locales in your project, you may need to add another LocaleContext
in order to find out your locale. The system will take care of the rest.

## Contributing

You can open an issue or a Pull Request if you want! 😘  
Thank you!
