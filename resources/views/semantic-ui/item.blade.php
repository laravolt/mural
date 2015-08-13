<div class="comment" data-id="{{ $comment['id'] }}">
    <a class="avatar">
        <img src="https://s3.amazonaws.com/uifaces/faces/twitter/mantia/24.jpg">
    </a>

    <div class="content">
        <a class="author">{{ $comment->author->name }}</a>

        <div class="metadata">
            <span class="date">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }}</span>
        </div>
        <div class="text">
            {{ $comment->body }}
            <span>&nbsp;</span>
        </div>
        <div class="actions">
            {{--<a class="reply">Reply</a>--}}
        </div>
    </div>
</div>
