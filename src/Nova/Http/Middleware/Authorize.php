<?php

namespace GreenBar\MenuBuilder\Nova\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use GreenBar\MenuBuilder\Nova\MenuBuilderTool;

class Authorize
{
    public function handle(Request $request, Closure $next): Response
    {
        return app(MenuBuilderTool::class)->authorize($request)
            ? $next($request)
            : abort(403);
    }
}
