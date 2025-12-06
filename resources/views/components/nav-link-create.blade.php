@props(['active'])

@php
$baseClasses = 'inline-flex items-center text-sm font-medium duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $baseClasses . ' ' . ($attributes->get('class') ?? '')]) }}>
    {{ $slot }}
</a>
