<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190709033857 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE `sessions` (
                `sess_id` VARCHAR(128) NOT NULL PRIMARY KEY,
                `sess_data` BLOB NOT NULL,
                `sess_time` INTEGER UNSIGNED NOT NULL,
                `sess_lifetime` MEDIUMINT NOT NULL
            ) COLLATE utf8_bin, ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE user (
                id INT AUTO_INCREMENT NOT NULL, 
                username VARCHAR(180) NOT NULL,  
                firstname VARCHAR(100) DEFAULT NULL,  
                lastname VARCHAR(100) DEFAULT NULL,  
                role_id INT NOT NULL,
                password VARCHAR(255) NOT NULL, 
                email VARCHAR(255) NOT NULL, 
                is_active TINYINT(1) NOT NULL DEFAULT 1, 
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                updated_at DATETIME DEFAULT NULL, 
                logged_at DATETIME DEFAULT NULL, 
                UNIQUE INDEX UNIQ_USERNAME (username), 
                UNIQUE INDEX UNIQ_EMAIL (email), 
                INDEX IDX_ROLE_ID (role_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE user_role (
                id INT AUTO_INCREMENT NOT NULL, 
                name VARCHAR(50) DEFAULT NULL, 
                description LONGTEXT DEFAULT NULL,
                UNIQUE INDEX UNIQ_NAME (name), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );        

        // 产品相关表
       $this->addSql('
            CREATE TABLE product (
                id INT AUTO_INCREMENT NOT NULL, 
                sku VARCHAR(32) NOT NULL, 
                type_id VARCHAR(32) DEFAULT NULL, 
                name VARCHAR(255) NOT NULL, 
                description LONGTEXT NOT NULL, 
                status SMALLINT NOT NULL, 
                url_path VARCHAR(255) NOT NULL, 
                product_url VARCHAR(255) DEFAULT NULL,
                visibility SMALLINT NOT NULL, 
                has_options SMALLINT NOT NULL, 
                price DOUBLE PRECISION NOT NULL, 
                special_price DOUBLE PRECISION DEFAULT NULL, 
                purchase_price DOUBLE PRECISION DEFAULT NULL, 
                inventory INT DEFAULT NULL, 
                purchase_url VARCHAR(255) DEFAULT NULL, 
                supplier_id INT DEFAULT NULL,
                created_at DATETIME NOT NULL, 
                updated_at DATETIME NOT NULL, 
                PRIMARY KEY(id),
                KEY `IDX_PRODUCT_SKU` (sku),
                KEY `IDX_D34A04AD2ADD6D8C` (supplier_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE product_media (
                id INT AUTO_INCREMENT NOT NULL, 
                product_id INT NOT NULL, 
                file VARCHAR(255) NOT NULL, 
                label VARCHAR(255) DEFAULT NULL, 
                position SMALLINT DEFAULT NULL, 
                exclude SMALLINT DEFAULT NULL, 
                url VARCHAR(255) NOT NULL, 
                INDEX IDX_CB70DA504584665A (product_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('
            ALTER TABLE product_media ADD CONSTRAINT FK_CB70DA504584665A FOREIGN KEY (product_id) REFERENCES product (id)'
        );

        $this->addSql('
            CREATE TABLE product_option (
                id INT AUTO_INCREMENT NOT NULL, 
                product_id INT NOT NULL, 
                title VARCHAR(255) NOT NULL, 
                type VARCHAR(50) NOT NULL, 
                sort_order INT NOT NULL, 
                is_require TINYINT(1) NOT NULL, 
                INDEX IDX_38FA41144584665A (product_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('
            ALTER TABLE product_option ADD CONSTRAINT FK_38FA41144584665A FOREIGN KEY (product_id) REFERENCES product (id)'
        );

        $this->addSql('
            CREATE TABLE product_option_field (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                price DOUBLE PRECISION DEFAULT NULL, 
                price_type VARCHAR(50) DEFAULT NULL, 
                sku VARCHAR(50) DEFAULT NULL, 
                max_characters INT DEFAULT NULL, 
                UNIQUE INDEX UNIQ_5BF54558727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE product_option_field ADD CONSTRAINT FK_5BF54558727ACA70 FOREIGN KEY (parent_id) REFERENCES product_option (id)');        

        $this->addSql('
            CREATE TABLE product_option_dropdown (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                title VARCHAR(255) DEFAULT NULL, 
                price DOUBLE PRECISION DEFAULT NULL, 
                price_type VARCHAR(50) DEFAULT NULL, 
                sku VARCHAR(50) DEFAULT NULL, 
                inventory INT DEFAULT NULL, 
                sort_order INT DEFAULT NULL, 
                INDEX IDX_A938C737727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('
            ALTER TABLE product_option_dropdown ADD CONSTRAINT FK_A938C737727ACA70 FOREIGN KEY (parent_id) REFERENCES product_option (id)'
        );

        // 订单相关表
       $this->addSql('
            CREATE TABLE sale_order (
                id INT AUTO_INCREMENT NOT NULL, 
                increment_id VARCHAR(60) NOT NULL, 
                tax_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                shipping_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                discount_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                subtotal decimal(12,2) NOT NULL DEFAULT 0.00, 
                grand_total decimal(12,2) NOT NULL DEFAULT 0.00, 
                total_qty_ordered SMALLINT NOT NULL, 
                base_tax_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_shipping_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_discount_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_subtotal decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_grand_total decimal(12,2) NOT NULL DEFAULT 0.00, 
                store_to_base_rate decimal(12,2) NOT NULL DEFAULT 0.00, 
                store_to_order_rate decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_to_global_rate decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_to_order_rate decimal(12,2) NOT NULL DEFAULT 0.00, 
                weight decimal(12,2) NOT NULL DEFAULT 0.00, 
                store_name VARCHAR(255) NOT NULL, 
                remote_ip VARCHAR(255) NOT NULL, 
                status VARCHAR(50) NOT NULL, 
                state VARCHAR(30) NOT NULL, 
                global_currency_code VARCHAR(30) NOT NULL, 
                base_currency_code VARCHAR(30) NOT NULL, 
                store_currency_code VARCHAR(30) NOT NULL, 
                order_currency_code VARCHAR(30) NOT NULL, 
                shipping_method VARCHAR(100) NOT NULL, 
                shipping_description VARCHAR(255) NOT NULL, 
                customer_email VARCHAR(100) NOT NULL, 
                quote_id INT(11) NOT NULL, 
                is_virtual TINYINT(1) NOT NULL, 
                customer_group_id INT NOT NULL, 
                customer_note_notify INT NOT NULL, 
                customer_is_guest TINYINT(1) NOT NULL, 
                order_id INT(11) NOT NULL,
                order_type smallint(5) UNSIGNED NOT NULL, 
                created_at DATETIME NOT NULL, 
                updated_at DATETIME NOT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE sale_order_payment (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                amount_ordered decimal(12,2) NOT NULL DEFAULT 0.00, 
                shipping_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_amount_ordered decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_shipping_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                method VARCHAR(255) NOT NULL, 
                payment_id INT NOT NULL, 
                UNIQUE INDEX UNIQ_BAD5ABA2727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('
            ALTER TABLE sale_order_payment ADD CONSTRAINT FK_BAD5ABA2727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order (id)'
        );

        $this->addSql('
            CREATE TABLE sale_order_item (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                product_id INT NOT NULL, 
                quote_item_id INT DEFAULT NULL,  
                product_type VARCHAR(32) NOT NULL, 
                product_options TEXT DEFAULT NULL, 
                weight decimal(12,2) DEFAULT NULL, 
                is_virtual TINYINT(1) NOT NULL, 
                sku VARCHAR(32) NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                free_shipping TINYINT(1) DEFAULT NULL, 
                is_qty_decimal TINYINT(1) DEFAULT NULL, 
                no_discount TINYINT(1) DEFAULT NULL, 
                qty_canceled decimal(12,2) NOT NULL DEFAULT 0.00, 
                qty_invoiced decimal(12,2) NOT NULL DEFAULT 0.00, 
                qty_ordered decimal(12,2) NOT NULL DEFAULT 0.00, 
                qty_refunded decimal(12,2) NOT NULL DEFAULT 0.00, 
                qty_shipped decimal(12,2) NOT NULL DEFAULT 0.00, 
                price decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_price decimal(12,2) NOT NULL DEFAULT 0.00, 
                original_price decimal(12,2) NOT NULL DEFAULT 0.00, 
                tax_percent decimal(12,2) NOT NULL DEFAULT 0.00, 
                tax_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_tax_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                tax_invoiced decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_tax_invoiced decimal(12,2) NOT NULL DEFAULT 0.00, 
                discount_percent decimal(12,2) NOT NULL DEFAULT 0.00, 
                discount_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_discount_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                discount_invoiced decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_discount_invoiced decimal(12,2) NOT NULL DEFAULT 0.00, 
                amount_refunded decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_amount_refunded decimal(12,2) NOT NULL DEFAULT 0.00, 
                row_total decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_row_total decimal(12,2) NOT NULL DEFAULT 0.00, 
                row_invoiced decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_row_invoiced decimal(12,2) NOT NULL DEFAULT 0.00, 
                row_weight decimal(12,2) NOT NULL DEFAULT 0.00, 
                weee_tax_applied VARCHAR(255) NOT NULL, 
                weee_tax_applied_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                weee_tax_applied_row_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_weee_tax_applied_amount decimal(12,2) NOT NULL DEFAULT 0.00, 
                weee_tax_disposition decimal(12,2) NOT NULL DEFAULT 0.00, 
                weee_tax_row_disposition decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_weee_tax_disposition decimal(12,2) NOT NULL DEFAULT 0.00, 
                base_weee_tax_row_disposition decimal(12,2) NOT NULL DEFAULT 0.00, 
                item_type smallint(5) UNSIGNED NOT NULL, 
                created_at DATETIME NOT NULL, 
                updated_at DATETIME NOT NULL,                
                INDEX IDX_AFBEFB4D727ACA70 (parent_id), 
                INDEX IDX_AFBEFB4D4584665A (product_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE sale_order_item ADD CONSTRAINT FK_AFBEFB4D727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order (id)');
         $this->addSql('ALTER TABLE sale_order_item ADD CONSTRAINT FK_AFBEFB4D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');

      $this->addSql('
            CREATE TABLE sale_order_address (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                address_type VARCHAR(30) NOT NULL, 
                firstname VARCHAR(100) NOT NULL, 
                lastname VARCHAR(100) NOT NULL, 
                street VARCHAR(255) NOT NULL, 
                city VARCHAR(50) NOT NULL, 
                region VARCHAR(50) NOT NULL, 
                postcode VARCHAR(50) NOT NULL, 
                country_id VARCHAR(50) NOT NULL, 
                telephone VARCHAR(50) DEFAULT NULL, 
                INDEX IDX_DAB3402E727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('
            ALTER TABLE sale_order_address ADD CONSTRAINT FK_DAB3402E727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order (id)'
        );                              

        // Init for Role & User
        $this->addSql('
            INSERT INTO user_role(name) VALUES ("ROLE_ADMIN"),("ROLE_SERVICE"),("ROLE_SHIPPER"),("ROLE_VENDOR")'
        );
        // Password "111111"       
        $this->addSql('
            INSERT INTO user(username, role_id, password, email) VALUES ("admin", 1, "$2y$13$nACmr7kIbEURFN4uSE1Mj.ykfMR5aPnHojA.ayDd6IlegaKK3vlZC", "netwolf103@gmail.com")'
        );

    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE `sessions`');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
        
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_media');
        $this->addSql('DROP TABLE product_option');
        $this->addSql('DROP TABLE product_option_dropdown');
        $this->addSql('DROP TABLE product_option_field');

        $this->addSql('DROP TABLE sale_order');
        $this->addSql('DROP TABLE sale_order_payment');
        $this->addSql('DROP TABLE sale_order_item');
        $this->addSql('DROP TABLE sale_order_address');        
    }
}
