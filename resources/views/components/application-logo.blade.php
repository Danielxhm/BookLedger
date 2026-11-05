<picture class="block shrink-0 w-[120px] h-10 aspect-[3/1]">
    <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
    <img src="{{ asset('images/logo.png') }}"
         alt="{{ config('app.name', 'BookLedger') }}"
         width="120"
         height="40"
         decoding="sync"
         fetchpriority="high"
         {{ $attributes->merge(['class' => 'block w-[120px] h-10 aspect-[3/1] object-contain select-none']) }}>
</picture>