<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190730030146 extends AbstractMigration
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
            CREATE TABLE sale_order_shipment (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT DEFAULT NULL, 
                increment_id VARCHAR(50) DEFAULT NULL, 
                store_id INT DEFAULT NULL,  
                total_qty DOUBLE PRECISION DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                updated_at DATETIME DEFAULT NULL, 
                INDEX IDX_4475638A727ACA70 (parent_id), PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE sale_order_shipment_item (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                sku VARCHAR(255) DEFAULT NULL, 
                name VARCHAR(255) DEFAULT NULL,  
                weight DOUBLE PRECISION DEFAULT NULL, 
                price DOUBLE PRECISION DEFAULT NULL, 
                qty DOUBLE PRECISION DEFAULT NULL, 
                INDEX IDX_56D48561727ACA70 (parent_id), PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE sale_order_shipment_track (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                carrier_code VARCHAR(32) DEFAULT NULL, 
                title VARCHAR(255) DEFAULT NULL, 
                track_number VARCHAR(255) DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                updated_at DATETIME DEFAULT NULL, 
                INDEX IDX_16105BAB727ACA70 (parent_id), PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        
        $this->addSql('ALTER TABLE sale_order_shipment ADD CONSTRAINT FK_4475638A727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order (id)');
        $this->addSql('ALTER TABLE sale_order_shipment_item ADD CONSTRAINT FK_56D48561727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order_shipment (id)');
        $this->addSql('ALTER TABLE sale_order_shipment_track ADD CONSTRAINT FK_16105BAB727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order_shipment (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sale_order_shipment_item DROP FOREIGN KEY FK_56D48561727ACA70');
        $this->addSql('ALTER TABLE sale_order_shipment_track DROP FOREIGN KEY FK_16105BAB727ACA70');
        $this->addSql('ALTER TABLE sale_order_shipment DROP FOREIGN KEY FK_4475638A727ACA70');

        $this->addSql('DROP TABLE sale_order_shipment');
        $this->addSql('DROP TABLE sale_order_shipment_item');
        $this->addSql('DROP TABLE sale_order_shipment_track');
    }
}
