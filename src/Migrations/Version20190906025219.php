<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190906025219 extends AbstractMigration
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
            CREATE TABLE product_google (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT DEFAULT NULL, 
                g_offer_id VARCHAR(32) NOT NULL, 
                g_title VARCHAR(255) NOT NULL, 
                g_description LONGTEXT NOT NULL, 
                g_link VARCHAR(255) NOT NULL, 
                g_image_link VARCHAR(255) NOT NULL, 
                g_additional_image_link LONGTEXT DEFAULT NULL, 
                g_availability VARCHAR(50) NOT NULL, 
                g_availability_date DATETIME DEFAULT NULL, 
                g_cost_of_goods_sold VARCHAR(100) DEFAULT NULL, 
                g_expiration_date DATETIME DEFAULT NULL, 
                g_price VARCHAR(100) NOT NULL, 
                g_sale_price VARCHAR(100) DEFAULT NULL, 
                g_sale_price_effective_date DATETIME DEFAULT NULL, 
                g_unit_price_measure VARCHAR(50) DEFAULT NULL, 
                g_unit_pricing_base_measure VARCHAR(50) DEFAULT NULL, 
                g_installment VARCHAR(50) DEFAULT NULL, 
                g_google_product_category_id INT DEFAULT NULL, 
                g_product_type LONGTEXT DEFAULT NULL, 
                g_brand VARCHAR(100) NOT NULL, 
                g_gtin VARCHAR(50) DEFAULT NULL, 
                g_mpn VARCHAR(100) DEFAULT NULL, 
                g_identifier_exists TINYINT(1) DEFAULT NULL, 
                g_condition VARCHAR(30) NOT NULL, 
                g_adult TINYINT(1) NOT NULL, 
                g_multipack VARCHAR(100) DEFAULT NULL, 
                g_is_bundle TINYINT(1) NOT NULL, 
                g_energy_efficiency_class VARCHAR(50) DEFAULT NULL, 
                g_min_energy_efficiency_class VARCHAR(50) DEFAULT NULL, 
                g_max_energy_efficiency_class VARCHAR(50) DEFAULT NULL, 
                g_age_group VARCHAR(30) NOT NULL, 
                g_color VARCHAR(100) NOT NULL, 
                g_gender VARCHAR(30) NOT NULL, 
                g_material VARCHAR(200) NOT NULL, 
                g_pattern VARCHAR(100) DEFAULT NULL, 
                g_size LONGTEXT NOT NULL, 
                g_size_type VARCHAR(50) DEFAULT NULL, 
                g_size_system VARCHAR(10) DEFAULT NULL, 
                g_item_group_id VARCHAR(50) NOT NULL, 
                g_ads_redirect LONGTEXT DEFAULT NULL, 
                g_custom_label_0 VARCHAR(100) DEFAULT NULL, 
                g_custom_label_1 VARCHAR(100) DEFAULT NULL, 
                g_custom_label_2 VARCHAR(100) DEFAULT NULL, 
                g_custom_label_3 VARCHAR(100) DEFAULT NULL, 
                g_custom_label_4 VARCHAR(100) DEFAULT NULL, 
                g_promotion_id VARCHAR(50) DEFAULT NULL, 
                g_excluded_destination VARCHAR(50) DEFAULT NULL, 
                g_included_destination VARCHAR(50) DEFAULT NULL, 
                g_shipping VARCHAR(200) DEFAULT NULL, 
                g_shipping_label VARCHAR(100) DEFAULT NULL, 
                g_shipping_weight VARCHAR(30) DEFAULT NULL, 
                g_shipping_length VARCHAR(100) DEFAULT NULL, 
                g_shipping_width VARCHAR(100) DEFAULT NULL, 
                g_shipping_height VARCHAR(100) DEFAULT NULL, 
                g_transit_time_label VARCHAR(100) DEFAULT NULL, 
                g_max_handling_time INT DEFAULT NULL, 
                g_min_handling_time INT DEFAULT NULL, 
                g_tax VARCHAR(200) DEFAULT NULL, 
                g_tax_category VARCHAR(100) DEFAULT NULL, 
                UNIQUE INDEX UNIQ_760B4F5727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE product_google ADD CONSTRAINT FK_760B4F5727ACA70 FOREIGN KEY (parent_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_google');
    }
}
