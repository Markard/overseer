<?php

namespace App\TimeTracker\Application\Input;

use Carbon\Carbon;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

final class DailyLog
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="255")
     */
    public $description;

    /**
     * In RFC3339 format
     *
     * @var string
     *
     * @Serializer\Type("DateTime<'Y-m-d\TH:i:sP', 'UTC'>")
     *
     * @Assert\DateTime(format="Y-m-d\TH:i:sP")
     * @Assert\NotNull()
     */
    public $startDate;

    /**
     * In RFC3339 format
     *
     * @var string
     *
     * @Serializer\Type("DateTime<'Y-m-d\TH:i:sP', 'UTC'>")
     *
     * @Assert\DateTime(format="Y-m-d\TH:i:sP")
     */
    public $endDate;

    public function __construct(string $description, string $startDate, ?string $endDate)
    {
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return Carbon
     */
    public function getStartDateAsCarbon()
    {
        return Carbon::createFromFormat(DATE_RFC3339, $this->startDate);
    }

    /**
     * @return null|Carbon
     */
    public function getEndDateAsCarbon()
    {
        return $this->endDate === null ? null : Carbon::createFromFormat(DATE_RFC3339, $this->endDate);
    }
}
