<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210130092600 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE temp_humidity_record (id INT AUTO_INCREMENT NOT NULL, micro_controller_id INT NOT NULL, temperature_int DOUBLE PRECISION NOT NULL, temperature_ext DOUBLE PRECISION NOT NULL, humidity_int DOUBLE PRECISION NOT NULL, humidity_ext DOUBLE PRECISION NOT NULL, INDEX IDX_DE0097077F5291FD (micro_controller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE temp_humidity_record ADD CONSTRAINT FK_DE0097077F5291FD FOREIGN KEY (micro_controller_id) REFERENCES micro_controller (id)');
        $this->addSql('DROP TABLE temperature_record');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE temperature_record (id INT AUTO_INCREMENT NOT NULL, micro_controller_id INT NOT NULL, temperature_int DOUBLE PRECISION NOT NULL, temperature_ext DOUBLE PRECISION NOT NULL, INDEX IDX_9248A7527F5291FD (micro_controller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE temperature_record ADD CONSTRAINT FK_9248A7527F5291FD FOREIGN KEY (micro_controller_id) REFERENCES micro_controller (id)');
        $this->addSql('DROP TABLE temp_humidity_record');
    }
}
