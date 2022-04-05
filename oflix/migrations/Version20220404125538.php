<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404125538 extends AbstractMigration
{
    public function isTransactional(): bool
    {
        return false;
    }
    
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE role_play (id INT AUTO_INCREMENT NOT NULL, personage_id INT NOT NULL, tv_show_id INT NOT NULL, credit_order INT NOT NULL, INDEX IDX_E036AC2EEA8E3E4A (personage_id), INDEX IDX_E036AC2E5E3A35BB (tv_show_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE role_play ADD CONSTRAINT FK_E036AC2EEA8E3E4A FOREIGN KEY (personage_id) REFERENCES `character` (id)');
        $this->addSql('ALTER TABLE role_play ADD CONSTRAINT FK_E036AC2E5E3A35BB FOREIGN KEY (tv_show_id) REFERENCES tv_show (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE role_play');
    }
}
