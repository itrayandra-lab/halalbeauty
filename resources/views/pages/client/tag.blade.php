@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Tag: {{ $tag->name }} - {{ $meta->web_name ?? 'Portal Berita' }}",
    "description": "Kumpulan artikel dengan tag {{ $tag->name }} dari {{ $meta->web_name ?? 'Portal Berita' }}",
    "url": "{{ request()->url() }}",
    "datePublished": "{{ $tag->created_at->toISOString() }}",
    "dateModified": "{{ $tag->updated_at->toISOString() }}",
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
        "text": "Halaman tag {{ $tag->name }} berisi kumpulan artikel yang memiliki tag atau topik terkait {{ $tag->name }}."
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
                "name": "Tag",
                "item": "{{ url('/tag') }}"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "{{ $tag->name }}",
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
            "name": "Apa itu tag {{ $tag->name }}?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Tag {{ $tag->name }} adalah label yang digunakan untuk mengelompokkan artikel dengan topik atau tema yang serupa, memudahkan pembaca menemukan konten yang relevan."
            }
        },
        {
            "@type": "Question",
            "name": "Bagaimana cara menemukan artikel dengan tag tertentu?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Anda dapat mengklik tag yang tersedia di setiap artikel atau menggunakan halaman tag untuk menjelajahi artikel berdasarkan topik yang diminati."
            }
        }
    ]
}
</script>
@endpush

@section('header')
    @include('widget.client.header-section', [
        'segment' => 'Tag',
        'data' => $tag->name,
    ])
@endsection

@section('content')
    <!-- Banner -->
    @include('widget.client.banner', ['data' => $banner_1])
    <div class="space-y-2">
        <div class="grid grid-cols-12 lg:gap-2">
            @forelse ($posts as $item)
                <div class="col-span-12 md:col-span-6">
                    <div class="mx-auto w-full rounded-lg shadow-sm lg:p-4 p-2 mb-2">
                        <div class="flex space-x-4">
                            <div class="size-30 rounded-lg overflow-hidden">
                                <img src="{{ getFile($item->image) }}" alt="{{ $item->title }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 space-y-6">
                                <span class="text-blue-600 font-semibold lg:hidden">
                                    <a href="/{{ $item->category->slug }}"
                                        class="relative z-10 inline-flex items-center p-1 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 transition-colors duration-200 group">
                                        <span
                                            class="flex items-center justify-center w-3 h-3 mr-2 text-white bg-blue-600 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash">
                                                <line x1="4" x2="20" y1="9" y2="9" />
                                                <line x1="4" x2="20" y1="15" y2="15" />
                                                <line x1="10" x2="8" y1="3" y2="21" />
                                                <line x1="16" x2="14" y1="3" y2="21" />
                                            </svg>
                                        </span>
                                        <span class="mr-2">{{ $item->category->name }}</span>
                                        <svg class="w-3 h-3 ml-auto text-gray-400 group-hover:text-gray-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </span>
                                <br class="lg:hidden">
                                <a class="text-gray-700 font-semibold lg:text-lg text-xs hover:text-gray-600 transition-colors duration-200"
                                    href="/{{ $item->category->slug }}/{{ $item->slug }}">
                                    {{ $item->title }}
                                </a>
                                <div class="lg:space-y-5">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="col-span-2 h-2"></div>
                                        <div class="col-span-1 h-2"></div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <img src="{{ $item->createdBy->image ? getFile($item->createdBy->image) : asset('dist/images/users/avatar-1.jpg') }}"
                                                alt="Author" class="w-5 h-5 lg:w-10 lg:h-10 rounded-full">
                                            <div>
                                                <a href="/author/{{ $item->createdBy->slug }}"
                                                    class="text-gray-700 hover:text-blue-600 text-xs font-semibold">
                                                    {{ $item->createdBy->name }}
                                                </a>
                                                <p class="text-gray-500 text-xs lg:text-sm">
                                                    <time
                                                        datetime="{{ \Carbon\Carbon::parse($item->published_at)->toDateTimeString() }}"
                                                        class="text-gray-500">
                                                        {{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('l, d M Y') }}
                                                    </time>
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-blue-600 font-semibold hidden lg:block">
                                            <a href="/{{ $item->category->slug }}"
                                                class="relative z-10 inline-flex items-center p-1 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 transition-colors duration-200 group">
                                                <span
                                                    class="flex items-center justify-center w-3 h-3 mr-2 text-white bg-blue-600 rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-hash">
                                                        <line x1="4" x2="20" y1="9" y2="9" />
                                                        <line x1="4" x2="20" y1="15" y2="15" />
                                                        <line x1="10" x2="8" y1="3" y2="21" />
                                                        <line x1="16" x2="14" y1="3" y2="21" />
                                                    </svg>
                                                </span>
                                                <span class="mr-2">{{ $item->category->name }}</span>
                                                <svg class="w-3 h-3 ml-auto text-gray-400 group-hover:text-gray-600"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
              @include('widget.client.no-data-search')
            @endforelse


        </div>
    </div>
    <hr class="w-48 h-1 mx-auto my-4 bg-gray-100 border-0 rounded-sm md:my-10 ">
    @include('widget.client.paginate', ['data' => $posts])
    <!-- Terpopuler -->
    @include('widget.client.most-popular', ['data' => $mostPopular])
@endsection
