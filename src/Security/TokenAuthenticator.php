<?php

namespace App\Security;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{

   /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authorization = $request->headers->get('Authorization');
        $apiToken = substr($authorization, 7);
        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('No API Authorization token provided');
        }

        if (!$this->areBracketsBalanced($apiToken)) {
            throw new CustomUserMessageAuthenticationException('Invalid API Authorization token');
        }

        // implement your own logic to get the user identifier from `$apiToken`
        // e.g. by looking up a user in the database using its API key
        $userIdentifier = ''/** ... */;

        return new SelfValidatingPassport(new UserBadge('user'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Check if brackets are correct balanced
     *
     * @param string $token Bearer token
     * @return bool
     */
    public function areBracketsBalanced(string $token) : bool
    {
		$stack = [];
        for ($i=0; $i < strlen($token); $i++) {
            $x = $token[$i];
            if (in_array($x, ['(', '[', '{'])) {
                $stack[] = $x;
                continue;
            }
            if (empty($stack)|| !in_array($x, ['(', '[', '{', '}',']',')'])) {
                return false;
            }
            switch ($x) {
                case ')':
                    if (in_array(array_pop($stack), ['{', '['])) {
                        return false;
                    }
                    break;
                case '}':
                    if (in_array(array_pop($stack), ['(', '['])) {
                        return false;
                    }
                    break;
                case ']':
                    if (in_array(array_pop($stack), ['(', '{'])) {
                        return false;
                    }
                    break;
            }
        }
        return empty($stack);
	}
}
