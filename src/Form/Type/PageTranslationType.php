<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PageTranslationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.title',
            ])
            ->add('slug', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.slug',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'monsieurbiz_cms_page.ui.content',
            ])
            ->add('metaTitle', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.meta_title',
            ])
            ->add('metaDescription', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.meta_description',
            ])
            ->add('metaKeywords', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.meta_keywords',
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
