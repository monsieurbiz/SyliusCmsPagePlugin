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

namespace MonsieurBiz\SyliusCmsPagePlugin\Controller\Admin\Page;

use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PageSlugController
{
    /** @var SlugGeneratorInterface */
    private $slugGenerator;

    /**
     * PageSlugController constructor.
     *
     * @param SlugGeneratorInterface $slugGenerator
     */
    public function __construct(SlugGeneratorInterface $slugGenerator)
    {
        $this->slugGenerator = $slugGenerator;
    }

    /**
     * Generate slug and return JSON.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function generateAction(Request $request): JsonResponse
    {
        $name = (string) $request->query->get('title');

        return new JsonResponse([
            'slug' => $this->slugGenerator->generate($name),
        ]);
    }
}
