<div class="comment" data-id="{{ $comment['id'] }}" id="mural-comment-{{ $comment['id'] }}" style="margin-bottom: 1%;">
    <div class="media" style="padding-right: 1em;">
        <div class="media-left">
            <a href="{{ $comment->author->commentator_permalink }}" class="avatar pull-left">
                <img src="{{ $comment->author->commentator_avatar }}" title="" class="img-rounded media-object">
            </a>
        </div>
        <div class="media-body">
            <div class="media-heading">
                <a href="{{ $comment->author->commentator_permalink }}" class="author"><b>{{ $comment->author->commentator_name }}</b>
                </a>
                <span>&nbsp;</span>
                <span class="date">
                    {{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }}
                </span>
                @if(auth()->check() && auth()->user()->canModerateComment())
                    <form class="form-remove" action="{{ route('mural.destroy', $comment->id) }}" method="POST" style="display: none;">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        <a class="btn button-remove pull-right"><i class="glyphicon glyphicon-trash"></i> @lang('mural::mural.remove')</a>
                    </form>
                @endif
            </div>
            <p>
                {{ $comment->body }}
                <span>&nbsp;</span>
            </p>
        </div>
    </div>

    @if(config('mural.vote'))
        <div class="extra content">
            {!! \Laravolt\Votee\VoteeFacade::render($comment, ['class' => 'basic', 'size' => 'mini']) !!}
        </div>
    @endif
</div>
