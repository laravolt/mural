<script type="text/javascript">
    $(function(){
        var mural = $('.mural-container');

        @if(auth()->check())
        mural.on('submit', '.mural-form', function(e) {
            e.preventDefault();
            var form = $(e.currentTarget);
            var btn = form.find('button[type=submit]');
            var commentContainer = $(e.delegateTarget).find('.mural-list');

            if(btn.hasClass('disabled')) {
                return false;
            }

            btn.addClass('loading disabled');

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function(response){
                    commentContainer.prepend(response.html);
                    mural.find('.title').html(response.title);
                    form.find("input[type=text], textarea").val('');
                    mural.find('.button-empty').remove();
                },
                error: function(response){
                    alert(response.responseText);
                },
                complete: function() {
                    btn.removeClass('loading disabled');
                }
            });
        });
        @endif

        mural.on('click', '.mural-more', function(e) {
            e.preventDefault();
            var btn = $(e.currentTarget);

            if(btn.hasClass('disabled')) {
                return false;
            }

            btn.addClass('loading disabled');
            var commentContainer = $(e.delegateTarget).find('.mural-list');

            $.ajax({
                type: "GET",
                url: btn.data('url'),
                data: {last_id: commentContainer.find('.comment:last').data('id')},
                success: function (html) {
                    if(html.length > 0) {
                        commentContainer.append(html);
                        btn.removeClass('disabled');
                    } else {
                        btn.attr("disabled", "disabled").html(btn.data('no-more-content'));
                    }
                },
                error: function () {
                    alert('Something goes wrong');
                },
                complete: function() {
                    btn.removeClass('loading');
                }
            });
            return false;
        });

        @if(auth()->check() && auth()->user()->canModerateComment())

        mural.on('click', '.button-remove', function(e) {
            $(this).parent('form').trigger('submit');
        });

        mural.on('submit', '.form-remove', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('.button');
            var comment = form.parents('.comment:first');

            comment.dimmer('show');

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: form.attr('action'),
                data: form.serialize(),
                success: function (response) {
                    if(response.status == 1) {
                        $('#mural-comment-' + response.id).hide();
                        mural.find('.title').html(response.title);
                    }
                },
                error: function (response) {
                    alert(response.responseText);
                },
                complete: function() {
                    comment.dimmer('hide');
                }
            });
        });
        @endif
    });
</script>
