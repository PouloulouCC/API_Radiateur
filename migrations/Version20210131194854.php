<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131194854 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE period (id INT AUTO_INCREMENT NOT NULL, micro_controller_id INT NOT NULL, week_day INT NOT NULL, time_start TIME NOT NULL, time_end TIME NOT NULL, INDEX IDX_C5B81ECE7F5291FD (micro_controller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE period ADD CONSTRAINT FK_C5B81ECE7F5291FD FOREIGN KEY (micro_controller_id) REFERENCES micro_controller (id)');
        $this->addSql('ALTER TABLE micro_controller DROP hours');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE period');
        $this->addSql('ALTER TABLE micro_controller ADD hours LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
