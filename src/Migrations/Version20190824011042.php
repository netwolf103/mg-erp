<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190824011042 extends AbstractMigration
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
            CREATE TABLE sale_order_payment_transaction (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_order_id INT NOT NULL, 
                txn_id VARCHAR(100) DEFAULT NULL, 
                parent_txn_id VARCHAR(100) DEFAULT NULL, 
                txn_type VARCHAR(15) DEFAULT NULL, 
                created_at DATETIME DEFAULT NULL, 
                INDEX IDX_E69AEAA1252C1E9 (parent_order_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE sale_order_payment_transaction ADD CONSTRAINT FK_E69AEAA1252C1E9 FOREIGN KEY (parent_order_id) REFERENCES sale_order (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sale_order_payment_transaction');
    }
}
