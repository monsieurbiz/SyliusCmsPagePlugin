<?php

/*
 * This file is part of Monsieur Biz' Cms Page plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Context\Channel\RequestBased;

use Sylius\Component\Channel\Context\RequestBased\RequestResolverInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;

#[AutoconfigureTag('sylius.context.channel.request_based.resolver')]
final class HostnameAndPortBasedRequestResolver implements RequestResolverInterface
{
    public function __construct(private ChannelRepositoryInterface $channelRepository)
    {
    }

    public function findChannel(Request $request): ?ChannelInterface
    {
        return $this->channelRepository->findOneEnabledByHostname($request->getHost() . ':' . $request->getPort());
    }
}
