<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200414035734 extends AbstractMigration
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
            CREATE TABLE mail_folder (
                id INT AUTO_INCREMENT NOT NULL, 
                name VARCHAR(50) NOT NULL, 
                alias VARCHAR(50) DEFAULT NULL, 
                fullpath VARCHAR(255) NOT NULL, 
                is_pause TINYINT(1) DEFAULT 0,
                created_at DATETIME NOT NULL, 
                updated_at DATETIME NOT NULL, 
                UNIQUE INDEX UNIQ_319BB6A65E237E06 (name), 
                UNIQUE INDEX UNIQ_319BB6A6C41288A7 (fullpath), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE mail (
                id INT AUTO_INCREMENT NOT NULL, 
                folder_id INT NOT NULL, 
                msg_id INT NOT NULL, 
                date DATETIME NOT NULL, 
                from_name VARCHAR(100) DEFAULT NULL, 
                from_address VARCHAR(255) NOT NULL, 
                to_address VARCHAR(255) NOT NULL, 
                cc_address VARCHAR(255) DEFAULT NULL, 
                bcc_address VARCHAR(255) DEFAULT NULL, 
                reply_to_address VARCHAR(255) DEFAULT NULL, 
                subject VARCHAR(255) NOT NULL, 
                body LONGTEXT NOT NULL, 
                created_at DATETIME NOT NULL, 
                INDEX IDX_5126AC48162CB942 (folder_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE mail ADD CONSTRAINT FK_5126AC48162CB942 FOREIGN KEY (folder_id) REFERENCES mail_folder (id)');        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mail_folder');
        $this->addSql('DROP TABLE mail');
    }
}
