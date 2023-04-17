<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230405101745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added note table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, character�_cp_id INT NOT NULL, title VARCHAR(100) NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_CFBDFA14EB9D7579 (character�_cp_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14EB9D7579 FOREIGN KEY (character�_cp_id) REFERENCES character_cp (id)');
        $this->addSql('ALTER TABLE character_cp_character DROP FOREIGN KEY FK_3833D6521136BE75');
        $this->addSql('ALTER TABLE character_cp_character DROP FOREIGN KEY FK_3833D652144CA15C');
        $this->addSql('DROP TABLE character_cp_character');
        $this->addSql('ALTER TABLE character_cp ADD user_id INT NOT NULL, ADD characters_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_cp ADD CONSTRAINT FK_F1038282A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE character_cp ADD CONSTRAINT FK_F1038282C70F0E28 FOREIGN KEY (characters_id) REFERENCES `character` (id)');
        $this->addSql('CREATE INDEX IDX_F1038282A76ED395 ON character_cp (user_id)');
        $this->addSql('CREATE INDEX IDX_F1038282C70F0E28 ON character_cp (characters_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE character_cp_character (character_cp_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_3833D652144CA15C (character_cp_id), INDEX IDX_3833D6521136BE75 (character_id), PRIMARY KEY(character_cp_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE character_cp_character ADD CONSTRAINT FK_3833D6521136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_cp_character ADD CONSTRAINT FK_3833D652144CA15C FOREIGN KEY (character_cp_id) REFERENCES character_cp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14EB9D7579');
        $this->addSql('DROP TABLE note');
        $this->addSql('ALTER TABLE character_cp DROP FOREIGN KEY FK_F1038282A76ED395');
        $this->addSql('ALTER TABLE character_cp DROP FOREIGN KEY FK_F1038282C70F0E28');
        $this->addSql('DROP INDEX IDX_F1038282A76ED395 ON character_cp');
        $this->addSql('DROP INDEX IDX_F1038282C70F0E28 ON character_cp');
        $this->addSql('ALTER TABLE character_cp DROP user_id, DROP characters_id');
    }
}
