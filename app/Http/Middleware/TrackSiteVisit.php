<?php

namespace App\Http\Middleware;

use App\Support\SiteTraffic;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function __construct(private SiteTraffic $traffic) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->traffic->record($request);

        return $next($request);
    }
}
