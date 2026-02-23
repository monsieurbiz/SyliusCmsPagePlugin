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

namespace MonsieurBiz\SyliusCmsPagePlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add noindex and nofollow columns for robots meta tag control.
 */
final class Version20260223130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add noindex and nofollow columns to monsieurbiz_cms_page';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE monsieurbiz_cms_page ADD noindex TINYINT(1) DEFAULT 0 NOT NULL, ADD nofollow TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE monsieurbiz_cms_page DROP noindex, DROP nofollow');
    }
}
