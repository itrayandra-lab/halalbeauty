<?php

namespace App\Helpers;

class StructuredDataHelper
{
    public static function generateArticleSchema($post, $author = null, $baseUrl = null)
    {
        $baseUrl = $baseUrl ?: config('app.url');
        $authorName = $author ? $author->name : 'Admin';
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->excerpt ?? strip_tags(substr($post->content, 0, 160)),
            'author' => [
                '@type' => 'Person',
                'name' => $authorName
            ],
            'datePublished' => $post->published_at ? $post->published_at->toISOString() : $post->created_at->toISOString(),
            'dateModified' => $post->updated_at->toISOString(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $baseUrl . '/post/' . $post->slug
            ],
            'image' => $post->image ? $baseUrl . '/storage/' . $post->image : null,
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'url' => $baseUrl
            ]
        ];
    }

    public static function generateFAQSchema($faqs)
    {
        $mainEntity = [];
        
        foreach ($faqs as $faq) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer']
                ]
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity
        ];
    }

    public static function generateHowToSchema($title, $description, $steps)
    {
        $stepEntities = [];
        
        foreach ($steps as $index => $step) {
            $stepEntities[] = [
                '@type' => 'HowToStep',
                'position' => $index + 1,
                'name' => $step['name'],
                'text' => $step['text']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => $title,
            'description' => $description,
            'step' => $stepEntities
        ];
    }

    public static function generateBreadcrumbSchema($breadcrumbs, $baseUrl = null)
    {
        $baseUrl = $baseUrl ?: config('app.url');
        $itemListElement = [];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $baseUrl . $breadcrumb['url']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement
        ];
    }

    public static function generateWebsiteSchema($baseUrl = null)
    {
        $baseUrl = $baseUrl ?: config('app.url');
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => $baseUrl,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $baseUrl . '/search?q={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    public static function generateOrganizationSchema($baseUrl = null)
    {
        $baseUrl = $baseUrl ?: config('app.url');
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => $baseUrl,
            'logo' => $baseUrl . '/assets/img/logo.png',
            'sameAs' => []
        ];
    }

    public static function renderJsonLd($schema)
    {
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }

    public static function extractFAQFromContent($content)
    {
        $faqs = [];
        
        if (preg_match_all('/<h[3-6][^>]*>(.*?)<\/h[3-6]>/i', $content, $questions)) {
            foreach ($questions[1] as $index => $question) {
                if (strpos(strtolower($question), '?') !== false || 
                    strpos(strtolower($question), 'apa') !== false ||
                    strpos(strtolower($question), 'bagaimana') !== false ||
                    strpos(strtolower($question), 'mengapa') !== false ||
                    strpos(strtolower($question), 'kapan') !== false ||
                    strpos(strtolower($question), 'dimana') !== false) {
                    
                    $pattern = '/<h[3-6][^>]*>' . preg_quote($question, '/') . '<\/h[3-6]>(.*?)(?=<h[1-6]|$)/is';
                    if (preg_match($pattern, $content, $answerMatch)) {
                        $answer = strip_tags($answerMatch[1]);
                        $answer = trim(preg_replace('/\s+/', ' ', $answer));
                        
                        if (strlen($answer) > 20) {
                            $faqs[] = [
                                'question' => strip_tags($question),
                                'answer' => $answer
                            ];
                        }
                    }
                }
            }
        }
        
        return $faqs;
    }

    public static function extractHowToFromContent($content)
    {
        $steps = [];
        
        if (preg_match_all('/<(?:ol|ul)[^>]*>(.*?)<\/(?:ol|ul)>/is', $content, $lists)) {
            foreach ($lists[1] as $list) {
                if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $list, $items)) {
                    foreach ($items[1] as $index => $item) {
                        $stepText = strip_tags($item);
                        $stepText = trim(preg_replace('/\s+/', ' ', $stepText));
                        
                        if (strlen($stepText) > 10) {
                            $steps[] = [
                                'name' => 'Langkah ' . ($index + 1),
                                'text' => $stepText
                            ];
                        }
                    }
                    
                    if (count($steps) >= 3) {
                        break;
                    }
                }
            }
        }
        
        return $steps;
    }
}