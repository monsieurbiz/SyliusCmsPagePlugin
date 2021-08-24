<?php

namespace MonsieurBiz\SyliusCmsPagePlugin\Repository;

interface BlockRepositoryInterface
{
    public function findOneEnabledByBlockCodeAndChannelCode(string $blockCode, string $localeCode, string $channelCode);
}
