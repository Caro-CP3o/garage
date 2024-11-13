<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113103548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, founded INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cars (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, model VARCHAR(255) NOT NULL, year INT NOT NULL, price DOUBLE PRECISION NOT NULL, mileage INT NOT NULL, description LONGTEXT NOT NULL, cover_image VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, owner INT NOT NULL, engine DOUBLE PRECISION NOT NULL, horse INT NOT NULL, fuel VARCHAR(255) NOT NULL, transmission VARCHAR(255) NOT NULL, options LONGTEXT DEFAULT NULL, INDEX IDX_95C71D1444F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, car_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, caption VARCHAR(255) NOT NULL, INDEX IDX_C53D045FC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, introduction VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cars ADD CONSTRAINT FK_95C71D1444F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FC3C6F69F FOREIGN KEY (car_id) REFERENCES cars (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cars DROP FOREIGN KEY FK_95C71D1444F5D008');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FC3C6F69F');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE cars');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE user');
    }
}
