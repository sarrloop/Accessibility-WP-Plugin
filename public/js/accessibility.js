(function () {
	'use strict';

	var config = window.SarrloopAccessibility || {};
	var root = document.documentElement;
	var storageKey = config.storageKey || 'sarrloopAccessibility';
	var widget = document.querySelector('[data-sla-widget]');

	if (!widget) {
		return;
	}

	var panel = widget.querySelector('.sla-panel');
	var toggle = widget.querySelector('.sla-toggle');
	var close = widget.querySelector('.sla-close');

	var stateDefaults = {
		textScale: 1,
		contrast: false,
		grayscale: false,
		highlight_links: false,
		stop_animations: false,
		large_cursor: false,
		readable_font: false,
		focus: false,
		keyboard: false
	};

	var state = Object.assign({}, stateDefaults);

	try {
		var saved = JSON.parse(localStorage.getItem(storageKey));
		if (saved && typeof saved === 'object') {
			state = Object.assign(state, saved);
		}
	} catch (e) {}

	function save() {
		try {
			localStorage.setItem(storageKey, JSON.stringify(state));
		} catch (e) {}
	}

	var textSelectors = 'h1, h2, h3, h4, h5, h6, p, li, blockquote, dt, dd, th, td, figcaption, label, button, a';

	function applyTextScaling(scale) {
		if (scale === 1) {
			root.style.removeProperty('--sla-text-scale');
			root.style.removeProperty('font-size');
			var scaled = document.querySelectorAll('[data-sla-orig-font]');
			for (var j = 0; j < scaled.length; j++) {
				scaled[j].style.removeProperty('font-size');
				scaled[j].removeAttribute('data-sla-orig-font');
			}
			return;
		}

		root.style.setProperty('--sla-text-scale', scale);
		root.style.fontSize = (100 * scale) + '%';

		var elements = document.querySelectorAll(textSelectors);
		for (var i = 0; i < elements.length; i++) {
			var el = elements[i];
			if (widget && widget.contains(el)) {
				continue;
			}

			var orig = el.getAttribute('data-sla-orig-font');
			if (!orig) {
				var comp = window.getComputedStyle(el).fontSize;
				orig = parseFloat(comp);
				if (orig && orig > 0) {
					el.setAttribute('data-sla-orig-font', orig);
				}
			} else {
				orig = parseFloat(orig);
			}

			if (orig && orig > 0) {
				el.style.setProperty('font-size', (orig * scale) + 'px', 'important');
			}
		}
	}

	function apply() {
		applyTextScaling(state.textScale);

		root.classList.toggle('sla-contrast', !!state.contrast);
		root.classList.toggle('sla-grayscale', !!state.grayscale);
		root.classList.toggle('sla-highlight-links', !!state.highlight_links);
		root.classList.toggle('sla-stop-animations', !!state.stop_animations);
		root.classList.toggle('sla-large-cursor', !!state.large_cursor);
		if (document.body) {
			document.body.classList.toggle('sla-large-cursor', !!state.large_cursor);
		}
		root.classList.toggle('sla-readable-font', !!state.readable_font);
		root.classList.toggle('sla-enhanced-focus', !!state.focus);
		root.classList.toggle('sla-keyboard-mode', !!state.keyboard);

		var buttons = widget.querySelectorAll('[data-sla-action]');
		for (var i = 0; i < buttons.length; i++) {
			var button = buttons[i];
			var action = button.getAttribute('data-sla-action');

			if (stateDefaults[action] !== undefined) {
				var isActive = !!state[action];
				button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
				button.classList.toggle('is-active', isActive);
			}
		}

		var btnInc = widget.querySelector('[data-sla-action="increase-text"]');
		var btnDec = widget.querySelector('[data-sla-action="decrease-text"]');
		var btnRes = widget.querySelector('[data-sla-action="reset-text"]');
		if (btnInc) {
			btnInc.classList.toggle('is-active', state.textScale > 1);
		}
		if (btnDec) {
			btnDec.classList.toggle('is-active', state.textScale < 1);
		}
		if (btnRes) {
			btnRes.classList.toggle('is-active', state.textScale === 1);
		}
	}

	function setTextScale(value) {
		state.textScale = Math.round(Math.max(0.8, Math.min(1.5, value)) * 10) / 10;
		save();
		apply();
	}

	function toggleFeature(key) {
		if (stateDefaults[key] === undefined) {
			return;
		}
		state[key] = !state[key];
		save();
		apply();
	}

	function reset() {
		state = Object.assign({}, stateDefaults);
		try {
			localStorage.removeItem(storageKey);
		} catch (e) {}
		apply();
		if ('speechSynthesis' in window) {
			window.speechSynthesis.cancel();
		}
	}

	function readAloud() {
		if (!('speechSynthesis' in window)) {
			return;
		}

		if (window.speechSynthesis.speaking) {
			window.speechSynthesis.cancel();
			return;
		}

		var selection = window.getSelection ? window.getSelection().toString().trim() : '';
		var mainEl = document.querySelector('main') || document.querySelector('article') || document.body;
		var text = selection || (mainEl ? mainEl.innerText : '');
		var clean = text.replace(/\s+/g, ' ').trim().slice(0, 10000);

		if (!clean) {
			return;
		}

		var utterance = new SpeechSynthesisUtterance(clean);

		// Use site locale if provided, fallback to html lang or browser default
		var lang = config.siteLocale ? config.siteLocale.replace('_', '-') : (document.documentElement.lang || 'en-US');
		utterance.lang = lang;

		window.speechSynthesis.speak(utterance);
	}

	function activateKeyboardMode() {
		if (state.keyboard) {
			setTimeout(function () {
				var first = document.querySelector('a[href], button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
				if (first) {
					first.focus();
				}
			}, 50);
		}
	}

	function openPanel() {
		toggle.setAttribute('aria-expanded', 'true');
		panel.hidden = false;
		if (close) {
			close.focus();
		}
	}

	function closePanel() {
		toggle.setAttribute('aria-expanded', 'false');
		panel.hidden = true;
		toggle.focus();
	}

	toggle.addEventListener('click', function () {
		var isOpen = toggle.getAttribute('aria-expanded') === 'true';
		if (isOpen) {
			closePanel();
		} else {
			openPanel();
		}
	});

	if (close) {
		close.addEventListener('click', function () {
			closePanel();
		});
	}

	panel.addEventListener('click', function (event) {
		var button = event.target.closest('[data-sla-action]');
		if (!button) {
			return;
		}

		var action = button.getAttribute('data-sla-action');

		if (action === 'increase-text') {
			setTextScale(state.textScale + 0.1);
		} else if (action === 'decrease-text') {
			setTextScale(state.textScale - 0.1);
		} else if (action === 'reset-text') {
			setTextScale(1);
		} else if (action === 'reset') {
			reset();
		} else if (action === 'tts') {
			readAloud();
		} else if (action === 'keyboard') {
			toggleFeature('keyboard');
			activateKeyboardMode();
		} else if (stateDefaults[action] !== undefined) {
			toggleFeature(action);
		}
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape' && !panel.hidden) {
			closePanel();
		}
	});

	// Close panel when clicking outside
	document.addEventListener('click', function (event) {
		if (!panel.hidden && !widget.contains(event.target)) {
			toggle.setAttribute('aria-expanded', 'false');
			panel.hidden = true;
		}
	});

	apply();
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			apply();
		});
	}
}());
