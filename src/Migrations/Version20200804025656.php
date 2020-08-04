<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200804025656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subscriber (
            id INT AUTO_INCREMENT NOT NULL, 
            subscriber_id INT NOT NULL, 
            subscriber_email VARCHAR(255) NOT NULL, 
            type INT NOT NULL, 
            customer_firstname VARCHAR(255) DEFAULT NULL, 
            customer_middlename VARCHAR(255) DEFAULT NULL, 
            customer_lastname VARCHAR(255) DEFAULT NULL, 
            subscriber_status INT NOT NULL, 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE subscriber');
    }
}
