<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Statistic;

class StatisticsMeasure
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            $this->recordStatistics($request);
        }
        
        return $response;
    }
    

    protected function recordStatistics(Request $request): void
    {
        $pageUrl = $request->fullUrl();
        Statistic::create([
            'page_url' => $pageUrl,
        ]);
    }
}