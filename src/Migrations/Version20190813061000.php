<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190813061000 extends AbstractMigration
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
            CREATE TABLE sale_order_expedited (
                id INT AUTO_INCREMENT NOT NULL, 
                parent_id INT NOT NULL, 
                comment LONGTEXT DEFAULT NULL, 
                creator_id INT NOT NULL, 
                created_at DATETIME DEFAULT NULL, 
                INDEX IDX_9266CD0B727ACA70 (creator_id),
                UNIQUE INDEX UNIQ_9266CD0B727ACA70 (parent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE sale_order_expedited ADD CONSTRAINT FK_9266CD0B727ACA70 FOREIGN KEY (parent_id) REFERENCES sale_order (id)');
        $this->addSql('ALTER TABLE sale_order_expedited ADD CONSTRAINT FK_9266CD0B61220EA6 FOREIGN KEY (creator_id) REFERENCES user_role (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sale_order_expedited DROP FOREIGN KEY FK_9266CD0B727ACA70');
        $this->addSql('ALTER TABLE sale_order_expedited DROP FOREIGN KEY FK_9266CD0B61220EA6');
        $this->addSql('DROP TABLE sale_order_expedited');
    }
}
