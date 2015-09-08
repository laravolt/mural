<div class="comment" data-id="{{ $comment['id'] }}" id="mural-comment-{{ $comment['id'] }}">
    <a href="{{ $comment->author->commentator_permalink }}" class="avatar">
        <img src="{{ $comment->author->commentator_avatar }}" title="">
    </a>

    <div class="content">
        <a href="{{ $comment->author->commentator_permalink }}" class="author">{{ $comment->author->commentator_name }}</a>

        <div class="metadata">
            <span class="date">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }}</span>
        </div>
        <div class="text">
            {{ $comment->body }}
            <span>&nbsp;</span>
        </div>
        <div class="actions">
            @if(auth()->check() && auth()->user()->canModerateComment())
                <form class="form-remove" action="{{ route('mural.destroy', $comment->id) }}" method="POST">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <a class="button-remove"><i class="icon trash"></i> @lang('mural::mural.remove')</a>
                </form>
            @endif
        </div>
    </div>

    <div class="ui inverted dimmer">
        <div class="ui mini text loader">@lang('mural::mural.loading')</div>
    </div>
</div>
