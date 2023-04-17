<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230405130127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'camelCase for the attribut';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('ALTER TABLE character_cp DROP FOREIGN KEY FK_F1038282C70F0E28');
        $this->addSql('CREATE TABLE character_choice (id INT AUTO_INCREMENT NOT NULL, serie_id INT NOT NULL, name VARCHAR(25) NOT NULL, weight VARCHAR(25) NOT NULL, speed VARCHAR(25) NOT NULL, tier VARCHAR(3) NOT NULL, iteration_number VARCHAR(3) NOT NULL, image_path VARCHAR(255) NOT NULL, INDEX IDX_62E99901D94388BD (serie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_choice ADD CONSTRAINT FK_62E99901D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034D94388BD');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP INDEX IDX_F1038282C70F0E28 ON character_cp');
        $this->addSql('ALTER TABLE character_cp CHANGE characters_id character_choice_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_cp ADD CONSTRAINT FK_F10382821ED6C4EA FOREIGN KEY (character_choice_id) REFERENCES character_choice (id)');
        $this->addSql('CREATE INDEX IDX_F10382821ED6C4EA ON character_cp (character_choice_id)');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14EB9D7579');
        $this->addSql('DROP INDEX IDX_CFBDFA14EB9D7579 ON note');
        $this->addSql('ALTER TABLE note CHANGE title title VARCHAR(75) NOT NULL, CHANGE character�_cp_id character_cp_id INT NOT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14144CA15C FOREIGN KEY (character_cp_id) REFERENCES character_cp (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14144CA15C ON note (character_cp_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE character_cp DROP FOREIGN KEY FK_F10382821ED6C4EA');
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, serie_id INT NOT NULL, name VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, weight VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, speed VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, tier VARCHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, iteration_number VARCHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image_path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_937AB034D94388BD (serie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE character_choice DROP FOREIGN KEY FK_62E99901D94388BD');
        $this->addSql('DROP TABLE character_choice');
        $this->addSql('DROP INDEX IDX_F10382821ED6C4EA ON character_cp');
        $this->addSql('ALTER TABLE character_cp CHANGE character_choice_id characters_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_cp ADD CONSTRAINT FK_F1038282C70F0E28 FOREIGN KEY (characters_id) REFERENCES `character` (id)');
        $this->addSql('CREATE INDEX IDX_F1038282C70F0E28 ON character_cp (characters_id)');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14144CA15C');
        $this->addSql('DROP INDEX IDX_CFBDFA14144CA15C ON note');
        $this->addSql('ALTER TABLE note CHANGE title title VARCHAR(100) NOT NULL, CHANGE character_cp_id character�_cp_id INT NOT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14EB9D7579 FOREIGN KEY (character�_cp_id) REFERENCES character_cp (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14EB9D7579 ON note (character�_cp_id)');
    }
}
