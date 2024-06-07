(function() {

	// Go to top button
	document.getElementById('top-btn').addEventListener('transitionend', function () {
		if (this.style.bottom !== '-100px') return;
	});

	function setScrollTop() {
		var timer;
		window.addEventListener('scroll', function onAppScroll() {
			if (timer) {
				window.clearTimeout(timer);
			}
			timer = window.setTimeout(function () {
				var scrollTop = window.scrollY || document.documentElement.scrollTop;
				var topBtn = document.getElementById('top-btn');
				if (scrollTop < 1000) {
					topBtn.style.bottom = '-100px';
				} else {
					topBtn.style.bottom = '10px';
				}
				setScrollTop();
			}, 500);
			window.removeEventListener('scroll', onAppScroll);
		});
	}
	setScrollTop();

	document.getElementById('top-btn').addEventListener('click', function (e) {
		e.preventDefault();
		window.scroll({ top: 0, left: 0, behavior: 'smooth' });
	});

	// Autosize textarea
	document.body.addEventListener('focus', updateTextareaSize, true);
	document.body.addEventListener('change', updateTextareaSize, true);

	function updateTextareaSize(event) {
		var target = event.target;
		if (target.tagName === 'TEXTAREA' && !target.classList.contains('cke_source')) {
			autosize(target);
			autosize.update(target);
		}
	}

	// Tooltips
	new bootstrap.Tooltip(document.body, {
		selector: '[data-bs-title]'
	});

	document.body.addEventListener('click', disposeTooltip, true);
	document.body.addEventListener('touchend', disposeTooltip, true);

	function disposeTooltip(event) {
		if (event.target.matches('button[data-bs-title]:not(.copy-url)')) {
			var tooltip = bootstrap.Tooltip.getInstance(event.target);
			if (tooltip) {
				tooltip.dispose();
			}
		}
	}

	// Multi modal
	document.querySelectorAll('.modal').forEach(function (modal) {
		modal.addEventListener('hidden.bs.modal', function () {
			if (document.querySelectorAll('.modal.show').length > 0) {
				document.body.classList.add('modal-open');
			}
		});
	});

	// Datetime
	document.body.addEventListener('feed-loaded', updateTime); // Update datetime when a feed is loaded

	setInterval(updateTime, 60000); // Update datetime every minute

	function updateTime() {
		document.querySelectorAll('[data-date]').forEach(function (element) {
			var dateFormat = element.getAttribute('data-date-format');
			var dateValue = element.getAttribute('data-date');
			if (dateFormat !== null) {
				element.textContent = luxon.DateTime.fromSeconds(parseInt(dateValue)).toLocaleString(dateFormat);
			} else {
				element.textContent = luxon.DateTime.fromSeconds(parseInt(dateValue)).toRelative();
			}
		});
	}

})();