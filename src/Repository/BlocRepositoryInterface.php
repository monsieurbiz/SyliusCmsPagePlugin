<?php

namespace MonsieurBiz\SyliusCmsPagePlugin\Repository;

interface BlocRepositoryInterface
{
    public function findOneEnabledByBlockCodeAndChannelCode(string $blockCode, string $localeCode, string $channelCode);
}
