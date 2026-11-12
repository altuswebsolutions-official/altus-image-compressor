=== Altus Image Compressor ===
Contributors: altuswebsolutions-official
Tags: image compressor, compress images, image optimizer, jpeg, webp
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Shrink JPEG, PNG and WebP images in the browser — quality + resize controls, before/after savings, instant download. No upload.

== Description ==

Drop in a JPEG, PNG or WebP image (up to 25 MB) and make it web-ready in seconds: pick an output format (same as original, JPEG, WebP or PNG) with honest advice for each choice, drag the quality slider, resize in one click, then download the smaller file.

Use the shortcode `[altus_image_compressor]` on any page.

The results are shown honestly: original size next to compressed size, the exact percentage saved, a plain-English verdict, and side-by-side before/after previews. If the "compressed" file somehow comes out bigger than the original — it happens with already-optimised images — the tool says so and suggests what to try instead. A PNG saved as JPEG triggers a transparency warning before you download.

Everything runs in the visitor's browser using the native Canvas API — the image is never uploaded to any server. No account, no watermark, no third-party libraries.

This open-source version contains NO lead capture, NO email sending, and NO data storage. Images are compressed in the browser and downloaded from the page; nothing is sent to or stored on any server.

== Installation ==

1. Upload the plugin zip via Plugins > Add New > Upload Plugin, or extract it into `/wp-content/plugins/`.
2. Activate the plugin.
3. Add `[altus_image_compressor]` to any page and publish it.

== Frequently Asked Questions ==

= Is it really free? =

Yes. Every compression, every format and every download is free, with no account and no email address required.

= Which formats can I compress? =

JPEG, PNG and WebP — the three formats websites actually use. Other image types (like GIF or BMP) are not supported; convert them to one of the three first.

= Will compressing hurt my image quality? =

Barely, if you do it right. Around 80% quality looks identical to the original on screens while being dramatically smaller — the tool defaults there. Drop lower and you will start to see softness; the before/after preview lets you check with your own eyes before downloading.

= Is there a size limit? =

You can compress images up to 25 MB — comfortably more than any sensible web image. If your file is bigger than that, resize it once in any photo app first, then bring it here.

= Are my images uploaded to your servers? =

No. The compression runs entirely inside your browser on your own device. We never see, store or process your images. In this open-source version there is no email option at all — the compressed file is downloaded straight from the page.

= What size should my website images be? =

Match the size they will actually display at. Blog images: 1024 px wide is plenty. Hero banners: 1600 px. Thumbnails: 640 px. Anything bigger is just weight your visitors download for nothing. The tool's resize presets are labelled with exactly these uses.

= What happens if the compressed file is bigger than the original? =

It happens with images that are already well optimised. The tool warns you honestly and suggests converting to WebP or lowering the quality instead of letting you download a bigger file by mistake.

== Changelog ==

= 1.0.0 =

First public release (stripped open-source version). Drag-and-drop compression for JPEG, PNG and WebP (up to 25 MB) with quality slider 10–100%, resize presets (640/1024/1600/2560 px), format choice with honest advice, before/after savings and verdict, side-by-side previews, transparency warning for PNG-to-JPEG, and smart-filename download. Email delivery, lead storage and email sending removed. The compression logic passed a 51-test Node harness in the original build (file validation, dimension-scaling math, savings %, format advice, transparency warnings, quality clamping, verdict labels).
