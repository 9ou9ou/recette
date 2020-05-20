<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519213433 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ingredient_recipe (id INT AUTO_INCREMENT NOT NULL, id_ingredient_id INT DEFAULT NULL, quantity VARCHAR(255) NOT NULL, id_recipe VARCHAR(255) NOT NULL, INDEX IDX_36F271762D1731E9 (id_ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient_recipe ADD CONSTRAINT FK_36F271762D1731E9 FOREIGN KEY (id_ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE recipe ADD ingredient_recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13753AA0A63 FOREIGN KEY (ingredient_recipe_id) REFERENCES ingredient_recipe (id)');
        $this->addSql('CREATE INDEX IDX_DA88B13753AA0A63 ON recipe (ingredient_recipe_id)');
        $this->addSql('ALTER TABLE recipe_ingredient DROP quantity');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13753AA0A63');
        $this->addSql('DROP TABLE ingredient_recipe');
        $this->addSql('DROP INDEX IDX_DA88B13753AA0A63 ON recipe');
        $this->addSql('ALTER TABLE recipe DROP ingredient_recipe_id');
        $this->addSql('ALTER TABLE recipe_ingredient ADD quantity VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
