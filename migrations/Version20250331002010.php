<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331002010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, author_id INT DEFAULT NULL, content LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_3E7B0BFB4B89032C (post_id), INDEX IDX_3E7B0BFBF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB4B89032C FOREIGN KEY (post_id) REFERENCES post (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFBF675F31B FOREIGN KEY (author_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB4B89032C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFBF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE response
        SQL);
    }
}
