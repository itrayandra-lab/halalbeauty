@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SearchResultsPage",
    "name": "Hasil Pencarian: {{ request('q') }} - {{ $meta->web_name ?? 'Portal Berita' }}",
    "description": "Hasil pencarian untuk '{{ request('q') }}' di {{ $meta->web_name ?? 'Portal Berita' }}",
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
        "text": "Halaman hasil pencarian untuk kata kunci '{{ request('q') }}' menampilkan artikel dan berita yang relevan."
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
                "name": "Pencarian",
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
            "name": "Bagaimana cara mencari artikel di situs ini?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Gunakan kotak pencarian di bagian atas halaman dan masukkan kata kunci yang ingin Anda cari. Sistem akan menampilkan artikel yang relevan dengan kata kunci tersebut."
            }
        },
        {
            "@type": "Question",
            "name": "Apakah pencarian mencakup semua konten?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Ya, pencarian mencakup judul artikel, konten, dan tag untuk memberikan hasil yang komprehensif dan relevan."
            }
        }
    ]
}
</script>
@endpush

@section('header')
    <section class="shadow-sm shadow-gray-50"
        style="background: radial-gradient(circle, transparent 20%, #ffffff 20%, #ffffff 80%, transparent 80%, transparent) 0% 0% / 64px 64px, radial-gradient(circle, transparent 20%, #ffffff 20%, #ffffff 80%, transparent 80%, transparent) 32px 32px / 64px 64px, linear-gradient(#f2f2f2 2px, transparent 2px) 0px -1px / 32px 32px, linear-gradient(90deg, #f2f2f2 2px, #ffffff 2px) -1px 0px / 32px 32px #ffffff; background-size: 64px 64px, 64px 64px, 32px 32px, 32px 32px; background-color: #ffffff;">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div
                class="header-title flex flex-col justify-between items-start border-b-2 border-dashed border-gray-200 pb-4 mb-4">
                <div class="text-sm text-gray-500 flex items-center mb-2">
                    Pencarian
                    <span class="mx-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </span>
                    {{ request('qr') }}
                </div>

                <div class="flex justify-between items-center w-full">
                    <div class="relative">
                        <h2 class="lg:text-2xl text-xl font-bold text-gray-900">
                            {{ $posts->count() }} Hasil Pencarian
                        </h2>
                        <svg width="180" class="mt-1" height="6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#0A4B94" d="M0 0h48v4H0z"></path>
                            <path fill="#1E6FBA" d="M52 0h16v4H52z"></path>
                            <path fill="#3B9AE1" d="M72 0h8v4h-8z"></path>
                            <path fill="#7CC1F5" d="M84 0h4v4h-4z"></path>
                            <path fill="#A8D8F9" d="M90 0h4v4h-4z"></path>
                            <path fill="#D4EBFC" d="M96 0h4v4h-4z"></path>
                            <path fill="#E8F4FE" d="M102 0h4v4h-4z"></path>
                            <path fill="#F5FAFF" d="M108 0h4v4h-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
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
                                                        <line x1="4" x2="20" y1="9"
                                                            y2="9" />
                                                        <line x1="4" x2="20" y1="15"
                                                            y2="15" />
                                                        <line x1="10" x2="8" y1="3"
                                                            y2="21" />
                                                        <line x1="16" x2="14" y1="3"
                                                            y2="21" />
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
