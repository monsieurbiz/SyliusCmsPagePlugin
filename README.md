<p align="center">
    <a href="https://monsieurbiz.com" target="_blank">
        <img src="https://monsieurbiz.com/logo.png" width="250px" alt="Monsieur Biz logo" />
    </a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="https://monsieurbiz.com/agence-web-experte-sylius" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" width="200px" alt="Sylius logo" />
    </a>
    <br/>
    <img src="https://monsieurbiz.com/assets/images/sylius_badge_extension-artisan.png" width="100" alt="Monsieur Biz is a Sylius Extension Artisan partner">
</p>

<h1 align="center">CMS Pages</h1>

[![CMS Page Plugin license](https://img.shields.io/github/license/monsieurbiz/SyliusCmsPagePlugin?public)](https://github.com/monsieurbiz/SyliusCmsPagePlugin/blob/master/LICENSE)
[![Build Status](https://img.shields.io/github/workflow/status/monsieurbiz/SyliusCmsPagePlugin/PHP%20Composer)](https://github.com/monsieurbiz/SyliusCmsPagePlugin/actions?query=workflow%3A%22PHP+Composer%22)
[![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/monsieurbiz/SyliusCmsPagePlugin/master)](https://scrutinizer-ci.com/g/monsieurbiz/SyliusCmsPagePlugin/?branch=master)

This plugins allows you to add manage CMS pages using the Rich Editor.

If you want to know more about our editor, see the [Rich Editor Repository](https://github.com/monsieurbiz/SyliusRichEditorPlugin)

![Example of CMS page creation](screenshots/demo.gif)

## Installation

```bash
composer require monsieurbiz/sylius-cms-page-plugin
```

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

monsieurbiz_cms_page_shop:
    resource: "@MonsieurBizSyliusCmsPagePlugin/Resources/config/routing/shop.yaml"
    prefix: /{_locale}
```

### Migrations

Make a doctrine migration diff : 

```php
bin/console doctrine:migrations:diff
```

Then run it : 

```php
bin/console doctrine:migrations:migrate
```

## Example of complete CMS Page

### Admin form with preview

![Admin full form](screenshots/full_back.jpg)

### Front display

![Front full display](screenshots/full_front.jpg)

## Create custom elements

You can customize and create custom elements in your page.  
In order to do that, you can check the [Rich Editor custom element creation](https://github.com/monsieurbiz/SyliusRichEditorPlugin#create-your-own-elements)

## SEO Friendly

You can define for every page the meta title, meta description and meta 
keywords.

## Contributing

You can open an issue or a Pull Request if you want! 😘  
Thank you!
