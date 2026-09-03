$(function () {
	$('[data-filter-input]').on('input', function () {
		const query = $(this).val().toLowerCase().trim();
		const category = $('[data-filter-category]').val() || '';
		let visible = 0;
		$('[data-filter-item]').each(function () {
			const item = $(this);
			const matchesText = !query || item.text().toLowerCase().includes(query);
			const matchesCategory = !category || item.data('category') === category;
			item.toggle(matchesText && matchesCategory);
			if (matchesText && matchesCategory) visible++;
		});
		$('[data-empty-state]').toggle(visible === 0);
	});
	$('[data-filter-category]').on('change', function () { $('[data-filter-input]').trigger('input'); });

	$('#courseSearch').on('input', function () {
		const query = $(this).val().toLowerCase().trim();
		const selector = $('#courseSelector');
		let matches = 0;
		selector.find('option[data-course-name]').each(function () {
			const match = !query || $(this).data('course-name').includes(query) || (query === 'webdev' && $(this).data('course-name').includes('website development'));
			$(this).toggle(match);
			if (match) matches++;
		});
		$('#courseSearchStatus').text(query ? (matches + ' course' + (matches === 1 ? '' : 's') + ' found. Select one to view its syllabus.') : 'Search the list to find a syllabus quickly.');
	});

	$('[data-gallery-category]').on('click', function () {
		const button = $(this);
		const category = button.data('gallery-category');
		$('[data-gallery-category]').removeClass('active').removeClass('btn-primary').addClass('btn-outline-primary');
		button.addClass('active').removeClass('btn-outline-primary').addClass('btn-primary');
		let visible = 0;
		$('[data-gallery-item]').each(function () {
			const show = !category || $(this).data('category') === category;
			$(this).toggle(show);
			if (show) visible++;
		});
		$('[data-gallery-empty]').toggle(visible === 0);
	});

	$('#galleryModal').on('show.bs.modal', function (event) {
		const button = $(event.relatedTarget);
		$('#galleryModalImage').attr('src', button.data('gallery-image')).attr('alt', button.data('gallery-title'));
		$('#galleryModalTitle').text(button.data('gallery-title'));
		$('#galleryModalDescription').text(button.data('gallery-description') || '');
	});

	$('.chatbot-toggle').on('click', function () {
		const panel = $('#chatbotPanel');
		const isOpen = !panel.prop('hidden');
		panel.prop('hidden', isOpen);
		$(this).attr('aria-expanded', String(!isOpen));
		if (!isOpen) $('#chatbotInput').trigger('focus');
	});
	$('.chatbot-close').on('click', function () { $('#chatbotPanel').prop('hidden', true); $('.chatbot-toggle').attr('aria-expanded', 'false'); });
	$('#chatbotForm').on('submit', function (event) {
		event.preventDefault();
		const input = $('#chatbotInput');
		const message = input.val().trim();
		if (!message) return;
		$('#chatbotMessages').append($('<div class="chatbot-message user">').text(message));
		input.val('').prop('disabled', true);
		$.post('chatbot.php', { message: message }).done(function (data) {
			const bot = $('<div class="chatbot-message bot">').text(data.reply || 'Please try another question.');
			if (data.link && data.link.url) bot.append($('<a class="chatbot-link">').attr('href', data.link.url).text(data.link.label));
			$('#chatbotMessages').append(bot);
		}).fail(function () { $('#chatbotMessages').append($('<div class="chatbot-message bot">').text('I am temporarily unavailable. Please use the Contact page for help.')); }).always(function () { input.prop('disabled', false).trigger('focus'); $('#chatbotMessages').scrollTop($('#chatbotMessages')[0].scrollHeight); });
	});
});
