<?php
/**
 * Plugin Name: Altus Image Compressor
 * Description: Free tool. Shrink JPEG, PNG and WebP images right in the browser — quality + resize controls, before/after savings, download. No uploads, 100% client-side.
 * Version: 1.0.0
 * Author: Altus Web Solutions
 * Text Domain: altus-image-compressor
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * No third-party libraries: compression uses the browser's native Canvas API.
 * Stripped open-source copy: no email, no lead storage, no database table — the
 * compressed image is downloaded straight from the browser.
 */

if (!defined('ABSPATH')) exit;

define('AIC_VERSION', '1.0.0');
define('AIC_PATH', plugin_dir_path(__FILE__));
define('AIC_URL', plugin_dir_url(__FILE__));

/* ------------------------------------------------------------------ */
/* Frontend assets (only loaded when the shortcode is present)        */
/* ------------------------------------------------------------------ */
add_action('wp_enqueue_scripts', 'aic_enqueue');
function aic_enqueue() {
    if (is_admin()) return;
    global $post;
    if (!($post instanceof WP_Post) || !has_shortcode($post->post_content, 'altus_image_compressor')) return;

    wp_enqueue_style('aic-gen', AIC_URL . 'assets/generator.css', array(), AIC_VERSION);
    wp_enqueue_script('aic-logic', AIC_URL . 'assets/compressor-logic.js', array(), AIC_VERSION, true);
    wp_enqueue_script('aic-gen', AIC_URL . 'assets/generator.js', array('aic-logic'), AIC_VERSION, true);
}

/* ------------------------------------------------------------------ */
/* Shortcode: renders the compressor UI                               */
/* ------------------------------------------------------------------ */
add_shortcode('altus_image_compressor', 'aic_shortcode');
function aic_shortcode() {
    ob_start();
    ?>
    <div id="aic-root" class="aic-root">
        <div class="aic-card">
            <div class="aic-badge">Free tool</div>
            <h2 class="aic-title">Shrink your image</h2>
            <p class="aic-sub">Drop in a JPEG, PNG or WebP and make it web-ready in seconds — pick a format, drag the quality slider, optionally resize. Your image never leaves your browser.</p>

            <div id="aic-drop" class="aic-drop" role="button" tabindex="0" aria-label="Choose an image to compress">
                <span class="aic-drop-icon" aria-hidden="true">🖼️</span>
                <p><strong>Drag &amp; drop your image here</strong><br>or</p>
                <button type="button" class="aic-btn aic-btn-secondary" id="aic-choose">Choose a file</button>
                <input type="file" id="aic-file" accept="image/jpeg,image/png,image/webp" hidden>
                <p class="aic-hint" style="margin-top:10px">JPEG, PNG or WebP · up to 25 MB</p>
            </div>

            <p class="aic-error" id="aic-error" role="alert" hidden></p>

            <div id="aic-controls" hidden>
                <div class="aic-file-summary">
                    <img id="aic-orig-thumb" alt="Original image preview">
                    <div>
                        <p class="aic-file-name" id="aic-orig-name"></p>
                        <p class="aic-file-meta" id="aic-orig-meta"></p>
                    </div>
                </div>

                <div class="aic-field-row">
                    <div class="aic-field">
                        <label class="aic-label" for="aic-format">Output format</label>
                        <select id="aic-format" class="aic-input aic-select">
                            <option value="original" selected>Same as original</option>
                            <option value="image/jpeg">JPEG — smallest for photos</option>
                            <option value="image/webp">WebP — modern, sharp + small</option>
                            <option value="image/png">PNG — lossless (stays bigger)</option>
                        </select>
                        <p class="aic-hint" id="aic-format-advice" hidden></p>
                    </div>
                    <div class="aic-field">
                        <label class="aic-label" for="aic-maxwidth">Resize (max width)</label>
                        <select id="aic-maxwidth" class="aic-input aic-select">
                            <option value="0" selected>Keep original size</option>
                            <option value="640">640 px — thumbnails</option>
                            <option value="1024">1024 px — blog images</option>
                            <option value="1600">1600 px — hero banners</option>
                            <option value="2560">2560 px — large screens</option>
                        </select>
                    </div>
                </div>

                <div class="aic-field" id="aic-quality-wrap">
                    <label class="aic-label" for="aic-quality">Quality</label>
                    <div class="aic-range-row">
                        <input type="range" id="aic-quality" min="10" max="100" value="80" step="5">
                        <span class="aic-range-val" id="aic-quality-val">80%</span>
                    </div>
                    <p class="aic-hint">Around 80% looks identical to the original on screen but is dramatically smaller. Drop to 60–70% for thumbnails.</p>
                </div>

                <p class="aic-warn" id="aic-trans-warn" role="alert" hidden>⚠️ JPEG cannot keep transparency — any see-through areas of your PNG will become white.</p>

                <button type="button" class="aic-btn" id="aic-compress">Compress image</button>
            </div>

            <div id="aic-results" hidden>
                <h3>Your results</h3>
                <div class="aic-result-grid">
                    <div class="aic-stat">
                        <div class="aic-stat-label">Original</div>
                        <div class="aic-stat-num" id="aic-orig-size">–</div>
                    </div>
                    <div class="aic-stat">
                        <div class="aic-stat-label">Compressed</div>
                        <div class="aic-stat-num" id="aic-out-size">–</div>
                    </div>
                    <div class="aic-stat">
                        <div class="aic-stat-label">Smaller by</div>
                        <div class="aic-stat-num" id="aic-savings">–</div>
                    </div>
                </div>
                <p class="aic-verdict" id="aic-verdict"></p>
                <div class="aic-compare">
                    <figure>
                        <img id="aic-orig-thumb2" alt="Original image">
                        <figcaption id="aic-dims">–</figcaption>
                    </figure>
                    <figure>
                        <img id="aic-out-thumb" alt="Compressed image preview">
                        <figcaption id="aic-out-dims">–</figcaption>
                    </figure>
                </div>
                <p class="aic-warn" id="aic-big-warn" role="alert" hidden>⚠️ The "compressed" file is actually bigger than the original — this happens with already-optimised images. Try converting to WebP or lowering the quality.</p>
                <div class="aic-btn-row">
                    <a class="aic-btn" id="aic-download" href="#" download>Download compressed image</a>
                </div>

            </div>

            <p class="aic-fineprint">Everything above runs entirely in your browser — your images never leave your device.</p>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
