<?php

namespace App\TimeTracker\Application;

use App\TimeTracker\Application\Input\DailyLog;
use App\TimeTracker\Storage;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class CrudController
{
    /**
     * @var Storage
     */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function listDailyLogs()
    {
        return View::create($this->storage->getAll());
    }

    /**
     * @param int $id
     *
     * @return View
     */
    public function showDailyLog($id)
    {
        if (!$entity = $this->storage->get($id)) {
            throw new NotFoundHttpException("Daily log with id: {$id} not found.");
        }

        return View::create($entity);
    }

    /**
     * @ParamConverter(name="request", converter="App\TimeTracker\Application\Input\RequestConverter")
     *
     * @param DailyLog $request
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return View
     */
    public function createDailyLog(DailyLog $request, ConstraintViolationListInterface $validationErrors)
    {
        if ($validationErrors->count()) {
            return View::create(['errors' => $validationErrors], Response::HTTP_BAD_REQUEST);
        }
        $entity = $this->storage->create(
            $request->description,
            $request->getStartDateAsCarbon(),
            $request->getEndDateAsCarbon()
        );

        return View::create($entity);
    }

    /**
     * @ParamConverter(name="request", converter="App\TimeTracker\Application\Input\RequestConverter")
     *
     * @param int $id
     * @param DailyLog $request
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return View
     */
    public function editDailyLog($id, DailyLog $request, ConstraintViolationListInterface $validationErrors)
    {
        if ($validationErrors->count()) {
            return View::create(['errors' => $validationErrors], Response::HTTP_BAD_REQUEST);
        }
        if (!$entity = $this->storage->get($id)) {
            throw new NotFoundHttpException("Daily log with id: {$id} not found.");
        }

        $entity = $this->storage->edit(
            $entity,
            $request->description,
            $request->getStartDateAsCarbon(),
            $request->getEndDateAsCarbon()
        );

        return View::create($entity);
    }

    /**
     * @param int $id
     *
     * @return View
     */
    public function deleteDailyLog($id)
    {
        $this->storage->delete($id);

        return View::create();
    }
}
