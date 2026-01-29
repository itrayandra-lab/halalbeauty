@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $info->title }}",
    "description": "{{ Str::limit(strip_tags($info->content), 160) }}",
    "url": "{{ request()->url() }}",
    "datePublished": "{{ $info->created_at->toISOString() }}",
    "dateModified": "{{ $info->updated_at->toISOString() }}",
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
        "text": "{{ Str::limit(strip_tags($info->content), 500) }}"
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
                "item": "{{ url('/info') }}"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "{{ $info->title }}",
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
            "name": "Apa isi dari {{ $info->title }}?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ Str::limit(strip_tags($info->content), 200) }}"
            }
        },
        {
            "@type": "Question",
            "name": "Kapan informasi ini terakhir diperbarui?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Informasi ini terakhir diperbarui pada {{ $info->updated_at->format('d M Y') }} untuk memastikan akurasi dan relevansi."
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

        <div class="mx-auto grid grid-cols-1 gap-x-8 gap-y-16 lg:mx-0 lg:grid-cols-1 lg:items-start lg:gap-y-10">
            <!-- Header Section -->
            <div class="mb-3 lg:mx-auto lg:w-full lg:px-8">
                <div class="lg:pr-4">
                    <div class="border-b-2 border-dashed border-gray-200 pb-3">
                        <a class="text-base/7 font-semibold text-blue-600">
                            Informasi Tertulis
                        </a>
                        <h1 class="mt-2 text-xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-3xl">
                            {{ $info->title }}
                        </h1>
                        <div class="flex items-center justify-between mt-2 text-gray-600 text-sm">
                            <div class="flex items-center gap-2">
                                <img src="{{ $info->createdBy->image ? getFile($info->createdBy->image) : asset('dist/images/users/avatar-1.jpg') }}"
                                    alt="" class="w-8 h-8 rounded-full">
                                <a class="hover:text-blue-600">{{ $info->createdBy->name }}</a>
                            </div>
                            <p>
                                {{ \Carbon\Carbon::parse($info->created_at)->locale('id')->translatedFormat('l, d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="-mt-10 lg:mx-auto lg:w-full lg:px-8 post-content">
                <div class="lg:pr-4">
                    <div class="text-base/7 text-gray-700">
                        {!! $info->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information Section -->
    <div class="col-span-12 lg:p-3">
        @include('widget.client.header-title', ['title' => 'Informasi Lainnya'])
        <div class="space-y-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
                @forelse ($information as $item)
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
    </div>
@endsection

@push('styles')
@endpush
