/* LUMIÈRE 設定ページ: ヒーロー背景画像のメディア選択。 */
(function ($) {
	"use strict";
	$(function () {
		var frame;
		var $input = $("#ls_hero_image");
		var $preview = $("#lumiere-hero-image-preview");

		$("#lumiere-hero-image-select").on("click", function (e) {
			e.preventDefault();
			if (frame) {
				frame.open();
				return;
			}
			frame = wp.media({
				title: "ヒーロー背景画像を選択",
				button: { text: "この画像を使う" },
				library: { type: "image" },
				multiple: false
			});
			frame.on("select", function () {
				var att = frame.state().get("selection").first().toJSON();
				$input.val(att.url);
				$preview.attr("src", att.url).show();
			});
			frame.open();
		});

		$("#lumiere-hero-image-remove").on("click", function (e) {
			e.preventDefault();
			$input.val("");
			$preview.attr("src", "").hide();
		});
	});
})(jQuery);
