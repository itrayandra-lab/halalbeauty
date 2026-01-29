@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $banner->title }}",
    "description": "{{ Str::limit(strip_tags($banner->description ?? $banner->title), 160) }}",
    "url": "{{ request()->url() }}",
    "datePublished": "{{ $banner->created_at->toISOString() }}",
    "dateModified": "{{ $banner->updated_at->toISOString() }}",
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
        "text": "{{ Str::limit(strip_tags($banner->description ?? $banner->title), 500) }}"
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
                "name": "{{ $banner->title }}",
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
            "name": "Apa isi dari {{ $banner->title }}?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ Str::limit(strip_tags($banner->description ?? $banner->title), 200) }}"
            }
        },
        {
            "@type": "Question",
            "name": "Kapan konten ini dipublikasikan?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Konten ini dipublikasikan pada {{ $banner->created_at->format('d M Y') }}."
            }
        }
    ]
}
</script>
@endpush

@section('content')
    <div class="relative isolate -mt-8 overflow-hidden bg-white px-2 py-10 lg:py-20 lg:overflow-visible lg:px-0">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <svg class="absolute top-0 left-[max(50%,25rem)] h-[64rem] w-[128rem] -translate-x-1/2 stroke-gray-200 [mask-image:radial-gradient(64rem_64rem_at_top,white,transparent)]"
                aria-hidden="true">
                <defs>
                    <pattern id="e813992c-7d03-4cc4-a2bd-151760b470a0" width="200" height="200" x="50%" y="-1"
                        patternUnits="userSpaceOnUse">
                        <path d="M100 200V.5M.5 .5H200" fill="none" />
                    </pattern>
                </defs>
                <svg x="50%" y="-1" class="overflow-visible fill-gray-50">
                    <path
                        d="M-100.5 0h201v201h-201Z M699.5 0h201v201h-201Z M499.5 400h201v201h-201Z M-300.5 600h201v201h-201Z"
                        stroke-width="0" />
                </svg>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#e813992c-7d03-4cc4-a2bd-151760b470a0)" />
            </svg>
        </div>
        <div
            class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-2 lg:items-start lg:gap-y-10">
            <div
                class="lg:col-span-2 mb-3 lg:col-start-1 lg:row-start-1 lg:mx-auto lg:grid lg:w-full lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8">
                <div class="lg:pr-4">
                    <div class="lg:max-w-lg border-b-2 border-dashed border-gray-200 pb-3">
                        <a class="text-base/7 font-semibold text-blue-600">
                            Banner Informasi
                        </a>
                        <h1 class="mt-2 text-xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-3xl">
                            {{ $banner->title }}
                        </h1>
                        <div class="flex items-center justify-between mt-2 text-gray-600 text-sm">
                            <div class="flex items-center gap-2">
                                <img src="{{ $banner->createdBy->image ? getFile($banner->createdBy->image) : asset('dist/images/users/avatar-1.jpg') }}"
                                    alt="" class="w-8 h-8 rounded-full">
                                <a class="hover:text-blue-600">{{ $banner->createdBy->name }}</a>
                            </div>
                            <p>
                                {{ \Carbon\Carbon::parse($banner->created_at)->locale('id')->translatedFormat('l, d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="lg:-mt-1 -mt-15 lg:-ml-12 lg:p-3 lg:top-4 lg:col-start-2 lg:row-span-2 lg:row-start-1 lg:overflow-hidden image-container">
                <img class="w-full rounded-sm bg-gray-900 ring-1 shadow-lg ring-gray-400/10 sm:w-[57rem] post-image"
                    src="{{ getFile($banner->image) }}" alt="">
            </div>

            <div
                class="lg:col-span-2 -mt-10 lg:col-start-1 lg:row-start-2 lg:mx-auto lg:grid lg:w-full lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8 post-content">
                <div class="lg:pr-4">
                    <div class="max-w-xl text-base/7 text-gray-700 lg:max-w-lg">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:p-3">
        @include('widget.client.header-title', ['title' => 'Informasi Lainnya'])
        <div class="space-y-2">
            <div class="grid grid-cols-12 lg:gap-4 py-2">
                @forelse ($banners as $item)
                    <div class="col-span-12 lg:col-span-6">
                        <div class="mx-auto w-full rounded mb-2 relative group">
                            <div class="flex space-x-4">
                                <div class="w-full h-30 lg:h-50 rounded bg-gray-200 relative overflow-hidden">
                                    <img src="{{ getFile($item->image) }}" alt="{{ $item->title }}"
                                        class="w-full h-full object-cover rounded transition-opacity duration-300 group-hover:opacity-80">
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300">
                                        <a href="/banner/{{ $item->slug }}" title="detail banner"
                                            class="text-white bg-gray-700 p-1 rounded-full mr-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-12">
                        @include('widget.client.no-data-search')
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @media (min-width: 1024px) {
            .image-container {
                position: sticky;
                top: 1rem;
                transition: all 0.3s ease;
            }

            .post-image {
                max-height: calc(100vh - 2rem);
                object-fit: cover;
            }

            body:has(.most-popular:target) .image-container {
                position: relative;
                top: 0;
            }
        }
    </style>
@endpush
