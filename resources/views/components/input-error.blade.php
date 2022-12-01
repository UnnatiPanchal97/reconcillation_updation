@props(['messages'])
@if ($messages)
    <div {{ $attributes->merge(['class' => 'alert alert-danger mt-2 mb-0']) }}>
        @foreach ((array) $messages as $message)
            {{ $message }}
        @endforeach
    </div>
@endif