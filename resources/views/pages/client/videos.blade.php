@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Video - {{ $meta->web_name ?? 'Portal Berita' }}",
    "description": "Kumpulan video informatif dan menarik dari {{ $meta->web_name ?? 'Portal Berita' }}",
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
        "text": "Halaman video berisi kumpulan konten video informatif dan menarik dari berbagai topik."
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
                "name": "Video",
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
            "name": "Jenis video apa saja yang tersedia?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Tersedia berbagai jenis video informatif, berita video, tutorial, dan konten multimedia lainnya yang relevan dan menarik."
            }
        },
        {
            "@type": "Question",
            "name": "Bagaimana cara menonton video?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Klik pada thumbnail video yang ingin ditonton untuk membuka halaman detail video dan mulai memutar konten."
            }
        }
    ]
}
</script>
@endpush

@section('header')
    @include('widget.client.header-section', [
        'segment' => 'Halaman',
        'data' => 'Video',
    ])
@endsection

@section('content')
    <div class="space-y-2">
        <div class="grid grid-cols-12 lg:gap-2">
            @forelse ($videos as $item)
                <div class="col-span-12 md:col-span-6">
                    <div class="mx-auto w-full bg-white shadow-sm border border-slate-200 rounded-lg p-2 mb-2">
                        <div class="flex space-x-3">
                            <a href="{{ route('video_detail', $item->slug) }}"
                                class="lg:w-40 w-30  object-cover rounded-lg overflow-hidden">
                                <img src="{{ getFile($item->image) }}" alt="{{ $item->title }}"
                                    class="w-full h-full object-cover">
                            </a>
                            <div class="flex-1 space-y-5">
                                <a class="text-gray-700 font-semibold lg:text-lg text-xs hover:text-gray-600 transition-colors duration-200"
                                    href="{{ route('video_detail', $item->slug) }}">
                                    {{ $item->title }}
                                </a>
                                <div class="lg:space-y-11 space-y-7">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="col-span-2 h-2"></div>
                                        <div class="col-span-1 h-2"></div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <img src="{{ $item->createdBy->image ? getFile($item->createdBy->image) : asset('dist/images/users/avatar-1.jpg') }}"
                                                alt="Author" class="w-5 h-5 lg:w-7 lg:h-7 rounded-full">
                                            <div>
                                                <a class="text-gray-700 hover:text-blue-600 text-xs font-semibold">
                                                    {{ $item->createdBy->name }}
                                                </a>
                                            </div>
                                        </div>
                                        <p class="text-gray-500 text-xs lg:text-sm text-right">
                                            <small class="text-gray-500">
                                                {{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('l, d M Y') }}
                                            </small>
                                        </p>
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
    @include('widget.client.paginate', ['data' => $videos])
    @include('widget.client.banner', ['data' => $banner_1])
@endsection
