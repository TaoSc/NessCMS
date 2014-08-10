$(function () {
	$(function() {
		$('img').lazyload({effect : 'fadeIn'});
	});

	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
	$('#login-btn').click(function () {
		$('#login').modal({remote: this.attr(href)}, 'show');
	});

	var sideSlider = $('[data-toggle=collapse-side]'),
		sel = sideSlider.attr('data-target'),
		sel2 = sideSlider.attr('data-target-2');
	sideSlider.click(function(event){
		$(sel).toggleClass('in');
		$(sel2).toggleClass('out');
	});

	$('#headlines-carousel').carousel({
		interval: 2500
	});

	if ($('.poll-template').html()) {
		var get = $.get(topDir + 'polls/' + $('.poll-template').attr('data-poll-id'));
		get.done(function (datas) {
			$('.poll-template').replaceWith(datas);
		});

		$('.poll-template').parent().on('click', 'input[name=poll_radios]', function () {
			var posting = $.post(topDir + 'polls/' + $('.poll-template').attr('data-poll-id') + '/send', {'poll_radios': $('input[name=poll_radios]:checked').attr('value')});
			posting.done(function (datas) {
				var decodedDatas = JSON.parse(datas);

				$('.poll-template').replaceWith(decodedDatas['poll_answers']);
				if ($('.poll-participants').html())
					$('.poll-participants').text(decodedDatas['poll_participants']);
			});
		});
	}

	if ($('#comments').html()) {
		var commentCreateLabel = $('label[for=content]').text(),
			commentsLocation = $('input#location').attr('value'),
			commentsLatestLink = null;

		$('#comments').parent().on('click', '.comments-toolbox a', function () {
			actualLink = $(this).attr('href');
			if (commentsLatestLink !== actualLink) {
				var get = $.get(actualLink);
				get.done(function (datas) {
					commentsLatestLink = actualLink;
					$('#comments').replaceWith(datas);
					$('#comments input#location').attr('value', commentsLocation);
					$('.comments-list img').lazyload({effect : 'fadeIn'});
				});
			}

			return false;
		});

		$('#comments').parent().on('click', 'button.vote-btn', function () {
			var commentId = $(this).attr('data-id'),
				voteState = $(this).attr('value'),
				posting = $.post(topDir + 'votes/comments/' + commentId, {'vote_state': voteState});

			posting.done(function (datas) {
				var decodedDatas = JSON.parse(datas);
					btnSelector = 'button.vote-btn[data-id=' + commentId + ']';

				if (voteState === 'strip')
					$(btnSelector + '[value=' + voteState + ']').remove();

				$(btnSelector).each(function () {
					var btnState = $(this).attr('value');

					if (btnState !== 'strip') {
						$('span.votes-nbr', this).text(decodedDatas[btnState]);

						$(this).prop('disabled', function (index, value) {
							return !value;
						});
					}
				});
			});

			return false;
		});

		$('#comments').parent().on('click', 'button.answer-btn', function () {
			var commentId = $(this).attr('value');

			$('label[for=content]').text($(this).text());
			$('textarea#content').focus();
			$('#parent_id').attr('value', $(this).attr('value'));
			if ($('#cancel-reply').css('display') === 'none')
				$('#cancel-reply').show();

			return false;
		});

		$('#comments').parent().on('click', '.comment-create #cancel-reply', function () {
			$('label[for=content]').text(commentCreateLabel);
			$('textarea[name=content]').focus();
			$('#parent_id').attr('value', 0);
			$('#cancel-reply').hide();

			return false;
		});
	}

	$('.news-list > .important.news').each(function () {
		$(this).hover(function () {
			$('.mask', this).stop(true).animate({
				opacity: '0.5'
			}, 200);
		}, function () {
			$('.mask', this).stop(true).animate({
				opacity: '0.2'
			}, 200);
		});
	});
});