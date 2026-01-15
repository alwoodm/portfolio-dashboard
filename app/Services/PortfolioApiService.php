<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PortfolioApiService
{
    /**
     * Check if portfolio API is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty(config('portfolio.url')) && ! empty(config('portfolio.admin_token'));
    }

    /**
     * Get the content URL for a specific file.
     */
    public function getContentUrl(string $file): ?string
    {
        $baseUrl = config('portfolio.url');
        $prefix = config('portfolio.content_prefix', '/api/content');

        if (empty($baseUrl)) {
            return null;
        }

        return rtrim($baseUrl, '/') . $prefix . '/' . $file;
    }

    /**
     * Check the health of the portfolio API.
     *
     * @return array{status: string, response_time: int|null, error: string|null, timestamp: Carbon}
     */
    public function checkHealth(): array
    {
        if (! $this->isConfigured()) {
            return [
                'status' => 'not_configured',
                'response_time' => null,
                'error' => 'Portfolio URL or admin token not configured',
                'timestamp' => now(),
            ];
        }

        $url = $this->getContentUrl('home');
        $startTime = microtime(true);

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->withHeaders(['x-admin-token' => config('portfolio.admin_token')])
                ->get($url);

            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                return [
                    'status' => 'connected',
                    'response_time' => $responseTime,
                    'error' => null,
                    'timestamp' => now(),
                ];
            }

            return [
                'status' => 'error',
                'response_time' => $responseTime,
                'error' => 'API returned status ' . $response->status(),
                'timestamp' => now(),
            ];
        } catch (Exception $e) {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            return [
                'status' => 'error',
                'response_time' => $responseTime,
                'error' => $e->getMessage(),
                'timestamp' => now(),
            ];
        }
    }

    /**
     * Fetch content from the portfolio API.
     *
     * @return array{success: bool, data: array|null, error: string|null}
     */
    public function fetchContent(string $file): array
    {
        $url = $this->getContentUrl($file);

        if ($url === null) {
            return [
                'success' => false,
                'data' => null,
                'error' => 'Portfolio URL not configured',
            ];
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->withHeaders(['x-admin-token' => config('portfolio.admin_token')])
                ->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'error' => null,
                ];
            }

            return [
                'success' => false,
                'data' => null,
                'error' => 'API returned status ' . $response->status(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get content statistics from the portfolio API.
     *
     * @return array{projects: int, skills: int, career: int, error: string|null}
     */
    public function getContentStats(): array
    {
        $stats = [
            'projects' => 0,
            'skills' => 0,
            'career' => 0,
            'error' => null,
        ];

        if (! $this->isConfigured()) {
            $stats['error'] = 'Portfolio not configured';

            return $stats;
        }

        // Fetch projects count (items array)
        $projects = $this->fetchContent('projects');
        if ($projects['success'] && isset($projects['data']['items'])) {
            $stats['projects'] = count($projects['data']['items']);
        }

        // Fetch skills count (count individual skills, not categories)
        $skills = $this->fetchContent('skills');
        if ($skills['success'] && isset($skills['data']['categories'])) {
            foreach ($skills['data']['categories'] as $category) {
                if (isset($category['items']) && is_array($category['items'])) {
                    $stats['skills'] += count($category['items']);
                }
            }
        }

        // Fetch career count
        $career = $this->fetchContent('career');
        if ($career['success'] && isset($career['data']['timeline'])) {
            $stats['career'] = count($career['data']['timeline']);
        }

        // If all fetches failed, report an error
        if (! $projects['success'] && ! $skills['success'] && ! $career['success']) {
            $stats['error'] = $projects['error'] ?? 'Failed to fetch content';
        }

        return $stats;
    }
}
