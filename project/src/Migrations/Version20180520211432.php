<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

final class Version20180520211432 extends AbstractMigration
{
    const POSTGRESQL = 'postgresql';
    const ERROR_MSG = "Migration can only be executed safely on 'postgresql'.";

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema): void
    {
        $this->validateDbPlatform();
        $sql = <<<SQL
CREATE TABLE daily_log (
  id          SERIAL                         NOT NULL,
  description VARCHAR(255)                   NOT NULL,
  start_date  TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL NOT NULL,
  end_date    TIMESTAMP(0) WITHOUT TIME ZONE,
  PRIMARY KEY (id)
);
SQL;
        $this->addSql($sql);
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema): void
    {
        $this->validateDbPlatform();
        $this->addSql('DROP TABLE daily_log');
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    private function validateDbPlatform(): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== self::POSTGRESQL, self::ERROR_MSG);
    }
}
