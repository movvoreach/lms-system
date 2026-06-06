<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\TelegramActionAlertService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SendTelegramActionAlert
{
    private const EXCLUDED_ROUTES = [
        'login.store',
        'two-factor.verify',
        'two-factor.resend',
    ];

    public function __construct(private readonly TelegramActionAlertService $telegram)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $actor = $request->user();

        $response = $next($request);

        if ($this->shouldAlert($request, $response)) {
            $this->telegram->sendForRequest(
                $request,
                $response->getStatusCode(),
                $actor instanceof User ? $actor : null
            );
        }

        return $response;
    }

    private function shouldAlert(Request $request, Response $response): bool
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return false;
        }

        if ($response->getStatusCode() >= 500) {
            return false;
        }

        $routeName = $request->route()?->getName();

        return filled($routeName) && ! in_array($routeName, self::EXCLUDED_ROUTES, true);
    }
}
