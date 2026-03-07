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
                "text": "Halal Beauty menyediakan berita terkini, artikel informatif, dan konten berkualitas seputar gaya hidup halal, kecantikan, dan kesehatan."
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

        /* Category Slider Styles */
        .category-slider-container {
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
            width: 100%;
        }
        .category-slider {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            scroll-behavior: smooth;
            -ms-overflow-style: none;
            scrollbar-width: none;
            flex-wrap: nowrap;
            white-space: nowrap;
        }
        .category-slider::-webkit-scrollbar {
            display: none;
        }
        .category-item {
            flex: 0 0 auto;
            cursor: pointer;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
        }
        .category-item.active {
            border-bottom-color: #E11D48;
            color: #E11D48;
        }
    </style>
@endpush

@section('full-width')
@section('content')

{{-- ─────────────────────────────────────────────────────────
     SECTION 1 — ARTIKEL TERBARU
     BG: White — 2 kolom featured + date-stamped
     ───────────────────────────────────────────────────────── --}}
@php
    $artFeatured = $latestNews->first();
    $artSide     = $latestNews->slice(1, 2);
@endphp

@if($artFeatured)
<section class="" style="background-color:#FAFAF9; padding:80px 0 80px 0 !important">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Kolom Kiri: Featured --}}
            <div class="flex flex-col gap-4 lg:pr-10">
                <span class="text-xs font-mono font-bold uppercase tracking-widest text-rose-500">
                    {{ $artFeatured->category->name ?? '—' }}
                </span>
                <a href="/{{ $artFeatured->category->slug }}/{{ $artFeatured->slug }}" class="group">
                    <h1 class="text-3xl lg:text-4xl font-bold leading-tight text-gray-900 group-hover:text-rose-600 transition-colors"
                        style="font-family:'Libre Baskerville',serif;">
                        {{ $artFeatured->title }}
                    </h1>
                </a>
                <p class="text-base italic text-gray-600 leading-relaxed" style="font-family:'Libre Baskerville',serif;">
                    {{ implode(' ', array_slice(explode(' ', strip_tags($artFeatured->content ?? '')), 0, 25)) }}...
                </p>
                <a href="/{{ $artFeatured->category->slug }}/{{ $artFeatured->slug }}"
                   class="self-start text-xs font-mono font-bold uppercase tracking-widest text-gray-700 underline underline-offset-4 decoration-rose-400 hover:text-rose-600 transition-colors">
                    Baca Selengkapnya →
                </a>
            </div>

            {{-- Kolom Kanan: 2 artikel --}}
            <div class="flex flex-col divide-y divide-gray-100">
                @foreach($artSide as $item)
                <div class="flex gap-5 py-6 first:pt-0 last:pb-0">
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
                @endforeach
            </div>

        </div>
    </div>
</section>
@endif


{{-- ─────────────────────────────────────────────────────────
     SECTION 2 — TERPOPULER
     BG: Warm Cream #FAFAF9 — Numbered rank list
     ───────────────────────────────────────────────────────── --}}
<section class="" style="padding:80px 0 80px 0 !important">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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


{{-- ─────────────────────────────────────────────────────────
     SECTION 3 — TELAAH MENDALAM
     BG: Off-white #FAFAF9 — Editorial: centered header + rows
     Layout: Left (date + author) | Right (h2 title + excerpt + link)
     ───────────────────────────────────────────────────────── --}}
