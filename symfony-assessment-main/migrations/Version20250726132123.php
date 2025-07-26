<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250726132123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, symbol VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country ADD currency_id INT DEFAULT NULL, ADD uuid VARCHAR(255) NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD region VARCHAR(255) NOT NULL, ADD sub_region VARCHAR(255) NOT NULL, ADD demonym VARCHAR(255) NOT NULL, ADD population BIGINT NOT NULL, ADD independant TINYINT(1) NOT NULL, ADD flag VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C96638248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5373C966D17F50A6 ON country (uuid)');
        $this->addSql('CREATE INDEX IDX_5373C96638248176 ON country (currency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C96638248176');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP INDEX UNIQ_5373C966D17F50A6 ON country');
        $this->addSql('DROP INDEX IDX_5373C96638248176 ON country');
        $this->addSql('ALTER TABLE country DROP currency_id, DROP uuid, DROP name, DROP region, DROP sub_region, DROP demonym, DROP population, DROP independant, DROP flag');
    }
}
