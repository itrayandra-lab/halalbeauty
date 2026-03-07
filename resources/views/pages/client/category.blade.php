@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $category->name }} - {{ $meta->web_name ?? 'Portal Berita' }}",
    "description": "Kumpulan berita dan artikel terkait {{ $category->name }} dari {{ $meta->web_name ?? 'Portal Berita' }}",
    "url": "{{ request()->url() }}",
    "datePublished": "{{ $category->created_at->toISOString() }}",
    "dateModified": "{{ $category->updated_at->toISOString() }}",
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
        "text": "Halaman kategori {{ $category->name }} berisi kumpulan berita dan artikel terkait topik {{ $category->name }}."
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
                "name": "{{ $category->name }}",
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
            "name": "Apa saja konten yang tersedia di kategori {{ $category->name }}?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Kategori {{ $category->name }} berisi berita terkini, artikel mendalam, dan informasi terpercaya seputar topik {{ $category->name }}."
            }
        },
        {
            "@type": "Question",
            "name": "Seberapa sering konten kategori {{ $category->name }} diperbarui?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Konten kategori {{ $category->name }} diperbarui secara berkala untuk memastikan pembaca mendapatkan informasi terbaru dan relevan."
            }
        }
    ]
}
</script>
@endpush

@section('header')
    @include('widget.client.header-section', [
        'segment' => 'Kategori',
        'data' => $category->name,
    ])
@endsection

@section('content')
    <!-- Banner -->
    @include('widget.client.banner', ['data' => $banner_1])
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-2">
            <div class="grid grid-cols-12 lg:gap-4">
                    @forelse ($posts as $item)
                    <div class="col-span-12 md:col-span-6 border-b border-stone-100 last:border-0 lg:border-0">
                        <div class="flex gap-5 py-6">
                            <div class="flex-shrink-0 w-12 text-center pt-1">
                                <span class="block text-2xl font-bold text-gray-700 leading-none" style="font-family:'Libre Baskerville',serif;">
                                    {{ \Carbon\Carbon::parse($item->published_at)->format('d') }}
                                </span>
                                <span class="block text-xs text-gray-400 uppercase tracking-wider mt-1">
                                    {{ \Carbon\Carbon::parse($item->published_at)->format('M') }}
                                </span>
                            </div>
                            <div class="flex flex-col flex-1 gap-1.5">
                                <span class="text-xs font-mono text-rose-400 uppercase tracking-widest">
                                    {{ $item->category->name ?? '—' }}
                                </span>
                                <a href="/{{ $item->category->slug }}/{{ $item->slug }}" class="group">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-rose-600 transition-colors leading-snug"
                                        style="font-family:'Libre Baskerville',serif;">
                                        {{ $item->title }}
                                    </h3>
                                </a>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    {{ implode(' ', array_slice(explode(' ', strip_tags($item->content ?? '')), 0, 20)) }}...
                                </p>
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
    <hr class="w-48 h-1 mx-auto my-4 bg-gray-100 border-0 rounded-sm md:my-10 ">
    @include('widget.client.paginate', ['data' => $posts])
    <!-- Terpopuler -->
    <section class="" style="padding:80px 0 80px 0 !important; background-color: #FAFAF9;">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            {{-- Section Title --}}
            <div style="margin-bottom:40px !important">
                <h1 class="text-3xl lg:text-4xl font-bold text-stone-800 tracking-tight whitespace-nowrap"
                    style="font-family:'Libre Baskerville',serif;">Terpopuler</h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-8">
                @foreach($mostPopular->take(6) as $item)
                <div class="flex gap-4 pb-8">
                    {{-- Rank number --}}
                    <span class="flex-shrink-0 text-5xl font-black leading-none select-none w-10 text-right"
                          style="font-family:'Libre Baskerville',serif; color:#D4C9B8;">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <div class="flex flex-col gap-1.5 flex-1">
                        <span class="text-xs font-mono text-rose-500 uppercase tracking-widest">
                            {{ $item->category->name ?? '—' }}
                        </span>
                        <a href="/{{ $item->category->slug }}/{{ $item->slug }}" class="group">
                            <h3 class="text-base font-bold text-stone-800 group-hover:text-rose-600 transition-colors leading-snug"
                                style="font-family:'Libre Baskerville',serif;">
                                {{ $item->title }}
                            </h3>
                        </a>
                        <span class="text-xs text-stone-400">
                            {{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('d M Y') }}
                            &nbsp;·&nbsp; {{ number_format($item->counter) }} dibaca
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