<section class="" style="background-color:#FAFAF9;  padding:80px 0 80px 0 !important">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Centered Section Header --}}
        <div class="text-center mb-14">
            <h1 class="text-3xl lg:text-4xl font-bold"
                style="font-family:'Libre Baskerville',serif; margin-bottom:40px !important">
                Kategori Pilihan
            </h1>
        </div>

        {{-- Article Rows --}}
        <div class="flex flex-col">
            @foreach($recommended->take(5) as $item)
            <div class="grid grid-cols lg:grid-cols-2 gap-10" >

                {{-- Left: Date + Author (Aligned Right) --}}
                <div class="flex flex-col gap-4 text-left">
                    <time class="text-xs font-mono uppercase tracking-widest text-gray-500">
                        {{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('d M Y') }}
                    </time>
                    <div>
                        <p class="text-xs font-mono uppercase tracking-widest text-rose-400 mb-1">Penulis</p>
                        <p class="text-sm italic text-gray-700" style="font-family:'Libre Baskerville',serif;">
                            {{ $item->createdBy->name ?? '—' }}
                        </p>
                    </div>
                </div>

                {{-- Right: Title + Excerpt + Link (Aligned Left) --}}
                <div class="flex flex-col gap-4 lg:pr-10">
                    <a href="/{{ $item->category->slug }}/{{ $item->slug }}" class="group">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 group-hover:text-rose-600 transition-colors leading-snug"
                            style="font-family:'Libre Baskerville',serif;">
                            {{ $item->title }}
                        </h2>
                    </a>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-2xl">
                        {{ implode(' ', array_slice(explode(' ', strip_tags($item->content ?? '')), 0, 22)) }}...
                    </p>
                    <a href="/{{ $item->category->slug }}/{{ $item->slug }}"
                       class="self-start text-xs font-mono uppercase tracking-[0.15em] text-gray-700 hover:text-rose-600 transition-colors flex items-center gap-2 mt-1">
                        Baca Selengkapnya <span>→</span>
                    </a>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ─────────────────────────────────────────────────────────
     SECTION 4 — BACAAN HARI INI
     BG: Warm Cream #F5F1EB — Magazine: 1 big + 3 stacked
     ───────────────────────────────────────────────────────── --}}
@php
    $magMain    = $latestNews->slice(3, 1)->first();
    $magSidebar = $latestNews->slice(4, 3);
@endphp

@if($magMain)
<section style="background-color:#FFFFFF;  padding:80px 0 80px 0 !important">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Title & Categories Split 40:60 --}}
        <div class="grid grid-cols-1 lg:grid-cols-10 gap-8 mb-10 items-center">
            <div class="lg:col-span-4">
                <h1 class="text-3xl lg:text-4xl font-bold text-stone-800 tracking-tight"
                    style="font-family:'Libre Baskerville',serif;">Bacaan Hari Ini</h1>
            </div>
            <div class="lg:col-span-6 flex items-center gap-4">
                <button id="prevCat" class="flex-shrink-0 w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center hover:bg-stone-200 transition-colors">
                    <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div class="category-slider-container flex-1">
                    <div id="categorySlider" class="category-slider">
                        <div class="category-item active text-xs font-mono font-bold uppercase tracking-widest" data-category-id="all">
                            Semua
                        </div>
                        @foreach($categories as $cat)
                        <div class="category-item text-xs font-mono font-bold uppercase tracking-widest text-stone-400 hover:text-rose-500" data-category-id="{{ $cat->id }}">
                            {{ $cat->name }}
                        </div>
                        @endforeach
                    </div>
                </div>
                <button id="nextCat" class="flex-shrink-0 w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center hover:bg-stone-200 transition-colors">
                    <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        <div id="section4Container" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Articles will be injected here via AJAX --}}
            @php
                $initialPosts = $latestNews->slice(3, 3);
            @endphp
            @foreach($initialPosts as $item)
            <div class="flex flex-col gap-4">
                <span class="text-xs font-mono font-bold uppercase tracking-widest text-rose-500">
                    {{ $item->category->name ?? '—' }}
                </span>
                <a href="/{{ $item->category->slug ?? '' }}/{{ $item->slug ?? '#' }}" class="group">
                    <h2 class="text-xl lg:text-2xl font-bold leading-tight text-stone-800 group-hover:text-rose-600 transition-colors"
                        style="font-family:'Libre Baskerville',serif;">
                        {{ $item->title }}
                    </h2>
                </a>
                <p class="text-sm text-stone-600 leading-relaxed" style="font-family:'Libre Baskerville',serif;">
                    {{ implode(' ', array_slice(explode(' ', strip_tags($item->content ?? '')), 0, 30)) }}...
                </p>
                <div class="flex items-center gap-3 text-xs text-stone-400 pt-2 border-t border-stone-100">
                    <span>{{ $item->createdBy->name ?? '—' }}</span>
                    <span>·</span>
                    <time>{{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('d M Y') }}</time>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ─────────────────────────────────────────────────────────
     SECTION 5 — JELAJAHI LEBIH LANJUT
     BG: Light Gray #F3F4F6 — Compact editorial table
     ───────────────────────────────────────────────────────── --}}
