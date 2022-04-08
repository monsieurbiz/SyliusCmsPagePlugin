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
final class Version20210311101300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monsieurbiz_cms_page (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, enabled TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_cms_page_channels (page_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_C7095B0AC4663E4 (page_id), INDEX IDX_C7095B0A72F5A1AA (channel_id), PRIMARY KEY(page_id, channel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_cms_page_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, metaTitle VARCHAR(255) DEFAULT NULL, metaKeywords VARCHAR(255) DEFAULT NULL, metaDescription LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_2E2C3B072C2AC5D3 (translatable_id), UNIQUE INDEX monsieurbiz_cms_page_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monsieurbiz_cms_page_channels ADD CONSTRAINT FK_C7095B0AC4663E4 FOREIGN KEY (page_id) REFERENCES monsieurbiz_cms_page (id)');
        $this->addSql('ALTER TABLE monsieurbiz_cms_page_channels ADD CONSTRAINT FK_C7095B0A72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id)');
        $this->addSql('ALTER TABLE monsieurbiz_cms_page_translation ADD CONSTRAINT FK_2E2C3B072C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES monsieurbiz_cms_page (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_cms_page_channels DROP FOREIGN KEY FK_C7095B0AC4663E4');
        $this->addSql('ALTER TABLE monsieurbiz_cms_page_translation DROP FOREIGN KEY FK_2E2C3B072C2AC5D3');
        $this->addSql('DROP TABLE monsieurbiz_cms_page');
        $this->addSql('DROP TABLE monsieurbiz_cms_page_channels');
        $this->addSql('DROP TABLE monsieurbiz_cms_page_translation');
    }
}
