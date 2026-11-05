<a href="/">
    <picture class="block shrink-0 w-[168px] h-14 aspect-[3/1]">
        <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
        <img src="{{ asset('images/logo.png') }}"
             alt="{{ config('app.name', 'BookLedger') }}"
             width="168"
             height="56"
             decoding="sync"
             fetchpriority="high"
             class="block w-[168px] h-14 aspect-[3/1] object-contain select-none">
    </picture>
</a>