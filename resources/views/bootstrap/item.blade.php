<div class="row comment" data-id="{{ $comment['id'] }}" id="mural-comment-{{ $comment['id'] }}" style="margin-bottom: 1%;">
    <div class="col-md-1 col-xs-2" style="width: 5.33%;">
        <a href="{{ $comment->author->commentator_permalink }}" class="avatar">
            <img src="{{ $comment->author->commentator_avatar }}" title="" class="img-rounded">
        </a>
    </div>

    <div class="row">
        <div class="col-md-11">
            <div class="row" style="min-height: 2.5em;">
                <div class="col-md-1 col-xs-1" style="padding-right: 1%;">
                    <a href="{{ $comment->author->commentator_permalink }}" class="author"><b>{{ $comment->author->commentator_name }}</b>
                    </a>
                </div>
                <div class="col-md-2 col-xs-6" style="padding: 0">
                    <span class="date">
                        {{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }}
                    </span>
                </div>  
                <div class="col-md-9 col-xs-2">
                    @if(auth()->check() && auth()->user()->canModerateComment())
                        <form class="form-remove" action="{{ route('mural.destroy', $comment->id) }}" method="POST" style="display: none;">
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                            <a class="btn button-remove pull-right"><i class="glyphicon glyphicon-trash"></i> @lang('mural::mural.remove')</a>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-10 col-xs-12">
                    {{ $comment->body }}
                    <span>&nbsp;</span>
                </div>
            </div>
        </div>
    </div>

    @if(config('mural.vote'))
    <div class="extra content">
        {!! \Laravolt\Votee\VoteeFacade::render($comment, ['class' => 'basic', 'size' => 'mini']) !!}
    </div>
    @endif

    
</div>
