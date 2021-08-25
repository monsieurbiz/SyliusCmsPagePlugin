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

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BlockType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('title', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.form.title',
            ])
            ->add('channels', ChannelChoiceType::class, [
                'label' => 'monsieurbiz_cms_page.ui.form.channels',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => BlockTranslationType::class,
            ])
            ->add('code', TextType::class, [
                'label' => 'monsieurbiz_cms_page.ui.form.code',
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'monsieurbiz_cms_page.ui.form.enabled',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'monsieurbiz_cms_block';
    }
}
