<?php

namespace App\TimeTracker;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="daily_log")
 */
final class DailyLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    public $description;

    /**
     * @ORM\Column(type="datetime", name="start_date", nullable=false)
     *
     * @var DateTime
     */
    public $startDate;

    /**
     * @ORM\Column(type="datetime", name="end_date", nullable=true)
     *
     * @var null|DateTime
     */
    public $endDate;

    public function __construct(string $description, DateTime $startDate, ?DateTime $endDate)
    {
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
