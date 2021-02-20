<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131093908 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE micro_controller ADD api_last_call DATETIME NOT NULL, ADD current_ext_humidity DOUBLE PRECISION NOT NULL, CHANGE temperature current_ext_temperature DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE temp_humidity_record ADD time_stamp DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE micro_controller ADD temperature DOUBLE PRECISION NOT NULL, DROP current_ext_temperature, DROP api_last_call, DROP current_ext_humidity');
        $this->addSql('ALTER TABLE temp_humidity_record DROP time_stamp');
    }
}
