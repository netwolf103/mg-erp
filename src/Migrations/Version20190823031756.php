<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190823031756 extends AbstractMigration
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
            CREATE TABLE sale_order_address_history (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                address_type VARCHAR(30) DEFAULT NULL, 
                firstname VARCHAR(100) DEFAULT NULL, 
                lastname VARCHAR(100) DEFAULT NULL, 
                street VARCHAR(255) DEFAULT NULL, 
                city VARCHAR(50) DEFAULT NULL, 
                region VARCHAR(50) DEFAULT NULL, 
                postcode VARCHAR(50) DEFAULT NULL, 
                country_id VARCHAR(50) DEFAULT NULL, 
                telephone VARCHAR(50) DEFAULT NULL, 
                comment LONGTEXT DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                INDEX IDX_A41E9208727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE sale_order_address_history ADD CONSTRAINT FK_A41E9208727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order_address (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sale_order_address_history');
    }
}
