<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190908013452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE exercise_record_set');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exercise_record_set (id INT AUTO_INCREMENT NOT NULL, exercise_record_id INT NOT NULL, reps INT NOT NULL, weight NUMERIC(8, 2) DEFAULT NULL, rest INT DEFAULT NULL, is_complete TINYINT(1) DEFAULT NULL, INDEX IDX_C44A2071E32A73E6 (exercise_record_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE exercise_record_set ADD CONSTRAINT FK_C44A2071E32A73E6 FOREIGN KEY (exercise_record_id) REFERENCES exercise_record (id)');
    }
}
