<?php

namespace App\TimeTracker\Application\Input;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RequestConverter implements ParamConverterInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $httpRequest, ParamConverter $configuration)
    {
        $request = new DailyLog(
            $httpRequest->request->get('description'),
            $httpRequest->request->get('startDate'),
            $httpRequest->request->get('endDate')
        );
        $this->validateAndApply($request, $httpRequest, $configuration);
    }

    /**
     * @param object $request - Custom request object
     * @param Request $httpRequest
     * @param ParamConverter $configuration
     */
    private function validateAndApply($request, Request $httpRequest, ParamConverter $configuration)
    {
        $httpRequest->attributes->set($configuration->getName(), $request);
        $errors = $this->validator->validate($request);
        $httpRequest->attributes->set('validationErrors', $errors);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === DailyLog::class;
    }
}
