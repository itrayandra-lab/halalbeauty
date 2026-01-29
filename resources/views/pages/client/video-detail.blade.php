@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "VideoObject",
    "name": "{{ $video->title }}",
    "description": "{{ Str::limit(strip_tags($video->description ?? $video->title), 160) }}",
    "thumbnailUrl": "{{ $video->thumbnail ? getFile($video->thumbnail) : '' }}",
    "uploadDate": "{{ $video->created_at->toISOString() }}",
    "duration": "PT{{ $video->duration ?? '0' }}S",
    "publisher": {
        "@type": "Organization",
        "name": "{{ $meta->web_name ?? 'Portal Berita' }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ $meta->logo ? getFile($meta->logo) : '' }}"
        }
    },
    "contentUrl": "{{ $video->video_url ?? request()->url() }}",
    "embedUrl": "{{ $video->embed_url ?? request()->url() }}"
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $video->title }} - Video",
    "description": "{{ Str::limit(strip_tags($video->description ?? $video->title), 160) }}",
    "url": "{{ request()->url() }}",
    "datePublished": "{{ $video->created_at->toISOString() }}",
    "dateModified": "{{ $video->updated_at->toISOString() }}",
    "publisher": {
        "@type": "Organization",
        "name": "{{ $meta->web_name ?? 'Portal Berita' }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ $meta->logo ? getFile($meta->logo) : '' }}"
        }
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
                "item": "{{ url('/video') }}"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "{{ $video->title }}",
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
            "name": "Apa isi dari video {{ $video->title }}?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ Str::limit(strip_tags($video->description ?? $video->title), 200) }}"
            }
        },
        {
            "@type": "Question",
            "name": "Kapan video ini dipublikasikan?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Video ini dipublikasikan pada {{ $video->created_at->format('d M Y') }}."
            }
        }
    ]
}
</script>
@endpush

