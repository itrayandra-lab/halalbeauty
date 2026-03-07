@extends('layouts.client.app')

@push('structured-data')
<script type="application/ld+json">
@php
    use App\Helpers\StructuredDataHelper;
    $articleSchema = StructuredDataHelper::generateArticleSchema($post, $post->createdBy);
    echo json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
</script>

@php
    $faqs = StructuredDataHelper::extractFAQFromContent($content);
    if (!empty($faqs)) {
        $faqSchema = StructuredDataHelper::generateFAQSchema($faqs);
        echo '<script type="application/ld+json">' . json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
@endphp

@php
    $steps = StructuredDataHelper::extractHowToFromContent($content);
    if (!empty($steps)) {
        $howToSchema = StructuredDataHelper::generateHowToSchema($post->title, strip_tags(substr($content, 0, 160)), $steps);
        echo '<script type="application/ld+json">' . json_encode($howToSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
@endphp

<script type="application/ld+json">
{
    "@context": "https://schema.org",
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
            "name": "{{ $post->category->name }}",
            "item": "{{ url('/' . $post->category->slug) }}"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $post->title }}",
            "item": "{{ request()->url() }}"
        }
    ]
}
</script>
@endpush

@section('content')
    <div class="relative isolate -mt-8 overflow-hidden bg-white px-2 py-10 lg:py-20 lg:overflow-visible lg:px-0">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <svg class="absolute top-0 left-[max(50%,25rem)] h-[64rem] w-[128rem] -translate-x-1/2 stroke-gray-200 [mask-image:radial-gradient(64rem_64rem_at_top,white,transparent)]" aria-hidden="true">
                <defs>
                    <pattern id="e813992c-7d03-4cc4-a2bd-151760b470a0" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                        <path d="M100 200V.5M.5 .5H200" fill="none" />
                    </pattern>
                </defs>
                <svg x="50%" y="-1" class="overflow-visible fill-gray-50">
                    <path d="M-100.5 0h201v201h-201Z M699.5 0h201v201h-201Z M499.5 400h201v201h-201Z M-300.5 600h201v201h-201Z" stroke-width="0" />
                </svg>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#e813992c-7d03-4cc4-a2bd-151760b470a0)" />
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="lg:pr-4">
                        <div class="lg:max-w-full">
                            <a href="/{{ $post->category->slug }}" class="text-base/7 font-semibold text-blue-600">
                                {{ $post->category->name }}
                            </a>
                            <h1 class="mt-2 text-xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-3xl">
                                {{ $post->title }}
                            </h1>
                            <div class="space-y-10 my-3">
                                <article class="space-y-8">
                                    <div class="space-y-6">
                                        <div class="flex flex-col items-start justify-between w-full md:flex-row md:items-center text-gray-600">
                                            <div class="flex items-center space-x-2">
                                                <img src="{{ $post->createdBy->image ? getFile($post->createdBy->image) : asset('dist/images/users/avatar-1.jpg') }}"
                                                    alt="" class="w-8 h-8 rounded-full">
                                                <a href="/author/{{ $post->createdBy->slug }}"
                                                    class="text-sm font-semibold ">{{ $post->createdBy->name }}</a>
                                            </div>
                                            <p class="flex-shrink-0 mt-3 text-sm md:mt-0">
                                                {{ \Carbon\Carbon::parse($post->published_at)->locale('id')->translatedFormat('l, d M Y') }} • {{ $post->counter }} views
                                            </p>
                                        </div>
                                    </div>
                                </article>
                                <div>
                                    <div class="flex flex-wrap py-1 gap-2 border-t border-dashed border-gray-400 dark:border-gray-600"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:pr-4 post-content">
                        <img class="w-full rounded-sm mb-4 bg-gray-900 ring-1 shadow-lg ring-gray-400/10 sm:w-[57rem] post-image" src="{{ getFile($post->image) }}" alt="">
                        <div class="text-base/7 text-gray-700 ">
                            {!! $content !!}
                        </div>
                    </div>

                    <!-- Tags Section -->
                     @if ($post->tags)
                    <div class="mt-8 pb-8 border-b border-dashed border-gray-400 dark:border-gray-600">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Tags:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($post->tags as $tag)
                                @php $tags = App\Models\PostTags::tagById($tag) @endphp
                                <a rel="noopener noreferrer" href="/tag/{{ $tags->slug }}"
                                    class="px-3 py-1 rounded-sm hover:underline bg-blue-400 dark:bg-blue-600 text-gray-900 dark:text-gray-50">
                                    #{{ $tags->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                     @endif
                    
                     <!-- Share Section -->
                    <div class="mt-8">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Bagikan Artikel:</h3>
                        <div class="flex flex-wrap gap-3">
                            <div class="bg-white w-full h-auto pb-5 flex  gap-4 flex-wrap">
                                {{-- facebook --}}
                                <a target="blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-white shadow-md shadow-gray-200 group transition-all duration-300">
                                    <svg class="transition-all duration-300 group-hover:scale-110"
                                        xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 72 72"
                                        fill="none">
                                        <path
                                            d="M46.4927 38.6403L47.7973 30.3588H39.7611V24.9759C39.7611 22.7114 40.883 20.4987 44.4706 20.4987H48.1756V13.4465C46.018 13.1028 43.8378 12.9168 41.6527 12.8901C35.0385 12.8901 30.7204 16.8626 30.7204 24.0442V30.3588H23.3887V38.6403H30.7204V58.671H39.7611V38.6403H46.4927Z"
                                            fill="#337FFF" />
                                    </svg>
                                </a>


                                {{-- x atau twiter --}}
                                <a target="blank" href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-white shadow-md shadow-gray-200 group transition-all duration-300">
                                    <svg class="transition-all duration-300 group-hover:scale-110"
                                        xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 72 72"
                                        fill="none">
                                        <path
                                            d="M40.7568 32.1716L59.3704 11H54.9596L38.7974 29.383L25.8887 11H11L30.5205 38.7983L11 61H15.4111L32.4788 41.5869L46.1113 61H61L40.7557 32.1716H40.7568ZM34.7152 39.0433L32.7374 36.2752L17.0005 14.2492H23.7756L36.4755 32.0249L38.4533 34.7929L54.9617 57.8986H48.1865L34.7152 39.0443V39.0433Z"
                                            fill="black" />
                                    </svg>
                                </a>

                                {{-- tiktok --}}
                                <a target="blank" href="https://www.tiktok.com/share/video?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-white shadow-md shadow-gray-200 group transition-all duration-300">
                                    <svg class="transition-all duration-300 group-hover:scale-110" width="28"
                                        height="28" viewBox="0 0 72 72" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M45.6721 29.4285C48.7387 31.6085 52.4112 32.7733 56.1737 32.7592V25.3024C55.434 25.3045 54.6963 25.2253 53.9739 25.0663V31.0068C50.203 31.0135 46.5252 29.8354 43.4599 27.6389V42.9749C43.4507 45.4914 42.7606 47.9585 41.4628 50.1146C40.165 52.2706 38.3079 54.0353 36.0885 55.2215C33.8691 56.4076 31.37 56.9711 28.8563 56.852C26.3426 56.733 23.9079 55.9359 21.8105 54.5453C23.7506 56.5082 26.2295 57.8513 28.9333 58.4044C31.6372 58.9576 34.4444 58.6959 36.9994 57.6526C39.5545 56.6093 41.7425 54.8312 43.2864 52.5436C44.8302 50.256 45.6605 47.5616 45.6721 44.8018V29.4285ZM48.3938 21.8226C46.8343 20.1323 45.8775 17.9739 45.6721 15.6832V14.7139H43.5842C43.8423 16.1699 44.4039 17.5553 45.2326 18.78C46.0612 20.0048 47.1383 21.0414 48.3938 21.8226ZM26.645 48.642C25.9213 47.6957 25.4779 46.5653 25.365 45.3793C25.2522 44.1934 25.4746 42.9996 26.0068 41.9338C26.5391 40.8681 27.3598 39.9731 28.3757 39.3508C29.3915 38.7285 30.5616 38.4039 31.7529 38.4139C32.4106 38.4137 33.0644 38.5143 33.6916 38.7121V31.0068C32.9584 30.9097 32.2189 30.8682 31.4794 30.8826V36.8728C29.9522 36.39 28.2992 36.4998 26.8492 37.1803C25.3992 37.8608 24.2585 39.0621 23.6539 40.5454C23.0494 42.0286 23.0252 43.6851 23.5864 45.1853C24.1475 46.6855 25.2527 47.9196 26.6823 48.642H26.645Z"
                                            fill="#EE1D52" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M43.4589 27.5892C46.5241 29.7857 50.2019 30.9638 53.9729 30.9571V25.0166C51.8243 24.5623 49.8726 23.4452 48.3927 21.8226C47.1372 21.0414 46.0601 20.0048 45.2315 18.78C44.4029 17.5553 43.8412 16.1699 43.5831 14.7139H38.09V44.8018C38.0849 46.1336 37.6629 47.4304 36.8831 48.51C36.1034 49.5897 35.0051 50.3981 33.7425 50.8217C32.4798 51.2453 31.1162 51.2629 29.8431 50.872C28.57 50.4811 27.4512 49.7012 26.6439 48.642C25.3645 47.9965 24.3399 46.9387 23.7354 45.6394C23.1309 44.3401 22.9818 42.875 23.3121 41.4805C23.6424 40.0861 24.4329 38.8435 25.556 37.9535C26.6791 37.0634 28.0693 36.5776 29.5023 36.5745C30.1599 36.5766 30.8134 36.6772 31.4411 36.8728V30.8826C28.7288 30.9477 26.0946 31.8033 23.8617 33.3444C21.6289 34.8855 19.8946 37.0451 18.8717 39.5579C17.8489 42.0708 17.5821 44.8276 18.1039 47.49C18.6258 50.1524 19.9137 52.6045 21.8095 54.5453C23.9073 55.9459 26.3458 56.7512 28.8651 56.8755C31.3845 56.9997 33.8904 56.4383 36.1158 55.2509C38.3413 54.0636 40.2031 52.2948 41.5027 50.133C42.8024 47.9712 43.4913 45.4973 43.4962 42.9749L43.4589 27.5892Z"
                                            fill="black" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M53.9736 25.0161V23.4129C52.0005 23.4213 50.0655 22.8696 48.3934 21.8221C49.8695 23.4493 51.8229 24.5674 53.9736 25.0161ZM43.5838 14.7134C43.5838 14.4275 43.4968 14.1292 43.4596 13.8434V12.874H35.8785V42.9744C35.872 44.6598 35.197 46.2738 34.0017 47.4621C32.8064 48.6504 31.1885 49.3159 29.503 49.3126C28.5106 49.3176 27.5311 49.0876 26.6446 48.6415C27.4519 49.7007 28.5707 50.4805 29.8438 50.8715C31.1169 51.2624 32.4805 51.2448 33.7432 50.8212C35.0058 50.3976 36.1041 49.5892 36.8838 48.5095C37.6636 47.4298 38.0856 46.1331 38.0907 44.8013V14.7134H43.5838ZM31.4418 30.8696V29.167C28.3222 28.7432 25.1511 29.3885 22.4453 30.9977C19.7394 32.6069 17.6584 35.0851 16.5413 38.0284C15.4242 40.9718 15.337 44.2067 16.2938 47.206C17.2506 50.2053 19.195 52.792 21.8102 54.5448C19.9287 52.5995 18.6545 50.1484 18.1433 47.4908C17.6321 44.8333 17.906 42.0844 18.9315 39.5799C19.957 37.0755 21.6897 34.924 23.918 33.3882C26.1463 31.8524 28.7736 30.9988 31.4791 30.9318L31.4418 30.8696Z"
                                            fill="#69C9D0" />
                                    </svg>

                                </a>

                                {{-- wa --}}
                                <a target="blank" href="https://wa.me/?text={{ urlencode($post->title . ' — ' . request()->fullUrl()) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-white shadow-md shadow-gray-200 group transition-all duration-300">
                                    <svg class="transition-all duration-300 group-hover:scale-110"
                                        xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                        viewBox="0 0 71 72" fill="none">
                                        <path
                                            d="M12.5762 56.8405L15.8608 44.6381C13.2118 39.8847 12.3702 34.3378 13.4904 29.0154C14.6106 23.693 17.6176 18.952 21.9594 15.6624C26.3012 12.3729 31.6867 10.7554 37.1276 11.1068C42.5685 11.4582 47.6999 13.755 51.5802 17.5756C55.4604 21.3962 57.8292 26.4844 58.2519 31.9065C58.6746 37.3286 57.1228 42.7208 53.8813 47.0938C50.6399 51.4668 45.9261 54.5271 40.605 55.7133C35.284 56.8994 29.7125 56.1318 24.9131 53.5513L12.5762 56.8405ZM25.508 48.985L26.2709 49.4365C29.7473 51.4918 33.8076 52.3423 37.8191 51.8555C41.8306 51.3687 45.5681 49.5719 48.4489 46.7452C51.3298 43.9185 53.1923 40.2206 53.7463 36.2279C54.3002 32.2351 53.5143 28.1717 51.5113 24.6709C49.5082 21.1701 46.4003 18.4285 42.6721 16.8734C38.9438 15.3184 34.8045 15.0372 30.8993 16.0736C26.994 17.11 23.5422 19.4059 21.0817 22.6035C18.6212 25.801 17.2903 29.7206 17.2963 33.7514C17.293 37.0937 18.2197 40.3712 19.9732 43.2192L20.4516 44.0061L18.6153 50.8167L25.508 48.985Z"
                                            fill="#00D95F" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M44.0259 36.8847C43.5787 36.5249 43.0549 36.2716 42.4947 36.1442C41.9344 36.0168 41.3524 36.0186 40.793 36.1495C39.9524 36.4977 39.4093 37.8134 38.8661 38.4713C38.7516 38.629 38.5833 38.7396 38.3928 38.7823C38.2024 38.8251 38.0028 38.797 37.8316 38.7034C34.7543 37.5012 32.1748 35.2965 30.5122 32.4475C30.3704 32.2697 30.3033 32.044 30.325 31.8178C30.3467 31.5916 30.4555 31.3827 30.6286 31.235C31.2344 30.6368 31.6791 29.8959 31.9218 29.0809C31.9756 28.1818 31.7691 27.2863 31.3269 26.5011C30.985 25.4002 30.3344 24.42 29.4518 23.6762C28.9966 23.472 28.4919 23.4036 27.9985 23.4791C27.5052 23.5546 27.0443 23.7709 26.6715 24.1019C26.0242 24.6589 25.5104 25.3537 25.168 26.135C24.8256 26.9163 24.6632 27.7643 24.6929 28.6165C24.6949 29.0951 24.7557 29.5716 24.8739 30.0354C25.1742 31.1497 25.636 32.2144 26.2447 33.1956C26.6839 33.9473 27.163 34.6749 27.6801 35.3755C29.3607 37.6767 31.4732 39.6305 33.9003 41.1284C35.1183 41.8897 36.42 42.5086 37.7799 42.973C39.1924 43.6117 40.752 43.8568 42.2931 43.6824C43.1711 43.5499 44.003 43.2041 44.7156 42.6755C45.4281 42.1469 45.9995 41.4518 46.3795 40.6512C46.6028 40.1675 46.6705 39.6269 46.5735 39.1033C46.3407 38.0327 44.9053 37.4007 44.0259 36.8847Z"
                                            fill="#00D95F" />
                                    </svg>
                                </a>

                                {{-- linkid --}}
                                <a target="blank" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-white shadow-md shadow-gray-200 group transition-all duration-300">
                                    <svg class="rounded-md transition-all duration-300 group-hover:scale-110"
                                        xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                        viewBox="0 0 72 72" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14.6975 11C12.6561 11 11 12.6057 11 14.5838V57.4474C11 59.4257 12.6563 61.03 14.6975 61.03H57.3325C59.3747 61.03 61.03 59.4255 61.03 57.4468V14.5838C61.03 12.6057 59.3747 11 57.3325 11H14.6975ZM26.2032 30.345V52.8686H18.7167V30.345H26.2032ZM26.6967 23.3793C26.6967 25.5407 25.0717 27.2703 22.4615 27.2703L22.4609 27.2701H22.4124C19.8998 27.2701 18.2754 25.5405 18.2754 23.3791C18.2754 21.1686 19.9489 19.4873 22.5111 19.4873C25.0717 19.4873 26.6478 21.1686 26.6967 23.3793ZM37.833 52.8686H30.3471L30.3469 52.8694C30.3469 52.8694 30.4452 32.4588 30.3475 30.3458H37.8336V33.5339C38.8288 31.9995 40.6098 29.8169 44.5808 29.8169C49.5062 29.8169 53.1991 33.0363 53.1991 39.9543V52.8686H45.7133V40.8204C45.7133 37.7922 44.6293 35.7269 41.921 35.7269C39.8524 35.7269 38.6206 37.1198 38.0796 38.4653C37.8819 38.9455 37.833 39.6195 37.833 40.2918V52.8686Z"
                                            fill="#006699" />
                                    </svg>
                                </a>



                                <button onclick="copyToClipboard()"
                                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-white shadow-md shadow-gray-200 group transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 image-container">
                    <div class="rounded p-4 bg-slate-100">
                        <div class="col-span-12 bg-gray-100 rounded lg:p-2 ">
                            @include('widget.client.header-title', ['title' => 'Rekomendasi Untuk Anda', 'link' => ''])
                            <div class="space-y-2">
                                <div class="grid grid-cols-12">
                                    @forelse ($recommended as $item)
                                        <div class="col-span-12">
                                            <div class="mx-auto w-full p-2 mb-2">
                                                <div class="flex space-x-4 items-center">
                                                    <div class="text-blue-900 font-bold text-2xl italic">
                                                        {{ $loop->iteration }}
                                                    </div>
                                                    <div class="flex-1">
                                                        <div>
                                                            <a class="text-gray-900 font-semibold  text-sm hover:text-gray-600 transition-colors duration-200"
                                                                href="/{{ $item->category->slug }}/{{ $item->slug }}">
                                                                {{ $item->title }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class=" border border-dashed border-gray-200  ">
                                        </div>
                                    @empty
                                        @for ($i = 0; $i < 5; $i++)
                                            <div class="col-span-12 md:col-span-6">
                                                @include('widget.client.load-data-1')
                                            </div>
                                        @endfor
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="rounded mt-3">
                        <div class="col-span-12 rounded">
                            <div class="space-y-2">
                                <div class="grid grid-cols-12">
                                    @foreach ($ads as $item)
                                        <div class="col-span-12">
                                            <div class="mx-auto w-full mb-2">
                                                <div class="flex flex-col items-center space-y-1">

                                                    @if($item->type == 'video')
                                                        <video class="rounded w-full h-auto cursor-pointer" 
                                                            autoplay muted loop playsinline
                                                            controls>
                                                            <source src="{{ asset($item->file_path) }}" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @elseif($item->type == 'youtube')
                                                        <div id="yt-player" data-url="{{ $item->youtube_url }}" class="w-full h-full"></div>
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
                                                    @else
                                                        <a href="{{ $item->redirect_url ?? '#' }}" target="_blank" class="block w-full">
                                                            <img src="{{ asset($item->file_path) }}" 
                                                                class="rounded w-full h-auto cursor-pointer" 
                                                                alt="{{ $item->title }}">
                                                        </a>
                                                    @endif

                                                    <p class="text-xs text-gray-400 text-center mt-1 italic">
                                                        Iklan / Ads Sponsored
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <script>
                                        
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 lg:p-3">
        <div style="margin-bottom:20px !important">
            <h2 class="text-2xl font-bold text-stone-800 tracking-tight"
                style="font-family:'Libre Baskerville',serif;">Relate Artikel</h2>
        </div>
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:gap-8">
                @foreach ($relate as $item)
                    <div class="border-b border-stone-100 last:border-0 lg:border-0">
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
                                    {{ implode(' ', array_slice(explode(' ', strip_tags($item->content ?? '')), 0, 15)) }}...
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Terpopuler -->
    <section class="" style="padding:80px 0 80px 0 !important; background-color: #FAFAF9;">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            {{-- Section Title --}}
            <div style="margin-bottom:40px !important">
                <h2 class="text-3xl lg:text-4xl font-bold text-stone-800 tracking-tight whitespace-nowrap"
                    style="font-family:'Libre Baskerville',serif;">Terpopuler</h2>
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
    @include('widget.client.banner', ['data' => $banner_1])
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

@push('scripts')
<script>
    function copyToClipboard() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Link berhasil disalin ke clipboard!');
        }).catch(() => {
            alert('Gagal menyalin link');
        });
    }
</script>
@endpush