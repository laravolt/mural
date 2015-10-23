<div class="ui segment mural-container">
    <div class="column" id="form_comment">
        <h3 class="ui header title">@lang('mural::mural.title_with_count', ['count' => $totalComment])</h3>
        @if(!$options->get('readonly'))
        @include('mural::form')
        @endif
    </div>

    <div class="ui comments minimal mural-list">
        @include('mural::list', ['comments' => $comments, 'options' => $options])
    </div>
    @if(!$comments->isEmpty())
    <a href="#" data-url="{{ route('mural.fetch', ['commentable_id' => $content->getKey(), 'room' => $room]) }}" class="ui fluid basic submit button mural-more" data-no-more-content="@lang('mural::mural.no_more_content')">@lang('mural::mural.load_more')</a>
    @else
        <button class="button ui basic fluid disabled button-empty">@lang('mural::mural.empty')</button>
    @endif
</div>
