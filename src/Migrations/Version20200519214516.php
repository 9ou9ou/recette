<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519214516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13753AA0A63');
        $this->addSql('DROP INDEX IDX_DA88B13753AA0A63 ON recipe');
        $this->addSql('ALTER TABLE recipe DROP ingredient_recipe_id');
        $this->addSql('ALTER TABLE ingredient_recipe DROP FOREIGN KEY FK_36F271762D1731E9');
        $this->addSql('DROP INDEX IDX_36F271762D1731E9 ON ingredient_recipe');
        $this->addSql('ALTER TABLE ingredient_recipe ADD ingredient_id INT DEFAULT NULL, CHANGE id_ingredient_id recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient_recipe ADD CONSTRAINT FK_36F2717659D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE ingredient_recipe ADD CONSTRAINT FK_36F27176933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('CREATE INDEX IDX_36F2717659D8A214 ON ingredient_recipe (recipe_id)');
        $this->addSql('CREATE INDEX IDX_36F27176933FE08C ON ingredient_recipe (ingredient_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ingredient_recipe DROP FOREIGN KEY FK_36F2717659D8A214');
        $this->addSql('ALTER TABLE ingredient_recipe DROP FOREIGN KEY FK_36F27176933FE08C');
        $this->addSql('DROP INDEX IDX_36F2717659D8A214 ON ingredient_recipe');
        $this->addSql('DROP INDEX IDX_36F27176933FE08C ON ingredient_recipe');
        $this->addSql('ALTER TABLE ingredient_recipe ADD id_ingredient_id INT DEFAULT NULL, DROP recipe_id, DROP ingredient_id');
        $this->addSql('ALTER TABLE ingredient_recipe ADD CONSTRAINT FK_36F271762D1731E9 FOREIGN KEY (id_ingredient_id) REFERENCES ingredient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_36F271762D1731E9 ON ingredient_recipe (id_ingredient_id)');
        $this->addSql('ALTER TABLE recipe ADD ingredient_recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13753AA0A63 FOREIGN KEY (ingredient_recipe_id) REFERENCES ingredient_recipe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DA88B13753AA0A63 ON recipe (ingredient_recipe_id)');
    }
}
