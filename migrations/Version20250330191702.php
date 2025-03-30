<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330191702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE band (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE band_membership (id INT AUTO_INCREMENT NOT NULL, band_id INT NOT NULL, member_id INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_7DCB8B4249ABEB17 (band_id), INDEX IDX_7DCB8B427597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_membership ADD CONSTRAINT FK_7DCB8B4249ABEB17 FOREIGN KEY (band_id) REFERENCES band (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_membership ADD CONSTRAINT FK_7DCB8B427597D3FE FOREIGN KEY (member_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE band_membership DROP FOREIGN KEY FK_7DCB8B4249ABEB17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE band_membership DROP FOREIGN KEY FK_7DCB8B427597D3FE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE band
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE band_membership
        SQL);
    }
}
