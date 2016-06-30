@if(auth()->check())
    <form class="mural-form" method="POST" action="{{ route('mural.store') }}">

        <h3 class="title">@lang('mural::mural.title_with_count', ['count' => $totalComment])</h3>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="commentable_id" value="{{ $content->getKey() }}">
        <input type="hidden" name="commentable_type" value="{{ get_class($content) }}">

        @if($room)
        <input type="hidden" name="room" value="{{ $room }}">
        @endif

        <div class="form-group">
            <textarea name="body" placeholder="@lang('mural::mural.write_a_comment')" rows="5" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-default btn-block mural-submit">
            @lang('mural::mural.submit')
        </button>
    </form>
@else
    <div class="alert alert-warning" role="alert">
        @lang('mural::mural.must_login', ['link' => url('login')])
    </div>
@endif
