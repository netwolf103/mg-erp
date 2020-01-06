<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190802031642 extends AbstractMigration
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
            CREATE TABLE sale_order_refund_track (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                carrier_name VARCHAR(255) DEFAULT NULL, 
                track_number VARCHAR(255) DEFAULT NULL, 
                INDEX IDX_DA287A41727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE sale_order_refund (
                id INT AUTO_INCREMENT NOT NULL, 
                item_id INT NOT NULL,
                sku VARCHAR(255) DEFAULT NULL, 
                name VARCHAR(255) DEFAULT NULL, 
                price DOUBLE PRECISION DEFAULT NULL, 
                qty_ordered INT DEFAULT NULL, 
                qty_refunded INT DEFAULT NULL, 
                row_total DOUBLE PRECISION DEFAULT NULL, 
                refund_amount DOUBLE PRECISION DEFAULT NULL, 
                status TINYINT(1) DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                refunded_at DATETIME DEFAULT NULL, 
                INDEX IDX_ABD155B9126F525E (item_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('ALTER TABLE sale_order_refund_track ADD CONSTRAINT FK_DA287A41727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order_refund (id)');
        $this->addSql('ALTER TABLE sale_order_refund ADD CONSTRAINT FK_ABD155B9126F525E FOREIGN KEY (item_id) REFERENCES sale_order_item (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sale_order_refund_track DROP FOREIGN KEY FK_DA287A41727ACA70');
        $this->addSql('ALTER TABLE sale_order_refund DROP FOREIGN KEY FK_ABD155B9126F525E');
        $this->addSql('DROP TABLE sale_order_refund_track');
        $this->addSql('DROP TABLE sale_order_refund');
    }
}
