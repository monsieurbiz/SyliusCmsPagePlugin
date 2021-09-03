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

namespace MonsieurBiz\SyliusCmsPagePlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210903080417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monsieurbiz_cms_block (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, enabled TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_cms_block_channels (block_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_50664388E9ED820C (block_id), INDEX IDX_5066438872F5A1AA (channel_id), PRIMARY KEY(block_id, channel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_cms_block_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, content LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_938B10EE2C2AC5D3 (translatable_id), UNIQUE INDEX monsieurbiz_cms_block_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monsieurbiz_cms_block_channels ADD CONSTRAINT FK_50664388E9ED820C FOREIGN KEY (block_id) REFERENCES monsieurbiz_cms_block (id)');
        $this->addSql('ALTER TABLE monsieurbiz_cms_block_channels ADD CONSTRAINT FK_5066438872F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id)');
        $this->addSql('ALTER TABLE monsieurbiz_cms_block_translation ADD CONSTRAINT FK_938B10EE2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES monsieurbiz_cms_block (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_cms_block_channels DROP FOREIGN KEY FK_50664388E9ED820C');
        $this->addSql('ALTER TABLE monsieurbiz_cms_block_translation DROP FOREIGN KEY FK_938B10EE2C2AC5D3');
        $this->addSql('DROP TABLE monsieurbiz_cms_block');
        $this->addSql('DROP TABLE monsieurbiz_cms_block_channels');
        $this->addSql('DROP TABLE monsieurbiz_cms_block_translation');
    }
}
