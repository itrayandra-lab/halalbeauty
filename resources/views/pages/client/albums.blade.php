@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Album - {{ $meta->web_name ?? 'Portal Berita' }}",
    "description": "Kumpulan album foto dan galeri gambar dari {{ $meta->web_name ?? 'Portal Berita' }}",
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
        "text": "Halaman album berisi kumpulan foto dan galeri gambar dari berbagai acara dan momen penting."
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
                "name": "Album",
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
            "name": "Apa saja yang ada di album foto?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Album foto berisi dokumentasi berbagai acara, kegiatan, dan momen penting yang telah diabadikan dalam bentuk galeri gambar."
            }
        },
        {
            "@type": "Question",
            "name": "Bagaimana cara melihat foto dalam album?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Klik pada album yang ingin dilihat untuk membuka galeri foto dan menjelajahi koleksi gambar yang tersedia."
            }
        }
    ]
}
</script>
@endpush

@section('header')
    @include('widget.client.header-section', [
        'segment' => 'Halaman',
        'data' => 'Album Foto',
    ])
@endsection

@section('content')
    <div class="space-y-2">
        @forelse ($albums as $item)
            <div class="header-title  items-center border-b-2 border-dashed border-gray-200 pb-4 mb-4">
                <h2 class="lg:text-lg text-sm font-bold relative">
                    {{ $item->name }}
                    <svg width="100" height="3" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#0A4B94" d="M0 0h48v4H0z"></path>
                        <path fill="#1E6FBA" d="M52 0h16v4H52z"></path>
                        <path fill="#3B9AE1" d="M72 0h8v4h-8z"></path>
                        <path fill="#7CC1F5" d="M84 0h4v4h-4z"></path>
                        <path fill="#A8D8F9" d="M90 0h4v4h-4z"></path>
                        <path fill="#D4EBFC" d="M96 0h4v4h-4z"></path>
                        <path fill="#E8F4FE" d="M102 0h4v4h-4z"></path>
                        <path fill="#F5FAFF" d="M108 0h4v4h-4z"></path>
                    </svg>
                </h2>
                <p href="" class="text-sm text-gray-500 ">
                    {{ $item->description }}
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 md:grid-cols-5">
                @foreach ($item->photos as $row)
                    <div>
                        <a href="{{ getFile($row->image) }}"
                            data-lightbox="{{ $row->album_id }}" data-title="{{ $item->name ?? 'Galery Image' }}">
                            <img class="object-cover object-center w-full h-40 max-w-full rounded-lg"
                                src="{{ getFile($row->image) }}" alt="gallery-photo" />
                        </a>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="col-span-12">
                @include('widget.client.no-data-search')
            </div>
        @endforelse
    </div>
    <hr class="w-48 h-1 mx-auto my-4 bg-gray-100 border-0 rounded-sm md:my-10 ">
    @include('widget.client.paginate', ['data' => $albums])
@endsection


@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush
