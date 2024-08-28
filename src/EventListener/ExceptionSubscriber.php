<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): \Throwable
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationFailedException) {
            $errors = [];

            foreach ($exception->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            if ('cli' === PHP_SAPI) {
                // Handle
            } else {
                $event->setResponse(new JsonResponse(['message' => 'Validation Error!', 'errors' => $errors], Response::HTTP_BAD_REQUEST));
            }
        }

        return $exception;
    }
}