document.addEventListener('DOMContentLoaded', function () {
	'use strict';

	var choices = document.querySelectorAll('.sla-icon-choice');
	choices.forEach(function (choice) {
		var input = choice.querySelector('input[type="radio"]');
		if (!input) {
			return;
		}

		input.addEventListener('change', function () {
			choices.forEach(function (c) {
				c.classList.remove('is-selected');
			});
			if (input.checked) {
				choice.classList.add('is-selected');
			}
		});
	});
});
