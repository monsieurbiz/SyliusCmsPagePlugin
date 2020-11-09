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

namespace MonsieurBiz\SyliusCmsPagePlugin\Form\Type;

use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\RichEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PageTranslationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.form.title',
                'constraints' => [
                    new Assert\NotBlank([]),
                ],
            ])
            ->add('slug', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.form.slug',
                'constraints' => [
                    new Assert\NotBlank([]),
                ],
            ])
            ->add('content', RichEditorType::class, [
                'label' => 'monsieurbiz_cms_page.ui.form.content',
                'constraints' => [
                    new Assert\NotBlank([]),
                ],
            ])
            ->add('metaTitle', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.form.meta_title',
                'constraints' => [
                    new Assert\NotBlank([]),
                ],
            ])
            ->add('metaDescription', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.form.meta_description',
                'constraints' => [
                    new Assert\NotBlank([]),
                ],
            ])
            ->add('metaKeywords', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.form.meta_keywords',
                'constraints' => [
                    new Assert\NotBlank([]),
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'monsieurbiz_cms_page_translation';
    }
}