@section('content')
    <div class="fixed inset-0 z-[10000] bg-[#0f0f0f] text-white font-sans mt-[64px] flex overflow-hidden">

        <div id="loading" class="absolute inset-0 bg-[#0f0f0f] z-[60] flex items-center justify-center">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-white border-opacity-50"></div>
        </div>

        <div class="flex-1 flex justify-center items-center relative min-w-0">

            <div class="relative flex items-end gap-4 h-[calc(100vh-96px)] w-full max-w-[1000px] justify-center">

                <div class="relative h-full aspect-[9/16] bg-black rounded-xl overflow-hidden shadow-2xl shrink-0 group">

                    <iframe id="youtubeVideo" class="w-full h-full object-cover"
                        src="{{ 'https://www.youtube.com/embed/' . Str::after($video->link_yt, 'v=') . '?autoplay=1&mute=0&rel=0&showinfo=0&controls=1&loop=1' }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                    </iframe>

                    <div
                        class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/80 via-black/40 to-transparent pointer-events-none">
                    </div>

                    {{-- TOMBOL INFO FLOATING (MOBILE ONLY) --}}
                    <button id="btnShowDetail"
                        class="absolute top-4 right-4 z-50 w-10 h-10 bg-black/40 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-black/60 transition-all xl:hidden border border-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>

                    <div
                        class="absolute bottom-0 left-0 right-0 p-4 pb-6 flex flex-col items-start gap-3 pointer-events-auto">

                        <div class="flex items-center gap-2">
                            <a href="#" class="flex items-center gap-2 group/author">
                                <div class="w-7 h-7 rounded-full overflow-hidden border border-white/10 bg-gray-700">
                                    <img src="{{ $video->createdBy->image ? getFile($video->createdBy->image) : asset('dist/images/users/avatar-1.jpg') }}"
                                        alt="{{ $video->createdBy->name }}" class="w-full h-full object-cover">
                                </div>
                                <span class="font-bold text-xs text-white group-hover/author:underline decoration-white/50">
                                    {{ $video->createdBy->name }}
                                </span>
                            </a>
                        </div>

                        <div class="max-w-[90%]">
                            <h1 class="text-sm  text-white line-clamp-2 leading-snug">
                                {{ $video->title }}
                            </h1>
                        </div>
                    </div>
                </div>


                <div
                    class="flex flex-col gap-4 ml-2 sm:self-center fixed bottom-6 right-6 sm:static z-50 ">
                    @php
                        $currentIndex = $videos->search(function ($item) use ($video) {
                            return $item->id == $video->id;
                        });
                        $prevVideo = $currentIndex > 0 ? $videos[$currentIndex - 1] : null;
                        $nextVideo = $currentIndex < $videos->count() - 1 ? $videos[$currentIndex + 1] : null;
                    @endphp

                    <a href="{{ $prevVideo ? route('video_detail', $prevVideo->slug) : '#' }}"
                        class="w-10 h-10 rounded-full bg-[#272727] hover:bg-[#3d3d3d] flex items-center justify-center transition-all {{ !$prevVideo ? 'opacity-30 cursor-not-allowed pointer-events-none' : '' }}">
                        <svg class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                            <path d="M18.41 16.59L13.82 12l4.59-4.59L17 6l-6 6 6 6zM6 6h2v12H6z"
                                transform="rotate(90 12 12)"></path>
                        </svg>
                    </a>

                    <a href="{{ $nextVideo ? route('video_detail', $nextVideo->slug) : '#' }}"
                        class="w-10 h-10 rounded-full bg-[#272727] hover:bg-[#3d3d3d] flex items-center justify-center transition-all {{ !$nextVideo ? 'opacity-30 cursor-not-allowed pointer-events-none' : '' }}">
                        <svg class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                            <path d="M5.59 7.41L10.18 12l-4.59 4.59L7 18l6-6-6-6zM16 6h2v12h-2z"
                                transform="rotate(90 12 12)"></path>
                        </svg>
                    </a>
                </div>


            </div>
        </div>

        <div class="hidden xl:flex w-[400px] border-l border-[#272727] flex-col bg-[#0f0f0f] relative z-20">
            <div class="flex items-center justify-between px-4 py-4 border-b border-[#272727]">
                <h2 class="text-base font-bold text-white">Deskripsi</h2>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar-dark p-4 space-y-6">

                <div class="space-y-3 pb-4 border-b border-[#272727]">
                    <h1 class="text-lg font-bold leading-snug">{{ $video->title }}</h1>
                    <div class="flex items-center gap-2 text-sm text-[#aaa]">
                        <span>{{ \Carbon\Carbon::parse($video->created_at)->locale('id')->diffForHumans() }}</span>
                    </div>
                    <div class="text-sm text-white whitespace-pre-line leading-relaxed">
                        {!! $content !!}
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-white mb-3">Video Lainnya</h3>
                    <div class="space-y-2">
                        @foreach ($videos as $item)
                            <a href="{{ route('video_detail', $item->slug) }}"
                                class="flex gap-2 group p-2 rounded-xl hover:bg-[#272727] transition-all {{ $item->id == $video->id ? 'bg-[#272727]' : '' }}">
                                <div class="w-32 aspect-video rounded-lg overflow-hidden flex-shrink-0 relative">
                                    <img src="{{ getFile($item->image) }}" alt="{{ $item->title }}"
                                        class="w-full h-full object-cover">
                                    <span
                                        class="absolute bottom-1 right-1 bg-black/80 text-white text-[10px] px-1 rounded font-medium">SHORTS</span>
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <h4 class="text-sm font-semibold text-white line-clamp-2 leading-tight mb-1">
                                        {{ $item->title }}</h4>
                                    <p class="text-xs text-[#aaa]">{{ $item->createdBy->name }}</p>
                                    <p class="text-xs text-[#aaa]">
                                        {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="mobileSheetOverlay" class="fixed inset-0 z-[20000] hidden xl:hidden">
        <div id="sheetBackdrop" class="absolute inset-0 bg-black/60 transition-opacity opacity-0"></div>

        <div id="sheetContent"
            class="absolute bottom-0 left-0 right-0 bg-[#1e1e1e] rounded-t-2xl h-[70vh] flex flex-col transform translate-y-full transition-transform duration-300 ease-out shadow-2xl border-t border-[#333]">

            <div class="flex items-center justify-between px-4 py-3 border-b border-[#333]">
                <h2 class="text-base font-bold text-white">Detail Video</h2>
                <button id="btnCloseSheet" class="p-2 hover:bg-[#333] rounded-full transition-colors">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar-dark p-4 space-y-6">
                <div class="space-y-3 pb-4 border-b border-[#333]">
                    <h1 class="text-lg font-bold leading-snug text-white">{{ $video->title }}</h1>
                    <div class="flex items-center gap-2 text-sm text-[#aaa]">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full overflow-hidden border border-white/10 bg-gray-700">
                                <img src="{{ $video->createdBy->image ? getFile($video->createdBy->image) : asset('dist/images/users/avatar-1.jpg') }}"
                                    alt="{{ $video->createdBy->name }}" class="w-full h-full object-cover">
                            </div>
                            <span class="font-semibold text-white">{{ $video->createdBy->name }}</span>
                        </div>
                        <span>•</span>
                        <span>{{ \Carbon\Carbon::parse($video->created_at)->locale('id')->diffForHumans() }}</span>
                    </div>
                    <div class="text-sm text-gray-200 whitespace-pre-line leading-relaxed">
                        {!! $content !!}
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-white mb-3">Video Lainnya</h3>
                    <div class="space-y-2">
                        @foreach ($videos as $item)
                            <a href="{{ route('video_detail', $item->slug) }}"
                                class="flex gap-3 group p-2 rounded-xl hover:bg-[#333] transition-all {{ $item->id == $video->id ? 'bg-[#333]' : '' }}">
                                <div class="w-32 aspect-video rounded-lg overflow-hidden flex-shrink-0 relative">
                                    <img src="{{ getFile($item->image) }}" alt="{{ $item->title }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <h4 class="text-sm font-semibold text-white line-clamp-2 leading-tight mb-1">
                                        {{ $item->title }}</h4>
                                    <p class="text-xs text-[#aaa]">{{ $item->createdBy->name }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .custom-scrollbar-dark::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar-dark::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar-dark::-webkit-scrollbar-thumb {
            background: #717171;
            border-radius: 4px;
        }

        .custom-scrollbar-dark::-webkit-scrollbar-thumb:hover {
            background: #a0a0a0;
        }

        .animate-spin {
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loading = document.getElementById('loading');
            const iframe = document.getElementById('youtubeVideo');

            setTimeout(() => {
                loading.style.opacity = '0';
                setTimeout(() => loading.style.display = 'none', 300);
            }, 1000);

            iframe.onload = function() {
                loading.style.opacity = '0';
                setTimeout(() => loading.style.display = 'none', 300);
            };

            const btnShowDetail = document.getElementById('btnShowDetail');
            const sheetOverlay = document.getElementById('mobileSheetOverlay');
            const sheetBackdrop = document.getElementById('sheetBackdrop');
            const sheetContent = document.getElementById('sheetContent');
            const btnCloseSheet = document.getElementById('btnCloseSheet');

            function openSheet() {
                sheetOverlay.classList.remove('hidden');
                setTimeout(() => {
                    sheetBackdrop.classList.remove('opacity-0');
                    sheetContent.classList.remove('translate-y-full');
                }, 10);
            }

            function closeSheet() {
                sheetBackdrop.classList.add('opacity-0');
                sheetContent.classList.add('translate-y-full');

                setTimeout(() => {
                    sheetOverlay.classList.add('hidden');
                }, 300);
            }

            if (btnShowDetail) {
                btnShowDetail.addEventListener('click', openSheet);
            }
            if (btnCloseSheet) {
                btnCloseSheet.addEventListener('click', closeSheet);
            }
            if (sheetBackdrop) {
                sheetBackdrop.addEventListener('click', closeSheet);
            }
        });
    </script>
@endpush
