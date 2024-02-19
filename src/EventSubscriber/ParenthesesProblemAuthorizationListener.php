<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ParenthesesProblemAuthorizationListener implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Check if the request has a Bearer token
        $authorizationHeader = $request->headers->get('Authorization');
        $token = substr($authorizationHeader, 7);
        $nextRequest = is_null($token) ? true : $this->areBracketsBalanced($token);

        if (!$nextRequest) {
            throw new AccessDeniedHttpException('Authorization Bearer token invalid');
        }
    }

    /**
     * Check if brackets are correctly balanced
     *
     * @param string $token Bearer token
     * @return bool
     */
    private function areBracketsBalanced(string $token): bool
    {
        $stack = [];
        $openingBrackets = ['(', '[', '{'];
        $closingBrackets = [')' => '(', ']' => '[', '}' => '{'];
    
        foreach (str_split($token) as $char) {
            if (in_array($char, $openingBrackets)) {
                $stack[] = $char;
            } elseif (in_array($char, array_keys($closingBrackets))) {
                if (empty($stack) || array_pop($stack) !== $closingBrackets[$char]) {
                    return false;
                }
            }
        }
    
        return empty($stack);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
