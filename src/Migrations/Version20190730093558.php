<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190730093558 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE customer_address (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                city VARCHAR(255) DEFAULT NULL, 
                country_id VARCHAR(255) DEFAULT NULL, 
                firstname VARCHAR(255) DEFAULT NULL, 
                lastname VARCHAR(255) DEFAULT NULL, 
                postcode VARCHAR(255) DEFAULT NULL, 
                prefix VARCHAR(255) DEFAULT NULL, 
                region VARCHAR(255) DEFAULT NULL, 
                street VARCHAR(255) DEFAULT NULL, 
                telephone VARCHAR(32) DEFAULT NULL, 
                is_default_billing TINYINT(1) DEFAULT NULL, 
                is_default_shipping TINYINT(1) DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                updated_at DATETIME DEFAULT NULL, 
                INDEX IDX_D4E6F81727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('
            CREATE TABLE customer (
                id INT AUTO_INCREMENT NOT NULL, 
                email VARCHAR(255) DEFAULT NULL, 
                firstname VARCHAR(255) DEFAULT NULL, 
                lastname VARCHAR(255) DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                updated_at DATETIME DEFAULT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_D4E6F81727ACA70 FOREIGN KEY (parent_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_D4E6F81727ACA70');
        $this->addSql('DROP TABLE customer_address');
        $this->addSql('DROP TABLE customer');
    }
}
