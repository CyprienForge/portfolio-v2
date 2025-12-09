<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class HttpsRedirectSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 100],
            KernelEvents::RESPONSE => ['onKernelResponse', -100],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $host = $request->getHost();
        
        // ÉTAPE 1 : Rediriger www vers non-www (priorité)
        if (str_starts_with($host, 'www.')) {
            $newHost = substr($host, 4); // Enlève "www."
            $url = 'https://' . $newHost . $request->getRequestUri();
            $event->setResponse(new RedirectResponse($url, 301));
            return;
        }
        
        // ÉTAPE 2 : Force HTTPS
        if (!$request->isSecure() && 
            $request->headers->get('X-Forwarded-Proto') !== 'https') {
            
            $url = 'https://' . $host . $request->getRequestUri();
            $event->setResponse(new RedirectResponse($url, 301));
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();
        
        // CRITIQUE : Ajoute le header HSTS pour HTTPS preload
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains; preload'
        );
    }
}
