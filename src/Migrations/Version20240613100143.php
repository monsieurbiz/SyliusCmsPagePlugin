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
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613100143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_cms_page_translation CHANGE metaTitle meta_title VARCHAR(255) DEFAULT NULL, CHANGE metaKeywords meta_keywords VARCHAR(255) DEFAULT NULL, CHANGE metaDescription meta_description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_cms_page_translation CHANGE meta_title metaTitle VARCHAR(255) DEFAULT NULL, CHANGE meta_keywords metaKeywords VARCHAR(255) DEFAULT NULL, CHANGE meta_description metaDescription LONGTEXT DEFAULT NULL');
    }
}
