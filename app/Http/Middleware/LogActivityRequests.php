<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivityRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->user() || ! $response->isSuccessful()) {
            return $response;
        }

        if ($this->hasUploadedFiles($request->allFiles())) {
            activity_log('upload', $this->moduleName($request), 'User uploaded one or more files.');
        }

        $contentDisposition = (string) $response->headers->get('content-disposition');

        if (str_contains(strtolower($contentDisposition), 'attachment')) {
            activity_log('download', $this->moduleName($request), 'User downloaded a file or exported data.');
        }

        return $response;
    }

    private function hasUploadedFiles(array $files): bool
    {
        foreach ($files as $file) {
            if (is_array($file) && $this->hasUploadedFiles($file)) {
                return true;
            }

            if ($file) {
                return true;
            }
        }

        return false;
    }

    private function moduleName(Request $request): string
    {
        $routeName = $request->route()?->getName();

        return $routeName ? str($routeName)->replace('.', ' ')->title()->toString() : 'System';
    }
}