@php
    $editorial = $latestNews->slice(7, 8);
@endphp

<section class="py-20 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Title --}}
        <div class="flex items-center gap-4 mb-10">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 tracking-tight whitespace-nowrap"
                style="font-family:'Libre Baskerville',serif;">Jelajahi Lebih Lanjut</h2>
        </div>

        <div class="flex flex-col bg-white rounded-xl shadow-sm overflow-hidden divide-y divide-gray-100">
            @forelse($editorial as $item)
            <div class="grid grid-cols-12 gap-4 px-5 py-4 hover:bg-rose-50 transition-colors group">
                {{-- No. --}}
                <div class="col-span-1 text-xs text-gray-300 font-mono font-bold pt-1 text-right">
                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                </div>
                {{-- Category --}}
                <div class="col-span-2 hidden md:flex items-start">
                    <a href="/{{ $item->category->slug }}"
                       class="text-xs font-mono font-semibold uppercase tracking-widest text-rose-500 hover:underline">
                        {{ $item->category->name ?? '—' }}
                    </a>
                </div>
                {{-- Title + excerpt --}}
                <div class="col-span-11 md:col-span-7 flex flex-col gap-1">
                    <a href="/{{ $item->category->slug }}/{{ $item->slug }}" class="group">
                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-rose-600 transition-colors leading-snug"
                            style="font-family:'Libre Baskerville',serif;">
                            {{ $item->title }}
                        </h4>
                    </a>
                    <p class="text-xs text-gray-500 leading-relaxed hidden sm:block">
                        {{ implode(' ', array_slice(explode(' ', strip_tags($item->content ?? '')), 0, 15)) }}...
                    </p>
                </div>
                {{-- Date --}}
                <div class="col-span-2 hidden md:flex items-start justify-end">
                    <time class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('d M Y') }}
                    </time>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 italic py-6 px-5">Belum ada artikel tersedia.</p>
            @endforelse
        </div>

    </div>
</section>

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

            // Category Slider Navigation
            const slider = $('#categorySlider');
            $('#prevCat').on('click', function() {
                slider.animate({
                    scrollLeft: '-=200'
                }, 300);
            });
            $('#nextCat').on('click', function() {
                slider.animate({
                    scrollLeft: '+=200'
                }, 300);
            });

            // AJAX Filtering for Section 4
            $('.category-item').on('click', function() {
                const categoryId = $(this).data('category-id');
                
                // UI feedback
                $('.category-item').removeClass('active text-rose-600').addClass('text-stone-400');
                $(this).addClass('active text-rose-600').removeClass('text-stone-400');

                // Fetch posts
                $.ajax({
                    url: '{{ route("api.posts_by_category") }}',
                    method: 'GET',
                    data: { category_id: categoryId },
                    success: function(posts) {
                        let html = '';
                        if (posts.length > 0) {
                            posts.forEach(post => {
                                html += `
                                    <div class="flex flex-col gap-4">
                                        <span class="text-xs font-mono font-bold uppercase tracking-widest text-rose-500">
                                            ${post.category_name}
                                        </span>
                                        <a href="${post.url}" class="group">
                                            <h2 class="text-xl lg:text-2xl font-bold leading-tight text-stone-800 group-hover:text-rose-600 transition-colors"
                                                style="font-family:'Libre Baskerville',serif;">
                                                ${post.title}
                                            </h2>
                                        </a>
                                        <p class="text-sm text-stone-600 leading-relaxed" style="font-family:'Libre Baskerville',serif;">
                                            ${post.excerpt}
                                        </p>
                                        <div class="flex items-center gap-3 text-xs text-stone-400 pt-2 border-t border-stone-100">
                                            <span>${post.author_name}</span>
                                            <span>·</span>
                                            <time>${post.formatted_date}</time>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html = '<div class="lg:col-span-3 text-center py-10 text-stone-400 italic">Belum ada artikel dalam kategori ini.</div>';
                        }
                        $('#section4Container').html(html);
                    },
                    error: function() {
                        alert('Gagal mengambil data artikel.');
                    }
                });
            });
        });
    </script>
@endpush
