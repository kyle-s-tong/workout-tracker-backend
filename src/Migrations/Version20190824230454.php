<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190824230454 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, value VARCHAR(191) NOT NULL, enabled TINYINT(1) NOT NULL, expiry_date DATETIME NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_5F37A13BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_record_set (id INT AUTO_INCREMENT NOT NULL, exercise_record_id INT NOT NULL, reps INT NOT NULL, weight NUMERIC(8, 2) DEFAULT NULL, rest INT DEFAULT NULL, is_complete TINYINT(1) DEFAULT NULL, INDEX IDX_C44A2071E32A73E6 (exercise_record_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workout_record (id INT AUTO_INCREMENT NOT NULL, workout_id INT NOT NULL, date_recorded DATETIME DEFAULT NULL, INDEX IDX_81B7805FA6CCCFC9 (workout_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise (id INT AUTO_INCREMENT NOT NULL, workout_id INT NOT NULL, exercise_summary_id INT NOT NULL, title VARCHAR(255) NOT NULL, number_of_sets INT NOT NULL, reps INT NOT NULL, rest INT DEFAULT NULL, INDEX IDX_AEDAD51CA6CCCFC9 (workout_id), INDEX IDX_AEDAD51C6AB3083C (exercise_summary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_exercise (exercise_source INT NOT NULL, exercise_target INT NOT NULL, INDEX IDX_A8B675F42AC5EA18 (exercise_source), INDEX IDX_A8B675F43320BA97 (exercise_target), PRIMARY KEY(exercise_source, exercise_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_record (id INT AUTO_INCREMENT NOT NULL, exercise_id INT NOT NULL, workout_record_id INT NOT NULL, title VARCHAR(255) NOT NULL, date_recorded DATETIME DEFAULT NULL, is_complete TINYINT(1) NOT NULL, INDEX IDX_EE7B0AFAE934951A (exercise_id), INDEX IDX_EE7B0AFA6F271258 (workout_record_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workout (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_649FFB72A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_summary (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercise_record_set ADD CONSTRAINT FK_C44A2071E32A73E6 FOREIGN KEY (exercise_record_id) REFERENCES exercise_record (id)');
        $this->addSql('ALTER TABLE workout_record ADD CONSTRAINT FK_81B7805FA6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id)');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51CA6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id)');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C6AB3083C FOREIGN KEY (exercise_summary_id) REFERENCES exercise_summary (id)');
        $this->addSql('ALTER TABLE exercise_exercise ADD CONSTRAINT FK_A8B675F42AC5EA18 FOREIGN KEY (exercise_source) REFERENCES exercise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_exercise ADD CONSTRAINT FK_A8B675F43320BA97 FOREIGN KEY (exercise_target) REFERENCES exercise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_record ADD CONSTRAINT FK_EE7B0AFAE934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id)');
        $this->addSql('ALTER TABLE exercise_record ADD CONSTRAINT FK_EE7B0AFA6F271258 FOREIGN KEY (workout_record_id) REFERENCES workout_record (id)');
        $this->addSql('ALTER TABLE workout ADD CONSTRAINT FK_649FFB72A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('ALTER TABLE workout DROP FOREIGN KEY FK_649FFB72A76ED395');
        $this->addSql('ALTER TABLE exercise_record DROP FOREIGN KEY FK_EE7B0AFA6F271258');
        $this->addSql('ALTER TABLE exercise_exercise DROP FOREIGN KEY FK_A8B675F42AC5EA18');
        $this->addSql('ALTER TABLE exercise_exercise DROP FOREIGN KEY FK_A8B675F43320BA97');
        $this->addSql('ALTER TABLE exercise_record DROP FOREIGN KEY FK_EE7B0AFAE934951A');
        $this->addSql('ALTER TABLE exercise_record_set DROP FOREIGN KEY FK_C44A2071E32A73E6');
        $this->addSql('ALTER TABLE workout_record DROP FOREIGN KEY FK_81B7805FA6CCCFC9');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51CA6CCCFC9');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C6AB3083C');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE exercise_record_set');
        $this->addSql('DROP TABLE routine');
        $this->addSql('DROP TABLE workout_record');
        $this->addSql('DROP TABLE exercise');
        $this->addSql('DROP TABLE exercise_exercise');
        $this->addSql('DROP TABLE exercise_record');
        $this->addSql('DROP TABLE workout');
        $this->addSql('DROP TABLE exercise_summary');
    }
}
