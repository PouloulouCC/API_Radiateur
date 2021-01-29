<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129215052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE micro_controller (id INT AUTO_INCREMENT NOT NULL, mac_address VARCHAR(255) NOT NULL, mode INT NOT NULL, temp_max INT DEFAULT NULL, temp_min INT DEFAULT NULL, temperature DOUBLE PRECISION NOT NULL, state TINYINT(1) NOT NULL, hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE micro_controller_user (micro_controller_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C2E483AF7F5291FD (micro_controller_id), INDEX IDX_C2E483AFA76ED395 (user_id), PRIMARY KEY(micro_controller_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temperature_record (id INT AUTO_INCREMENT NOT NULL, micro_controller_id INT NOT NULL, temperature_int DOUBLE PRECISION NOT NULL, temperature_ext DOUBLE PRECISION NOT NULL, INDEX IDX_9248A7527F5291FD (micro_controller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE micro_controller_user ADD CONSTRAINT FK_C2E483AF7F5291FD FOREIGN KEY (micro_controller_id) REFERENCES micro_controller (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE micro_controller_user ADD CONSTRAINT FK_C2E483AFA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE temperature_record ADD CONSTRAINT FK_9248A7527F5291FD FOREIGN KEY (micro_controller_id) REFERENCES micro_controller (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE micro_controller_user DROP FOREIGN KEY FK_C2E483AF7F5291FD');
        $this->addSql('ALTER TABLE temperature_record DROP FOREIGN KEY FK_9248A7527F5291FD');
        $this->addSql('ALTER TABLE micro_controller_user DROP FOREIGN KEY FK_C2E483AFA76ED395');
        $this->addSql('DROP TABLE micro_controller');
        $this->addSql('DROP TABLE micro_controller_user');
        $this->addSql('DROP TABLE temperature_record');
        $this->addSql('DROP TABLE `user`');
    }
}
