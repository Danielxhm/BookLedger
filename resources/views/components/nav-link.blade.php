@props(['active', 'navigate' => true])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-1.5 border-b-2 border-gray-900 text-sm font-medium leading-5 text-gray-900 whitespace-nowrap focus:outline-none focus:border-gray-900 transition-colors duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-1.5 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 whitespace-nowrap hover:text-gray-900 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition-colors duration-150 ease-in-out';
@endphp

<a @if($navigate && !$attributes->has('@click.prevent')) wire:navigate @endif {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
