<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Country;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function show(): Response
    {
        // 1 saat cache — kampanya/haber eklendikçe yenileniyor.
        $xml = Cache::remember('sitemap.xml', 3600, fn () => $this->build());

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $body = collect([
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /donate/thank-you/',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
        ])->implode("\n");

        return response($body, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    private function build(): string
    {
        $urls = collect();

        // Statik sayfalar
        foreach ([
            ['', '1.0', 'daily'],
            ['/calismalarimiz', '0.9', 'daily'],
            ['/nerelerde-calisiyoruz', '0.7', 'weekly'],
            ['/etki-ve-guvence', '0.6', 'monthly'],
            ['/hakkimizda', '0.6', 'monthly'],
            ['/iletisim', '0.5', 'monthly'],
            ['/haberler', '0.7', 'daily'],
            ['/gonullu-ol', '0.5', 'monthly'],
            ['/yardim-talebi', '0.5', 'monthly'],
            ['/insan-kaynaklari', '0.5', 'monthly'],
        ] as [$path, $priority, $changefreq]) {
            $urls->push([
                'loc'        => url($path),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => $changefreq,
                'priority'   => $priority,
            ]);
        }

        Campaign::active()->select('slug', 'updated_at')->chunk(200, function ($rows) use ($urls) {
            foreach ($rows as $c) {
                $urls->push([
                    'loc'        => route('campaigns.show', $c->slug),
                    'lastmod'    => $c->updated_at?->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority'   => '0.8',
                ]);
            }
        });

        Post::published()->select('slug', 'updated_at', 'published_at')->chunk(200, function ($rows) use ($urls) {
            foreach ($rows as $p) {
                $urls->push([
                    'loc'        => route('posts.show', $p->slug),
                    'lastmod'    => ($p->updated_at ?? $p->published_at)?->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority'   => '0.6',
                ]);
            }
        });

        Country::active()->select('code', 'updated_at')->get()->each(function ($c) use ($urls) {
            $urls->push([
                'loc'        => route('countries.show', strtolower($c->code)),
                'lastmod'    => $c->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority'   => '0.5',
            ]);
        });

        Page::published()->select('slug', 'updated_at')->get()->each(function ($p) use ($urls) {
            $urls->push([
                'loc'        => url('/sayfa/' . $p->slug),
                'lastmod'    => $p->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority'   => '0.4',
            ]);
        });

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc']) . "</loc>\n";
            $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        return $xml;
    }
}
