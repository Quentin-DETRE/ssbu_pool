<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230405095354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initiation of the database';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, serie_id INT NOT NULL, name VARCHAR(25) NOT NULL, weight VARCHAR(25) NOT NULL, speed VARCHAR(25) NOT NULL, tier VARCHAR(3) NOT NULL, iteration_number VARCHAR(3) NOT NULL, image_path VARCHAR(255) NOT NULL, INDEX IDX_937AB034D94388BD (serie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_cp (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_cp_character (character_cp_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_3833D652144CA15C (character_cp_id), INDEX IDX_3833D6521136BE75 (character_id), PRIMARY KEY(character_cp_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, image_path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, username VARCHAR(25) NOT NULL, name VARCHAR(25) NOT NULL, surname VARCHAR(25) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE character_cp_character ADD CONSTRAINT FK_3833D652144CA15C FOREIGN KEY (character_cp_id) REFERENCES character_cp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_cp_character ADD CONSTRAINT FK_3833D6521136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034D94388BD');
        $this->addSql('ALTER TABLE character_cp_character DROP FOREIGN KEY FK_3833D652144CA15C');
        $this->addSql('ALTER TABLE character_cp_character DROP FOREIGN KEY FK_3833D6521136BE75');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE character_cp');
        $this->addSql('DROP TABLE character_cp_character');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE `user`');
    }
}
