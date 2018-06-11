<?php

namespace App\TimeTracker;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

final class Storage
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function get(int $id): ?DailyLog
    {
        return $this->entityManager->find(DailyLog::class, $id);
    }

    /**
     * @return DailyLog[]
     */
    public function getAll(): array
    {
        $repository = $this->entityManager->getRepository(DailyLog::class);

        return $repository->findAll();
    }

    /**
     * @param string $description
     * @param Carbon $startDate
     * @param Carbon|null $endDate
     *
     * @return DailyLog
     */
    public function create(string $description, Carbon $startDate, ?Carbon $endDate)
    {
        $dailyLog = new DailyLog($description, $startDate, $endDate);
        $this->entityManager->persist($dailyLog);
        $this->entityManager->flush();

        return $dailyLog;
    }

    /**
     * @param DailyLog $dailyLog
     * @param string $description
     * @param Carbon $startDate
     * @param Carbon|null $endDate
     *
     * @return DailyLog
     */
    public function edit(DailyLog $dailyLog, string $description, Carbon $startDate, ?Carbon $endDate)
    {
        $dailyLog->description = $description;
        $dailyLog->startDate = $startDate;
        $dailyLog->endDate = $endDate;

        $this->entityManager->persist($dailyLog);
        $this->entityManager->flush();

        return $dailyLog;
    }

    public function delete(int $id)
    {
        if ($dailyLog = $this->get($id)) {
            $this->entityManager->remove($dailyLog);
            $this->entityManager->flush();
        }
    }
}
