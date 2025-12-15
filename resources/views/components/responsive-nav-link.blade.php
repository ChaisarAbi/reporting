@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-teal-300 text-start text-base font-medium text-white bg-teal-900/30 focus:outline-none focus:text-white focus:bg-teal-900/50 focus:border-teal-500 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-white/80 hover:text-white hover:bg-teal-900/20 hover:border-teal-300 focus:outline-none focus:text-white focus:bg-teal-900/30 focus:border-teal-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
