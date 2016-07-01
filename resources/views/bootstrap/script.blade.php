<script type="text/javascript">
    $(function () {
        var murals = $('.mural-container');
        var loader = '<i class="fa fa-spinner fa-spin"></i>';

        @if(auth()->check())
        murals.on('submit', '.mural-form', function (e) {
            e.preventDefault();
            var mural = $(e.delegateTarget);
            var form = $(e.currentTarget);
            var btn = form.find('button[type=submit]');
            var commentContainer = mural.find('.mural-list');

            if (btn.hasClass('disabled')) {
                return false;
            }
            btn.prepend(loader);
            btn.addClass('disabled');
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    commentContainer.prepend(response.html);
                    form.find('.title').html(response.title);
                    form.find("input[type=text], textarea").val('');
                    mural.find('.button-empty').remove();
                },
                error: function (response) {
                    alert(response.responseText);
                },
                complete: function () {
                    $('.mural-submit > i').remove();
                    btn.removeClass('disabled');
                }
            });
        });
        @endif
        murals.on('click', '.mural-more', function (e) {
            e.preventDefault();
            var mural = $(e.delegateTarget);
            var btn = $(e.currentTarget);

            if (btn.hasClass('disabled')) {
                return false;
            }

            btn.prepend(loader);
            btn.addClass('disabled');

            var commentContainer = $(e.delegateTarget).find('.mural-list');

            mural.data('page', parseInt(mural.data('page')) + 1);
            var param = jQuery.extend({}, mural.data());
            var url = mural.data('url');
            delete param.url;

            $.ajax({
                type: "GET",
                url: url + '?' + decodeURIComponent($.param(param)),
                success: function (html) {
                    if (html.length > 0) {
                        commentContainer.append(html);
                        btn.removeClass('disabled');
                    } else {
                        btn.attr("disabled", "disabled").html(btn.data('no-more-content'));
                    }
                },
                error: function () {
                    alert('Something goes wrong');
                },
                complete: function () {
                    $('.mural-more > i').remove();
                }
            });
            return false;
        });

        murals.find('.dropdown-sort')
                .dropdown({
                    onChange: function (value, text, item) {
                        var mural = $(item).parents('.mural-container');
                        var commentContainer = mural.find('.mural-list');

                        mural.find('.comments').dimmer('show');
                        mural.data('sort', value);
                        mural.data('page', 1);

                        var param = jQuery.extend({}, mural.data());
                        var url = mural.data('url');
                        delete param.url;

                        $.ajax({
                            type: "GET",
                            url: url + '?' + decodeURIComponent($.param(param)),
                            success: function (html) {
                                commentContainer.html(html);
                            },
                            error: function () {
                                alert('Something goes wrong');
                            },
                            complete: function () {
                                mural.find('.comments').dimmer('hide');
                                mural.find('.mural-more').removeClass('loading disabled').removeAttr("disabled").html('{{ trans('mural::mural.load_more') }}');
                            }
                        });
                    }
                });

        @if(auth()->check() && auth()->user()->canModerateComment())

        murals.on({
            mouseover : function() {
                $(this).find('.form-remove').css('display', 'inline');
            },
            mouseleave : function() {
                $(this).find('.form-remove').hide();
            }
        }, '.comment');
        

        murals.on('click', '.button-remove', function (e) {
            $(this).parent('form').trigger('submit');
        });

        murals.on('submit', '.form-remove', function (e) {
            e.preventDefault();
            var mural = $(e.delegateTarget);
            var form = $(this);
            var comment = form.parents('.comment:first');

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: form.attr('action'),
                data: form.serialize(),
                success: function (response) {
                    if (response.status == 1) {
                        $('#mural-comment-' + response.id).fadeOut('1600','linear');
                        mural.find('.title').html(response.title);
                    }
                },
                error: function (response) {
                    alert(response.responseText);
                },
                complete: function () {
                }
            });
        });
        @endif
    });
</script>
