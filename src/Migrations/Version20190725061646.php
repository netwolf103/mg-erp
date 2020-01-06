<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190725061646 extends AbstractMigration
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
            CREATE TABLE sale_order_comment (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                user_id INT DEFAULT 0,
                is_customer_notified INT DEFAULT NULL, 
                comment LONGTEXT DEFAULT NULL, 
                status VARCHAR(32) DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                entity_name VARCHAR(32) DEFAULT NULL, 
                INDEX IDX_43897DC3727ACA70 (parent_id), 
                INDEX IDX_43897DC3A76ED395 (user_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE sale_order_comment ADD CONSTRAINT FK_43897DC3727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sale_order_comment DROP FOREIGN KEY FK_43897DC3727ACA70');
        $this->addSql('DROP TABLE sale_order_comment');
   
    }
}
