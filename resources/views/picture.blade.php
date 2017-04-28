{{-- Note: see related ViewComposer --}}
<picture>
    @foreach ($picture->sources as $source)
        <source srcset="{{ $source->srcset }}" {!! $source->mediaQuery ? 'media="'.$source->mediaQuery.'"' : '' !!} />
    @endforeach

    <img src="{{ $picture->src }}" alt="{{ $picture->alt }}">
</picture>
