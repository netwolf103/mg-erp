<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190809103437 extends AbstractMigration
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
            CREATE TABLE sale_order_invoice_item (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                weee_tax_applied LONGTEXT DEFAULT NULL, 
                qty DOUBLE PRECISION DEFAULT NULL, 
                price DOUBLE PRECISION DEFAULT NULL, 
                row_total DOUBLE PRECISION DEFAULT NULL, 
                base_price DOUBLE PRECISION DEFAULT NULL, 
                base_row_total DOUBLE PRECISION DEFAULT NULL, 
                base_weee_tax_applied_amount DOUBLE PRECISION DEFAULT NULL, 
                base_weee_tax_applied_row_amount DOUBLE PRECISION DEFAULT NULL, 
                weee_tax_applied_amount DOUBLE PRECISION DEFAULT NULL, 
                weee_tax_applied_row_amount DOUBLE PRECISION DEFAULT NULL, 
                weee_tax_disposition DOUBLE PRECISION DEFAULT NULL, 
                weee_tax_row_disposition DOUBLE PRECISION DEFAULT NULL, 
                base_weee_tax_disposition DOUBLE PRECISION DEFAULT NULL, 
                base_weee_tax_row_disposition DOUBLE PRECISION DEFAULT NULL, 
                sku VARCHAR(255) DEFAULT NULL, 
                name VARCHAR(255) DEFAULT NULL, 
                INDEX IDX_649D430F727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE sale_order_invoice (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                increment_id VARCHAR(50) DEFAULT NULL, 
                store_id INT DEFAULT NULL, 
                global_currency_code VARCHAR(3) DEFAULT NULL, 
                base_currency_code VARCHAR(3) DEFAULT NULL, 
                store_currency_code VARCHAR(3) DEFAULT NULL, 
                order_currency_code VARCHAR(3) DEFAULT NULL, 
                store_to_base_rate DOUBLE PRECISION DEFAULT NULL, 
                store_to_order_rate DOUBLE PRECISION DEFAULT NULL, 
                base_to_global_rate DOUBLE PRECISION DEFAULT NULL, 
                base_to_order_rate DOUBLE PRECISION DEFAULT NULL, 
                subtotal DOUBLE PRECISION DEFAULT NULL, 
                base_subtotal DOUBLE PRECISION DEFAULT NULL, 
                base_grand_total DOUBLE PRECISION DEFAULT NULL, 
                discount_amount DOUBLE PRECISION DEFAULT NULL, 
                base_discount_amount DOUBLE PRECISION DEFAULT NULL, 
                shipping_amount DOUBLE PRECISION DEFAULT NULL, 
                base_shipping_amount DOUBLE PRECISION DEFAULT NULL, 
                tax_amount DOUBLE PRECISION DEFAULT NULL, 
                base_tax_amount DOUBLE PRECISION DEFAULT NULL, 
                state INT DEFAULT NULL, 
                grand_total DOUBLE PRECISION DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                updated_at DATETIME DEFAULT NULL,                 
                UNIQUE INDEX UNIQ_479838EB727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        
        $this->addSql('ALTER TABLE sale_order_invoice_item ADD CONSTRAINT FK_649D430F727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order_invoice (id)');
        $this->addSql('ALTER TABLE sale_order_invoice ADD CONSTRAINT FK_479838EB727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sale_order_invoice_item DROP FOREIGN KEY FK_649D430F727ACA70');
        $this->addSql('DROP TABLE sale_order_invoice_item');
        $this->addSql('DROP TABLE sale_order_invoice');
    }
}
