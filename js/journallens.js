/* JournalLens badge: hide the badge (and its sidebar block) if the image cannot be loaded. */
(function () {
	function hide(img) {
		var box = img.closest('.block_journallens') || img.closest('.journallens-badge');
		if (box) box.style.display = 'none';
	}
	function init() {
		var imgs = document.querySelectorAll('img.journallens-badge__img');
		for (var i = 0; i < imgs.length; i++) {
			var img = imgs[i];
			if (img.complete && img.naturalWidth === 0) hide(img);
			else img.addEventListener('error', hide.bind(null, img));
		}
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
	else init();
})();
