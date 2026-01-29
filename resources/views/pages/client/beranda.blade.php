@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $meta->web_name ?? 'Portal Berita' }} - Beranda",
    "description": "{{ $meta->meta_description ?? 'Portal berita terkini dan terpercaya' }}",
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
        "text": "Halaman utama portal berita dengan berita terkini, trending topics, dan informasi terpercaya dari berbagai kategori."
    },
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Beranda",
                "item": "{{ url('/') }}"
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
            "name": "Apa yang bisa ditemukan di {{ $meta->web_name ?? 'Portal Berita' }}?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Portal berita ini menyediakan berita terkini, artikel informatif, dan konten berkualitas dari berbagai kategori seperti politik, ekonomi, teknologi, olahraga, dan lifestyle."
            }
        },
        {
            "@type": "Question",
            "name": "Seberapa sering konten diperbarui?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Konten diperbarui secara berkala setiap hari untuk memastikan pembaca mendapatkan informasi terbaru dan terpercaya."
            }
        }
    ]
}
</script>
@endpush
@push('styles')
    <style>
        .carousel-slide {
            transition: opacity 0.7s ease-in-out;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .carousel-slide.active {
            display: block;
            opacity: 1;
            position: relative;
        }

        .carousel-slide.hidden {
            display: none;
            opacity: 0;
        }

        #controls-carousel {
            position: relative;
            overflow: hidden;
        }

        .slide-indicator {
            transition: all 0.3s ease;
        }

        .slide-indicator:hover {
            transform: scale(1.2);
        }
    </style>
@endpush

