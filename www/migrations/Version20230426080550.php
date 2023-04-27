<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230426080550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'The iteration_number column in character_choice has been made unique';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62E9990132FDAAB1 ON character_choice (iteration_number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_62E9990132FDAAB1 ON character_choice');
    }
}
