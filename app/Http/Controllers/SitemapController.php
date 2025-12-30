<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Response;

/**
 * サイトマップコントローラー
 * SEO用のXMLサイトマップを生成
 */
class SitemapController extends Controller
{
    /**
     * サイトマップXMLを生成
     */
    public function index(): Response
    {
        // アクティブなイベントを取得（直近100件）
        $events = Event::active()
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        $xml = $this->generateXml($events);

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * XMLを生成
     */
    private function generateXml($events): string
    {
        $baseUrl = config('app.url');
        $now = now()->toW3cString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // 静的ページ
        $staticPages = [
            ['loc' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => '/events/create', 'priority' => '0.9', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $xml .= $this->generateUrlEntry(
                $baseUrl . $page['loc'],
                $now,
                $page['changefreq'],
                $page['priority']
            );
        }

        // イベントページ
        foreach ($events as $event) {
            $xml .= $this->generateUrlEntry(
                route('events.show', $event->uuid),
                $event->updated_at->toW3cString(),
                'daily',
                '0.7'
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * URL エントリーを生成
     */
    private function generateUrlEntry(
        string $loc,
        string $lastmod,
        string $changefreq,
        string $priority
    ): string {
        return "  <url>\n" .
            "    <loc>{$loc}</loc>\n" .
            "    <lastmod>{$lastmod}</lastmod>\n" .
            "    <changefreq>{$changefreq}</changefreq>\n" .
            "    <priority>{$priority}</priority>\n" .
            "  </url>\n";
    }
}
