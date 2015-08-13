<div class="comment" data-id="{{ $comment['id'] }}">
    <a class="avatar">
        <img src="{{ $comment->author->avatar_url }}" title="">
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
    </div>
</div>
