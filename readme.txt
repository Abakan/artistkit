=== ArtistKit ===
Contributors: hexagonwebfr, promotrackerplugins
Tags: epk, musician, music, press kit, artist
Requires at least: 5.8
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 2.0.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Free Electronic Press Kit (EPK) builder for musicians. Showcase your music and press materials directly on your WordPress site.

== Description ==

Create a professional EPK directly from your WordPress admin. No subscriptions, no external platforms — your data stays on your domain.

ArtistKit gives independent musicians, bands and labels everything they need to pitch bookers, journalists, festivals and curators in a professional way.

**Free version features:**

* Artist EPK page (bio, photo, stats, social links)
* Streaming platforms integration (Spotify, Apple Music, Deezer, YouTube Music, SoundCloud, Bandcamp)
* Embedded music player (Spotify, SoundCloud)
* MP3 player with optional download
* Press quotes section
* Press assets ZIP download (photos, rider)
* Custom accent color
* SEO-friendly markup
* Responsive mobile design
* Dedicated URL on your domain (`yoursite.com/epk`)

**Use cases:**

* Solo artists looking for a professional press kit page
* Bands needing to share materials with bookers and festivals
* Labels managing artist profiles
* Indie musicians wanting to look professional without paying monthly fees

**Premium Features (ArtistKit Pro):**

