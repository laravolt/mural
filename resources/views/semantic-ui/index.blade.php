<div class="ui segment mural-container {{ $options->get('class') }}"
     data-id="{{ $content->getKey() }}"
     data-type="{{ get_class($content) }}"
     data-room="{{ $room }}"
     data-sort="latest"
     data-page="1"
     data-url="{{ route('mural.index') }}"
>
    @if(!$options->get('readonly'))
        @include('mural::form')
    @endif

    @if(config('mural.vote'))
        @include(('mural::sort'))
    @endif

    <div class="ui minimal comments">
        <div class="mural-list">
            @include('mural::list', ['comments' => $comments, 'options' => $options])
        </div>
        <div class="ui inverted dimmer">
            <div class="ui mini text loader">@lang('mural::mural.loading')</div>
        </div>
    </div>

    @if(!$comments->isEmpty())
        <button class="ui fluid basic submit button mural-more" data-no-more-content="@lang('mural::mural.no_more_content')">@lang('mural::mural.load_more')</button>
    @else
        <button class="button ui basic fluid disabled button-empty">@lang('mural::mural.empty')</button>
    @endif
</div>

@if(config('mural.script_stack'))
    @push(config('mural.script_stack'))
    @include('mural::script')
    @endpush
@else
    @include('mural::script')
@endif
