@php($googleAdsTagId = config('services.google.ads_tag_id'))

@if($googleAdsTagId)
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAdsTagId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ $googleAdsTagId }}');
    </script>
@endif
