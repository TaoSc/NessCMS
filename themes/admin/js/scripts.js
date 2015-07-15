$(function () {
	function include(arr, obj) {
		return (arr.indexOf(obj) != -1);
	}

	function AutoComplete(tagType) {
		var tags = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.whitespace,
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			identify: function (obj) {return obj.id;},
			// prefetch: {url: linksDir + 'tags/' + tagType},
			remote: {
				url: linksDir + 'tags/' + tagType
			}
		});

		$('input[name=tag-temp]').typeahead({
			minLength: 2,
			highlight: true
		}, {
			name: 'tags',
			source: tags,
			display: 'name'
		})
		.bind('typeahead:select', function(ev, suggestion) {
			latestSuggestedTag = suggestion;
			var tagDropdown = $(this).closest('.form-group');

			tagDropdown.find('.btn-success').prop('disabled', false);
			tagDropdown.find('input[name=tag-id-temp]').val(suggestion.id);
		})
		.bind('typeahead:change', function(ev, suggestion) {
			var tagDropdown = $(this).closest('.form-group'),
				tagId = tagDropdown.find('input[name=tag-id-temp]');

			if (typeof(latestSuggestedTag) == 'undefined' || latestSuggestedTag.name != suggestion)
				tagDropdown.find('.btn-success').prop('disabled', true);
		});
	};

	var addTag = function (event) {
		event.preventDefault();

		var tagDropdown = $(this).closest('.form-group'),
			tagName = tagDropdown.find('input[name=tag-temp]'),
			tagId = tagDropdown.find('input[name=tag-id-temp]'),
			tagType = tagDropdown.find('input[name=tag-type-temp]'),
			tagsList = tagDropdown.find('input[name=tags]'),
			tempTagsList = JSON.parse(tagsList.val()),
			tagLabel = '<span class="tag-label" data-id="' + tagId.val() + '">' +
							'<span class="label label-primary"><span class="glyphicon glyphicon-tag"></span> ' + tagName.val() + '</span>' +
							'<a class="btn btn-xs icon-btn btn-muted btn-remove" href="#remove"><span class="glyphicon btn-glyphicon glyphicon-remove text-danger"></span></a>' +
						'</span>';

		if (include(tempTagsList, parseInt(tagId.val())))
			tagAddFailed();
		else {
			tempTagsList.push(parseInt(tagId.val()));
			tagDropdown.find('.btn-success').prop('disabled', true);
			tagsList.val(JSON.stringify(tempTagsList));

			$('input[name=tag-temp]').typeahead('val', '');
			tagName.val('');
			tagId.val('');

			$(tagLabel).prependTo($('.tags-chosen'));
		}
	},

	removeTag = function (event) {
		event.preventDefault();

		var tag = $(this).closest('.tag-label'),
			tagId = parseInt(tag.attr('data-id')),
			tagsList = $('input[name=tags]'),
			tempTagsList = JSON.parse(tagsList.val());

		tempTagsList.splice(tempTagsList.indexOf(tagId), 1);
		tagsList.val(JSON.stringify(tempTagsList));
		tag.remove();
	},

	selectTagType = function (event) {
		event.preventDefault();

		var tagDropdownMenu = $(this).closest('.input-group-select'),
			typeSlug = $(this).attr('href').replace('#', ''),
			concept = $(this).text();

		tagDropdownMenu.find('.concept').text(concept);
		tagDropdownMenu.find('input[name=tag-type-temp]').val(typeSlug);
		$(this).closest('.form-group').find('.btn-success').prop('disabled', true);

		$('input[name=tag-temp]').typeahead('destroy');
		delete typeAhead;
		typeAhead = new AutoComplete($('input[name=tag-type-temp]').val());
	}

	$(document).on('click', '.btn-add', addTag);
	$(document).on('click', '.btn-remove', removeTag);
	$(document).on('click', '.dropdown-menu.tags-types a', selectTagType);
	var typeAhead = new AutoComplete($('input[name=tag-type-temp]').val());
});