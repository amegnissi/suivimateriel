<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class UrlFichier
{
    public function __construct(
        private RequestStack $requestStack,
        private string $basePath = '/uploads'
    ) {}

    public function generate(string $relativePath): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return null;
        }

        return $request->getSchemeAndHttpHost() . $request->getBasePath() . $this->basePath . '/' . ltrim($relativePath, '/');
    }
}