@section('content')

    <div class="hidden lg:grid lg:grid-cols-2 gap-4">
        @foreach ($slide as $item)
            <div class="relative rounded-2xl overflow-hidden shadow-lg bg-gray-900 text-white min-h-[500px] h-full group">
                <img src="{{ getFile($item->image) }}" alt="{{ $item->title }}"
                    class="absolute inset-0 w-full h-full object-cover opacity-50 transition-transform duration-300 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <div class="flex items-center text-sm mb-3">
                        <a href="{{ $item?->category?->slug }}"
                            class="text-blue-400 font-semibold mr-2 hover:underline">{{ $item->category->name }}</a>
                        <span
                            class="text-gray-400">{{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('l, d M Y') }}</span>
                    </div>
                    <a href="/{{ $item->category->slug }}/{{ $item->slug }}">
                        <h2 class="text-2xl lg:text-4xl font-bold mb-4 leading-tight hover:text-blue-400 transition-colors">
                            {{ $item->title }}</h2>
                    </a>
                    <div class="flex items-center">
                        <a href="/author/{{ $item->createdBy->slug }}"
                            class="w-10 h-10 rounded-full bg-gray-600 mr-3 overflow-hidden">
                            <img src="{{ $item->createdBy->image ? getFile($item->createdBy->image) : asset('dist/images/users/avatar-1.jpg') }}"
                                alt="Author" class="w-full h-full object-cover">
                        </a>
                        <span class="text-lg font-semibold">{{ $item->createdBy->name }}</span>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 content-between h-full">
            @forelse ($latestNews as $item)
                <div class="relative rounded-2xl overflow-hidden shadow-lg bg-gray-900 text-white h-64 group">
                    <img src="{{ getFile($item->image) }}" alt="{{ $item->title }}"
                        class="absolute inset-0 w-full h-full object-cover opacity-60 transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-4">
                        <div class="flex items-center text-xs mb-2">
                            <a href="{{ $item?->category?->slug }}"
                                class="text-blue-400 font-semibold mr-2 hover:underline">{{ $item->category->name }}</a>
                            <span
                                class="text-gray-400">{{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('l, d M Y') }}</span>
                        </div>
                        <a href="/{{ $item->category->slug }}/{{ $item->slug }}">
                            <h3 class="text-lg font-bold leading-snug hover:text-blue-400 transition-colors line-clamp-2">
                                {{ $item->title }}</h3>
                        </a>
                    </div>
                </div>
            @empty
                @for ($i = 0; $i < 4; $i++)
                    @include('widget.client.load-data-1')
                @endfor
            @endforelse
        </div>
    </div>

    <div class="grid grid-cols-12 gap-2 lg:hidden">
        <div class="col-span-12 md:col-span-8">
            <div class="space-y-2">
                <div id="controls-carousel" class="relative w-full" data-carousel="static">
                    <!-- Slides -->
                    @foreach ($slide as $item)
                        <section class="carousel-slide duration-700 ease-in-out {{ $loop->first ? 'active' : 'hidden' }}" data-carousel-news>
                            <div class="relative h-64 overflow-hidden rounded-lg md:h-96">
                                <img src="{{ getFile($item->image) }}" class="block w-full h-full object-cover"
                                    alt="slide-image">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <div class="flex flex-col items-start justify-between">
                                        <div class="flex items-center gap-x-4 text-xs mb-2">
                                            <time
                                                datetime="{{ \Carbon\Carbon::parse($item->published_at)->toDateTimeString() }}"
                                                class="text-gray-300">
                                                {{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('l, d M Y') }}
                                            </time>
                                            <a href="{{ $item->category->slug }}"
                                                class="relative z-10 inline-flex items-center px-2 py-0.5 text-xs font-medium text-gray-700 bg-white/90 backdrop-blur rounded-full">
                                                <span class="mr-1 text-blue-600">#</span>
                                                {{ $item->category->name }}
                                            </a>
                                        </div>
                                        <h3 class="text-lg font-bold text-white leading-tight line-clamp-2">
                                            <a href="/{{ $item->category->slug }}/{{ $item->slug }}">
                                                {{ $item->title }}
                                            </a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endforeach

                    <!-- Navigation buttons -->
                    @if($slide->count() > 1)
                        <!-- Slide indicators only -->
                        <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3">
                            @foreach ($slide as $item)
                                <button type="button" class="w-3 h-3 rounded-full {{ $loop->first ? 'bg-white' : 'bg-white/50' }} slide-indicator" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}" data-carousel-slide-to="{{ $loop->index }}"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-span-12 md:col-span-4 mt-2">
            <div class="space-y-2 overflow-y-auto custom-scrollbar-y max-h-[600px]">
                @forelse ($latestNews as $item)
                    <div class="mx-auto w-full bg-white border border-gray-100 rounded-lg p-2 shadow-sm">
                        <div class="flex space-x-3">
                            <div class="w-24 h-24 flex-shrink-0 rounded-md overflow-hidden bg-gray-100">
                                <img src="{{ getFile($item->image) }}" alt="{{ $item->title }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 flex flex-col justify-between py-1">
                                <a class="text-sm font-semibold text-gray-900 hover:text-blue-600 transition-colors line-clamp-2 leading-snug"
                                    href="/{{ $item->category->slug }}/{{ $item->slug }}">
                                    {{ $item->title }}
                                </a>
                                <div class="flex items-center justify-between text-[10px] text-gray-500 mt-2">
                                    <time datetime="{{ \Carbon\Carbon::parse($item->published_at)->toDateTimeString() }}">
                                        {{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('d M Y') }}
                                    </time>
                                    <a href="{{ $item->category->slug }}"
                                        class="text-blue-500 font-medium hover:underline">
                                        {{ $item->category->name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    @for ($i = 0; $i < 5; $i++)
                        @include('widget.client.load-data-1')
                    @endfor
                @endforelse
            </div>
        </div>
    </div>


    <div class="grid grid-cols-12 gap-2 mt-4">

        <!-- Terpopuler -->
        @include('widget.client.most-popular', ['data' => $mostPopular])

        <!-- Banner -->
        @include('widget.client.banner', ['data' => $banner_1])

        <!-- Rekomendasi untuk Anda -->
        <div class="col-span-12 lg:p-3">
            @include('widget.client.header-title', ['title' => 'Rekomendasi untuk Anda', 'link' => ''])
            <div class="space-y-2">
                <div class="grid grid-cols-12 lg:gap-2">
                    @foreach ($recommended as $item)
                        <div class="col-span-12 md:col-span-6">
                            <div class="mx-auto w-full rounded-lg shadow-sm lg:p-4 p-2 mb-2">
                                <div class="flex space-x-4">
                                    <div class="size-30 rounded-lg overflow-hidden">
                                        <img src="{{ getFile($item->image) }}" alt="{{ $item->title }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 space-y-6">
                                        <span class="text-blue-600 font-semibold lg:hidden">
                                            <a href="{{ $item->category->slug }}"
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
                                                    <a href="{{ $item->category->slug }}"
                                                        class="relative z-10 inline-flex items-center p-1 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 transition-colors duration-200 group">
                                                        <span
                                                            class="flex items-center justify-center w-3 h-3 mr-2 text-white bg-blue-600 rounded-full">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="10"
                                                                height="10" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
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
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Video reels -->
        @include('widget.client.video', ['data' => $videos])

        <!-- Photos Column -->
        @include('widget.client.photos', ['data' => $photos])

        @if ($information->count() > 0)
            <div class="col-span-12 md:col-span-4 p-4 bg-slate-100 rounded">
                @include('widget.client.header-title', ['title' => 'Informasi Penting Anda', 'link' => 'info'])
                <div class="space-y-2 overflow-y-auto custom-scrollbar-y max-h-[800px]">
                    @foreach ($information as $item)
                        <div class="mx-auto w-full border-b border-dashed border-gray-300 p-2 mb-2">
                            <div class="flex space-x-4">
                                <div class="rounded overflow-hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-info text-blue-400">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 16v-4" />
                                        <path d="M12 8h.01" />
                                    </svg>
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div class="rounded">
                                        <a class="text-gray-700 font-semibold hover:text-gray-600 transition-colors duration-200"
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
                    @endforeach
                </div>
            </div>
        @else
            <div class="col-span-12 md:col-span-4 p-4 bg-slate-100 rounded">
                <div
                    class="w-full h-full min-h-[300px] flex flex-col items-center justify-center bg-gray-50 border border-gray-200 rounded overflow-hidden">

                    @php
                        $ad = $ads->first();
                    @endphp

                    @if($ad)
                        <a href="{{ $ad->redirect_url ?? '#' }}" target="_blank" class="block w-full h-full">
                            @if(in_array($ad->type, ['image', 'gif']))
                                <img src="{{ asset($ad->file_path) }}"
                                    class="w-full h-full object-cover rounded cursor-pointer" 
                                    alt="{{ $ad->title }}">
                            
                            @elseif($ad->type == 'video')
                                <video class="w-full h-full object-cover rounded" autoplay muted loop playsinline> 
                                    <source src="{{ asset($ad->file_path) }}" type="video/mp4">
                                </video>

                            @elseif($ad->type == 'youtube')
                                <div id="yt-player" data-url="{{ $ad->youtube_url }}" class="w-full h-full"></div>
                            @endif
                        </a>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const container = document.getElementById("yt-player");
                                if (!container) return;

                                const url = container.dataset.url;

                                function extractYoutubeId(link) {
                                    const regex = /(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]+)/;
                                    const match = link.match(regex);
                                    return match ? match[1] : link;
                                }

                                const videoId = extractYoutubeId(url);

                                container.innerHTML = `
                                    <iframe class="w-full h-full rounded"
                                        src="https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1&loop=1&playlist=${videoId}&controls=0&showinfo=0&modestbranding=1"
                                        frameborder="0"
                                        allow="autoplay; encrypted-media; fullscreen"
                                        allowfullscreen>
                                    </iframe>
                                `;
                            });
                        </script>
                    @endif
                </div>
            </div>
        @endif

        <!-- Banner -->
        @include('widget.client.banner', ['data' => $banner_2])

        <div class="col-span-12 rounded p-4 bg-slate-100">
            @include('widget.client.tags', ['data' => $tags])
        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            let currentSlide = 0;
            const slides = $('.carousel-slide');
            const indicators = $('.slide-indicator');
            const totalSlides = slides.length;
            let autoSlideInterval;

            // Function to show specific slide
            function showSlide(index) {
                slides.removeClass('active').addClass('hidden').css('opacity', '0');
                slides.eq(index).removeClass('hidden').addClass('active').css('opacity', '1');
                
                // Update indicators
                indicators.removeClass('bg-white').addClass('bg-white/50');
                indicators.eq(index).removeClass('bg-white/50').addClass('bg-white');
                
                currentSlide = index;
            }

            // Function to go to next slide
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }

            // Auto slide functionality
            function startAutoSlide() {
                if (totalSlides > 1) {
                    autoSlideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
                }
            }

            function stopAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                }
            }

            // Event listeners for indicators (manual navigation)
            $('[data-carousel-slide-to]').on('click', function() {
                stopAutoSlide();
                const slideIndex = parseInt($(this).data('carousel-slide-to'));
                showSlide(slideIndex);
                startAutoSlide(); // Restart auto slide after manual navigation
            });

            // Pause auto slide on hover
            $('#controls-carousel').hover(
                function() {
                    stopAutoSlide();
                },
                function() {
                    startAutoSlide();
                }
            );

            // Initialize
            if (totalSlides > 0) {
                showSlide(0);
                startAutoSlide();
            }

            // Responsive icon adjustment
            function adjustIconSize() {
                if ($(window).width() < 768) {
                    $('.w-10.h-10').removeClass('w-10 h-10').addClass('w-8 h-8');
                    $('.w-4.h-4').removeClass('w-4 h-4').addClass('w-3 h-3');
                } else {
                    $('.w-8.h-8').removeClass('w-8 h-8').addClass('w-10 h-10');
                    $('.w-3.h-3').removeClass('w-3 h-3').addClass('w-4 h-4');
                }
            }

            adjustIconSize();
            $(window).on('resize', adjustIconSize);
        });
    </script>
@endpush
