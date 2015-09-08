@if(auth()->check())
    <form class="ui reply form mural-form" method="POST" action="{{ route('mural.store') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="commentable_id" value="{{ $content->getKey() }}">

        @if($room)
        <input type="hidden" name="room" value="{{ $room }}">
        @endif

        <div class="field">
            <textarea name="body" placeholder="@lang('mural::mural.write_a_comment')" rows="5"></textarea>
        </div>
        <button type="submit" class="ui fluid submit button">@lang('mural::mural.submit')</button>
    </form>
@else
    <div class="ui message warning">
        @lang('mural::mural.must_login', ['link' => url('/auth/login')])
    </div>
@endif
