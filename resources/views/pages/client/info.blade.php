@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Informasi - {{ $meta->web_name ?? 'Portal Berita' }}",
    "description": "Halaman informasi dan pengumuman penting dari {{ $meta->web_name ?? 'Portal Berita' }}",
    "url": "{{ request()->url() }}",
    "datePublished": "{{ now()->toISOString() }}",
    "dateModified": "{{ now()->toISOString() }}",
    "publisher": {
        "@type": "Organization",
        "name": "{{ $meta->web_name ?? 'Portal Berita' }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ $meta->logo ? getFile($meta->logo) : '' }}"
        }
    },
    "mainContentOfPage": {
        "@type": "WebPageElement",
        "text": "Halaman informasi berisi pengumuman, kebijakan, dan informasi penting lainnya."
    },
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Beranda",
                "item": "{{ url('/') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "Informasi",
                "item": "{{ request()->url() }}"
            }
        ]
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "Informasi apa saja yang tersedia?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Tersedia berbagai informasi penting seperti pengumuman, kebijakan, panduan, dan informasi lainnya yang relevan untuk pembaca."
            }
        },
        {
            "@type": "Question",
            "name": "Seberapa sering informasi diperbarui?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Informasi diperbarui secara berkala sesuai kebutuhan untuk memastikan pembaca mendapatkan informasi yang akurat dan terkini."
            }
        }
    ]
}
</script>
@endpush

@section('header')
    @include('widget.client.header-section', [
        'segment' => 'Halaman',
        'data' => 'Informasi',
    ])
@endsection

@section('content')
    <div class="space-y-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
            @forelse ($info as $item)
                <div class="border-b border-dashed border-gray-300 p-2">
                    <div class="flex space-x-4 lg:text-2xl">
                        <div class="rounded overflow-hidden">
                            <img src="{{ asset('assets/img/informasi.png') }}" alt="" class="lg:w-10 w-7"
                                srcset="">
                        </div>
                        <div class="flex-1 flex flex-col justify-between ">
                            <div class="rounded">
                                <a class="text-gray-700  font-semibold hover:text-gray-600 transition-colors duration-200"
                                    href="/info/{{ $item->slug }}">
                                    {{ $item->title }}
                                </a>
                            </div>
                            <div class="flex items-center justify-between gap-x-4 text-xs mt-2">
                                <time datetime="{{ \Carbon\Carbon::parse($item->published_at)->toDateTimeString() }}"
                                    class="text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('l, d M Y') }}
                                </time>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2">
                    @include('widget.client.no-data-search')
                </div>
            @endforelse
        </div>
    </div>
    <hr class="w-48 h-1 mx-auto my-4 bg-gray-100 border-0 rounded-sm md:my-10 ">
    @include('widget.client.paginate', ['data' => $info])
@endsection
