<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $meta->meta_title ?? 'Portal Berita' }}</title>
    <!-- Meta Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="{{ $meta->meta_description }}" />
    <meta name="keywords" content="{{ $meta->meta_keywords }}" />
    <meta name="author" content="{{ $meta->web_name }}" />

    <!-- Open Graph Meta -->
    <meta property="og:title" content="{{ $meta->meta_title }}">
    <meta property="og:description" content="{{ $meta->meta_description }}">
    <meta property="og:image" content="{{ getFile($meta->og_image) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Google Search Console Verification -->
    <meta name="google-site-verification" content="bv3rh_-FXLuLzRCtFRFxAINk-dKEIcv6LVdN2XM0Qxs" />

    <!-- Favicon & Logo -->
    <link rel="shortcut icon" href="{{ getFile($meta->favicon) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ getFile($meta->logo) }}">
    <meta name="robots" content="index, follow" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="theme-color" content="#ffffff" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-TileImage" content="{{ getFile($meta->favicon) }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ getFile($meta->favicon) }}" type="image/x-icon">

    @php
        use App\Helpers\StructuredDataHelper;
        $websiteSchema = StructuredDataHelper::generateWebsiteSchema();
        $organizationSchema = StructuredDataHelper::generateOrganizationSchema();
    @endphp
    
    {!! StructuredDataHelper::renderJsonLd($websiteSchema) !!}
    {!! StructuredDataHelper::renderJsonLd($organizationSchema) !!}
    
    @stack('structured-data')
    
    @yield('head')

    @vite('resources/css/app.css')
    @vite('resources/css/styles.css')
    @vite('resources/js/app.js')

</head>
{{-- stack ui css --}}
@stack('styles')

<body>
    <div class="min-h-full">
        @include('widget.client.navbar')
        @include('widget.client.header')
        <main>
            @yield('header')
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 ">
                @yield('content')
            </div>
        </main>
        @include('widget.client.footer')
    </div>
</body>
{{-- stack script --}}
@stack('scripts')

</html>