The free version covers essential EPK needs. For active musicians and labels, [ArtistKit Pro](https://promotracker.fr/artistkit) (sold separately) adds:

1. **EPK Release per song** — Dedicated EPK page for each single, EP or album
2. **Track Patchwork** — 5 audio players in a grid layout
3. **Extended embeds** — 380px Spotify/SoundCloud with visible tracklist
4. **Real-time Analytics** — Views per EPK, time-period breakdown, traffic sources
5. **PDF Export** — One-click polished PDF generation
6. **Password Protection** — Send confidential EPKs before public release
7. **Press Talking Points** — Formatted briefs for journalists
8. **Radio Info** — BPM, key, ISRC fields
9. **5 Templates** — Match your visual identity
10. **8 Font Pairs** — Curated typography combinations

ArtistKit Pro is an add-on plugin installed alongside ArtistKit. Visit [promotracker.fr/artistkit](https://promotracker.fr/artistkit) to learn more.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/artistkit`, or install through the WordPress plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to **ArtistKit → Artist EPK** and create your artist profile.
4. Visit `yoursite.com/epk` to view your published EPK.

== Frequently Asked Questions ==

= Does ArtistKit work with my WordPress theme? =

Yes. ArtistKit generates EPKs in a standalone template that doesn't depend on your theme. It works with any properly coded WordPress theme.

= Can I customize the colors? =

Yes. The free version supports a custom accent color. The Pro version offers 5 templates and 8 font pairs.

= Do I need any external service or account? =

ArtistKit works entirely on your WordPress site — no external accounts, no API keys, no monthly subscription. The only external request is to Google Fonts, used to load the EPK display font. See the "External services" section below for details.

= Where is my data stored? =

All your data stays in your WordPress database. Your EPK is hosted on your domain.

= Is there a Pro version? =

Yes. ArtistKit Pro is a separate add-on plugin with advanced features for active musicians. Available at [promotracker.fr/artistkit](https://promotracker.fr/artistkit).

= How do I uninstall the plugin? =

Deactivate from the WordPress Plugins screen, then click "Delete". All artist EPK data will be removed.

== External services ==

This plugin loads web fonts from Google Fonts to render the typography on your public EPK page.

When a visitor opens an EPK page, the plugin enqueues a stylesheet from `fonts.googleapis.com` and the browser then downloads the font files from `fonts.gstatic.com`. As part of these requests, the visitor's browser sends information such as their IP address and user-agent to Google. The request is made on every public EPK page view. No EPK content or personal data managed by the plugin is sent to Google.

This service is provided by Google. Please review Google's terms and privacy policy:

* Terms of Service: https://policies.google.com/terms
* Privacy Policy: https://policies.google.com/privacy

== Screenshots ==

1. The Artist EPK page on the frontend
2. The plugin admin dashboard
3. Editing an artist profile
4. Mobile view of an EPK page

== Changelog ==

= 2.0.6 =
* Compliance: address manual review feedback on generic naming.
  - Rename classes AK_Admin/AK_Frontend/AK_Post_Types to ArtistKit_Admin/ArtistKit_Frontend/ArtistKit_Post_Types.
  - Rename the custom post type `ak_artist_epk` to `artistkit_epk`.
  - Rename the localized JS object `AK` to `ArtistKitData`, the `ak-admin` asset handles to `artistkit-admin`, and the settings/meta nonces and actions to the `artistkit_` prefix.

= 2.0.5 =
* Plugin Check: resolve the two remaining functional warnings.
  - Unslash `$_POST['ak_press_quotes']` up-front; each sub-field (quote/source/url) is sanitized individually in the loop, with a documented `phpcs:ignore` explaining the deep sanitization.
  - Add a resource version to the Google Fonts `wp_enqueue_style()` call for proper cache-busting.

= 2.0.4 =
* Compliance: address WordPress.org plugin review feedback.
  - Sanitize `$_POST` (unslash + `map_deep` / `sanitize_text_field`) before exposing it to the `artistkit_save_settings` filter.
  - Prefix global declarations: constants `AK_*` → `ARTISTKIT_*`, global functions `ak_*` → `artistkit_*`, and stored options `ak_*` → `artistkit_*`.
  - Remove the public "Powered by ArtistKit" credit from the EPK footer (no longer displayed on visitor-facing pages).
  - Document the use of Google Fonts as an external service in the readme (with terms and privacy links).

= 2.0.3 =
* Fix: Artist cover artwork now displays full-width on mobile devices (≤ 768px) for better visual impact.

= 2.0.2 =
* Compliance: pass WordPress.org Plugin Check audit.
  - Move inline `<style>` and `<script>` tags to `wp_enqueue_style/script` + `wp_add_inline_style`; the EPK template now uses `wp_head()` / `wp_footer()`.
  - Escape all output (`esc_html`, `esc_url`, `esc_attr`) including contact emails (replace `antispambot()` wrap with `esc_html` of an `antispambot` output).
  - Prefix template-level local variables with `ak_` to satisfy Plugin Check `PrefixAllGlobals`.
  - Remove obsolete `load_plugin_textdomain()` call (WP 4.6+ auto-loads).
  - Use `wp_safe_redirect()` instead of `wp_redirect()`.
  - Annotate known false-positive nonce checks and the uninstall-only direct DB query with `phpcs:ignore`.
* Tested up to WordPress 7.0.

= 2.0.1 =
* Fix: `/epk` URL returned 404 immediately after a fresh activation. The rewrite rules flush now happens once on the next `init` hook (after the CPT and custom rewrite rules are registered) instead of during the activation callback. Users no longer need to manually save permalinks after activation.

= 2.0.0 =
* Major refactor: Pro features moved to a separate add-on plugin (ArtistKit Pro)
* Cleaner free plugin focused on essential EPK creation
* New extensibility hooks for the Pro add-on
* Strings anglicised — translation now via .po catalog
* New "Upgrade to Pro" admin page
* WordPress.org compliant readme

= 1.3.7 =
* Fix: font rendering issue in EPK templates
* Improved: responsive layout on mobile devices

= 1.3.6 =
* Added: video embed section
* Improved: streaming link detection

= 1.3.5 =
* Initial public release

== Upgrade Notice ==

= 2.0.3 =
Visual improvement: full-width cover on mobile.

= 2.0.2 =
WordPress.org compliance pass — proper asset enqueueing, full output escaping. Recommended for all installs.

= 2.0.1 =
Fixes a permalink 404 after fresh activation. Recommended for all 2.0.0 installs.

= 2.0.0 =
Major version with architectural changes. Pro features (Release EPKs, Analytics, PDF Export, etc.) are now in a separate ArtistKit Pro add-on plugin available at promotracker.fr/artistkit. Existing Pro users: download the new ArtistKit Pro plugin from your account.
