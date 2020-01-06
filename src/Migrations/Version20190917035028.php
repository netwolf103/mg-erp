<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190917035028 extends AbstractMigration
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
            CREATE TABLE product_purchase_order_comment (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT DEFAULT NULL, 
                user_id INT DEFAULT NULL, 
                comment LONGTEXT DEFAULT NULL, 
                status VARCHAR(32) DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                INDEX IDX_F6DA75DE727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('
            CREATE TABLE product_purchase_order_item (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                size VARCHAR(50) DEFAULT NULL, 
                price DOUBLE PRECISION DEFAULT NULL, 
                qty_ordered INT DEFAULT NULL, 
                subtotal DOUBLE PRECISION DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                updated_at DATETIME DEFAULT NULL, 
                INDEX IDX_798A351727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('
            CREATE TABLE product_purchase_order (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT DEFAULT NULL, 
                status VARCHAR(50) DEFAULT NULL, 
                total_qty_ordered INT DEFAULT NULL, 
                shipping_amount DOUBLE PRECISION DEFAULT NULL, 
                subtotal DOUBLE PRECISION DEFAULT NULL, 
                grand_total DOUBLE PRECISION DEFAULT NULL, 
                source_order_number VARCHAR(200) DEFAULT NULL, 
                track_number VARCHAR(200) DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                updated_at DATETIME DEFAULT NULL, 
                INDEX IDX_728B6E9E727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE product_purchase_order_comment ADD CONSTRAINT FK_F6DA75DE727ACA70 FOREIGN KEY (parent_id) REFERENCES product_purchase_order (id)');
        $this->addSql('ALTER TABLE product_purchase_order_item ADD CONSTRAINT FK_798A351727ACA70 FOREIGN KEY (parent_id) REFERENCES product_purchase_order (id)');
        $this->addSql('ALTER TABLE product_purchase_order ADD CONSTRAINT FK_728B6E9E727ACA70 FOREIGN KEY (parent_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_purchase_order_comment DROP FOREIGN KEY FK_F6DA75DE727ACA70');
        $this->addSql('ALTER TABLE product_purchase_order_item DROP FOREIGN KEY FK_798A351727ACA70');

        $this->addSql('DROP TABLE product_purchase_order_comment');
        $this->addSql('DROP TABLE product_purchase_order_item');
        $this->addSql('DROP TABLE product_purchase_order');
    }
}
