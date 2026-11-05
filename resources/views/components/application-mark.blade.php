<picture class="block shrink-0 w-[120px] h-10 sm:w-[132px] sm:h-11 aspect-[3/1]">
    <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
    <img src="{{ asset('images/logo.png') }}"
         alt="{{ config('app.name', 'BookLedger') }}"
         width="132"
         height="44"
         decoding="sync"
         fetchpriority="high"
         {{ $attributes->merge(['class' => 'block w-[120px] h-10 sm:w-[132px] sm:h-11 aspect-[3/1] object-contain select-none']) }}>
</picture>