<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230406134534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change of the max length of the iteration_number in character_choice';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_choice CHANGE iteration_number iteration_number VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_choice CHANGE iteration_number iteration_number VARCHAR(3) NOT NULL');
    }
}
