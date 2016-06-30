<div class="mural-container {{ $options->get('class') }}"
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
    
    <div class="row">
        <div class="col-md-12 mural-list" style="margin: 1em 0">
            @include('mural::list', ['comments' => $comments, 'options' => $options])
        </div>
        <div class="col-md-12">
            <div class="ui mini text loader">
                <i class="fa fa-spinner fa-spin"></i>
                @lang('mural::mural.loading')
            </div>
        </div>
    </div>

    @if(!$comments->isEmpty())
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-default btn-block submit mural-more" data-no-more-content="@lang('mural::mural.no_more_content')">
                
                @lang('mural::mural.load_more')</button>
            </div>
        </div>
    @else
        <a href="#" class="btn btn-default btn-block disabled button-empty" role="button">@lang('mural::mural.empty')</a>
    @endif

</div>

@if(config('mural.script_stack'))
    @push(config('mural.script_stack'))
    @include('mural::script')
    @endpush
@else
    @include('mural::script')
@endif
