# REFACTOR INVENTORY — ArtistKit v1.3.7 → v2.0.0 Free + Pro split

**Date** : 2026-05-25
**Branch** : `refactor-wp-org-split`
**Tag base** : `v1.3.7-pre-refactor` (pushed)
**Objectif** : préparer Free (WP.org) + Pro add-on (commercialisé via PromoTracker).

---

## 1. Périmètre du repo source (`github-artistkit/`)

```
640K total (332K .git)
23 PHP files · 7 CSS files · 2 JS files · LICENSE · readme.txt · README.md
```

**Structure** :
```
./artistkit.php                            (entry point)
./includes/                                 (5 classes)
  ├── class-post-types.php                 — CPT register
  ├── class-license.php                    — licence API (Pro)
  ├── class-admin.php                      — admin UI
  ├── class-frontend.php                   — frontend rendering
  └── class-analytics.php                  — analytics (Pro)
./admin/views/                              (15 views)
  ├── dashboard.php                        — mixte
  ├── settings.php                         — mixte (Design Free + Licence Pro)
  ├── analytics.php                        — Pro
  ├── pro-gate.php                         — gating UI (à supprimer)
  ├── meta-artist-identity.php             — Free
  ├── meta-artist-links.php                — Free
  ├── meta-artist-press.php                — Free
  ├── meta-artist-assets.php               — Free
  ├── meta-audio.php                       — mixte (Free MP3 + Pro patchwork)
  ├── meta-epk-link.php                    — Free (générique)
  ├── meta-release-main.php                — Pro
  ├── meta-release-story.php               — Pro
  ├── meta-release-links.php               — Pro
  ├── meta-release-radio.php               — Pro
  └── meta-release-assets.php              — Pro
./templates/                                (3 templates frontend)
  ├── epk-artist.php                       — mixte (Free + 4 blocs `if ak_is_pro()`)
  ├── epk-release.php                      — Pro
  └── password-form.php                    — Pro (protection mdp)
./assets/css/                               (7 stylesheets)
  ├── admin.css                            — Free
  ├── frontend.css                         — Free
  ├── theme-dark-minimal.css               — Free (template par défaut)
  ├── theme-light-clean.css                — **AMBIGU** — voir §4 discordance
  ├── theme-bold-contrast.css              — Pro
  ├── theme-warm-organic.css               — Pro
  └── theme-neon-dark.css                  — Pro
./assets/js/                                (2 scripts)
  ├── admin.js                             — à auditer (mixte probable)
  └── frontend.js                          — à auditer (analytics tracking = Pro)
./languages/                                (vide dans le repo git — sera regénéré)
./LICENSE, ./readme.txt, ./README.md       — Free
```

---

## 2. Carte des références `ak_is_pro()` et symboles Pro (à modifier ou retirer)

Toutes les références trouvées via `grep -rn "ak_is_pro|AK_License|AK_Analytics|license_valid|AK_LICENSE_API|ak_release_epk"` :

### 2.1 `artistkit.php` (entry point — à réécrire intégralement)
| Ligne | Code | Sort |
|------|------|------|
| 22 | `define( 'AK_LICENSE_API', ... )` | **Retirer** (passe dans Pro `AKP_LICENSE_API`) |
| 26 | `require_once class-license.php` | **Retirer** |
| 29 | `require_once class-analytics.php` | **Retirer** |
| 38 | `AK_Analytics::create_table()` | **Retirer** |
| 46-47 | `'license_key' => '', 'license_valid' => false` | **Retirer** des defaults `ak_settings` |
| 67 | `AK_License::init()` | **Retirer** |
| 72-75 | `function ak_is_pro()` | **Retirer** (déplacé dans Pro comme `akp_is_active()`) |

**Nouveaux ajouts** :
- `do_action( 'artistkit_init' )` à la fin de `ak_init()` (hook d'extension principal)
- `do_action( 'artistkit_register_post_types' )` dans `ak_init` après `AK_Post_Types::init()`

### 2.2 `includes/class-post-types.php`
| Ligne | Code | Sort |
|------|------|------|
| 34-51 | `register_post_type( 'ak_release_epk', ... )` | **Migrer vers Pro** |
| 58 | `add_rewrite_rule( '^epk/([^/]+)/?$', 'index.php?ak_page=release&ak_slug=...' )` | **Migrer vers Pro** (le slash + slug est Pro) |

Le rewrite `/epk` racine reste Free. Le rewrite `/epk/{slug}` part en Pro avec son CPT.

### 2.3 `includes/class-frontend.php`
| Ligne | Code | Sort |
|------|------|------|
| 31 | `AK_Analytics::log_event(...)` (AJAX handler) | **Migrer vers Pro** (handler ajax_log_event entier) |
| 63 | `AK_Analytics::log_view( $epk->ID, 'artist' )` | **Retirer du Free** ; Pro re-tracke via hook |
| 70-108 | `render_release_epk()` méthode entière | **Migrer vers Pro** |
| 105 | `AK_Analytics::log_view( ..., 'release' )` | **Migrer vers Pro** |
| 270-279 | `get_published_releases()` (helper) | **Migrer vers Pro** |
| 296-313 | `check_password()` | **Migrer vers Pro** |
| 315-320 | `render_password_form()` | **Migrer vers Pro** |
| 228-266 | `get_release_data()` entière | **Migrer vers Pro** |

**Hooks à ajouter** (le Pro s'y accroche) :
- Dans `render_artist_epk()` : `do_action( 'artistkit_before_render', $epk, 'artist' )` (Pro track la vue)
- Dans le payload `get_artist_data()` : `apply_filters( 'artistkit_artist_data', $data, $post )` (Pro y ajoute `featured_tracks` et `releases`)

### 2.4 `includes/class-admin.php`
| Ligne | Code | Sort |
|------|------|------|
| 20-22 | row action "Voir la sortie" sur `ak_release_epk` | **Migrer vers Pro** |
| 47-53 | submenu "Releases" (`edit.php?post_type=ak_release_epk`) | **Migrer vers Pro** |
| 55-66 | submenu "Analytics" entier (avec badge PRO) | **Migrer vers Pro** |
| 90-91 | enqueue check inclut `ak_release_epk` et `artistkit-analytics` | **Conserver le Free pour `ak_artist_epk` seulement** ; Pro étend via hook |
| 101 | `'isPro' => ak_is_pro()` dans wp_localize_script | **Retirer** (le Pro fait son propre localize) |
| 121-123 | `page_analytics()` méthode | **Migrer vers Pro** |
| 164-169 | 6 add_meta_box pour `ak_release_epk` | **Migrer vers Pro** |
| 233-237 | `mb_release_main` (avec pro-gate fallback) | **Migrer vers Pro** |
| 249-255 | `mb_release_story` | **Migrer vers Pro** |
| 259-267 | `mb_release_links` | **Migrer vers Pro** |
| 271-277 | `mb_release_radio` | **Migrer vers Pro** |
| 281-287 | `mb_release_assets` | **Migrer vers Pro** |
| 291-295 | `mb_epk_link_release` | **Migrer vers Pro** |
| 357-379 | bloc `save_meta_boxes` pour `ak_release_epk` | **Migrer vers Pro** |
| 413-415 | `get_releases()` helper | **Migrer vers Pro** |
| 423-431 | admin notices `license_status` | **Migrer vers Pro** |

**Hooks à ajouter** :
- `do_action( 'artistkit_admin_menu', 'artistkit' )` après les submenus Free (parent slug en arg)
- `do_action( 'artistkit_admin_enqueue', $hook )` après `wp_localize_script`
- `do_action( 'artistkit_register_meta_boxes' )` après les meta-boxes Artist
- `do_action( 'artistkit_save_post', $post_id, $post_type )` à la fin de `save_meta_boxes`

### 2.5 `includes/class-analytics.php`
**Entier → migrer vers Pro** (`AKP_Analytics`).
- Lignes 45, 53 : checks `ak_is_pro()` deviennent inutiles (le plugin Pro = signal de présence)

### 2.6 `includes/class-license.php`
**Entier → migrer vers Pro** (`AKP_License`).
- Référence à `AK_LICENSE_API` (ligne 20) → renommer en `AKP_LICENSE_API`
- L'option `ak_settings['license_valid']` devient `akp_settings['license_valid']`

⚠️ **POINT SÉCURITÉ CRITIQUE (validé par David)** : à la fin de Phase 3, vérifier explicitement qu'**aucun bypass type `return true`** n'apparaît dans la version Pro de `class-license.php`. Le bypass dev qui était présent dans le working local NE DOIT PAS se retrouver en prod.

### 2.7 `templates/epk-artist.php` (mixte → 4 blocs Pro à externaliser)
| Ligne | Bloc Pro à externaliser |
|------|------------------------|
| 58-68 | Bouton "Télécharger PDF" (nav) — déplacer via hook `artistkit_nav_actions` |
| 188 | `$h = ( $embed_height === '380' && ak_is_pro() ) ? '380' : '152';` — Free force `152`, Pro override via filtre `artistkit_embed_height` |
| 227-252 | Bloc patchwork tracks — déplacer via hook `artistkit_after_audio_player` |
| 307-339 | Bloc Releases grid — déplacer via hook `artistkit_after_press_quotes` ou `artistkit_after_main_sections` |

### 2.8 `admin/views/dashboard.php`
| Ligne | Sort |
|------|------|
| 39-83 (card "EPK Releases") | **Retirer entièrement du Free** — déplacer dans Pro via hook `artistkit_after_dashboard_cards` |
| 91 (lien "Passer en Pro") | **Conserver** mais simplifier (toujours affiché, sans `ak_is_pro()`) |

### 2.9 `admin/views/settings.php`
| Ligne | Sort |
|------|------|
| 4 | `$is_pro = ak_is_pro();` | **Retirer** |
| 14-20 | notices `license_status` | **Retirer** (Pro re-injecte ses notices) |
| 54-60 | Liste templates : `light-clean`, `bold-contrast`, `warm-organic`, `neon-dark` | **Retirer du Free** — Free n'expose que `dark-minimal` (voir §4) |
| 96-108 | Liste fonts : 7 fonts Pro | **Retirer du Free** — Free n'expose que `inter` |
| 129-216 | Card "Licence Pro" entière | **Retirer** — Pro injecte sa card via hook `artistkit_settings_after` |
| 220-260 | CSS inline `.ak-settings-grid 1fr 1fr` | **Adapter** en 1 colonne (plus de card License) |

**Hooks à ajouter** :
- `do_action( 'artistkit_settings_templates', $current_template )` — Pro injecte ses templates supplémentaires
- `do_action( 'artistkit_settings_fonts', $current_font )` — Pro injecte ses fonts supplémentaires
- `do_action( 'artistkit_settings_after', $settings )` — Pro injecte sa card License

### 2.10 `admin/views/analytics.php`
**Entier → migrer vers Pro** (la version "teaser flouté" disparaît, Pro affiche la vraie analytics)

### 2.11 `admin/views/pro-gate.php`
**Supprimer du Free** — devient inutile (plus de gating intra-Free)

### 2.12 `admin/views/meta-audio.php` (mixte — à splitter)
| Lignes | Bloc | Sort |
|--------|------|------|
| 6-25 | Embed type + URL (152px) | **Conserver Free** |
| 27-66 | Embed height selector (380px lock + mockup Pro) | **Retirer du Free** — Free force 152 sans choix |
| 70-97 | Player MP3 (URL + label + downloadable) | **Conserver Free** |
| 99-228 | Section patchwork (Pro + mockup) | **Retirer du Free** — Pro injecte via hook `artistkit_audio_meta_after` |

### 2.13 `admin/views/meta-release-*.php`
Les 5 fichiers `meta-release-{main,story,links,radio,assets}.php` → **migrer vers Pro**.

---

## 3. Plan de migration fichier-par-fichier

### 3.1 Fichiers à CONSERVER dans Free (avec modifications)
| Fichier | Action |
|---------|--------|
| `artistkit.php` | Réécrire intégralement (cf. §2.1) |
| `includes/class-post-types.php` | Supprimer CPT release + rewrite `/epk/{slug}` |
| `includes/class-admin.php` | Stripper tout ce qui touche release + analytics + license, ajouter hooks |
| `includes/class-frontend.php` | Garder `render_artist_epk` + `get_artist_data` + helpers ; supprimer release/password/ajax_log_event ; ajouter hooks |
| `templates/epk-artist.php` | Stripper 4 blocs Pro, remplacer par `do_action()` |
| `admin/views/dashboard.php` | Stripper card Releases + hook après cards |
| `admin/views/settings.php` | Retirer card Licence + selectors templates/fonts Pro |
| `admin/views/meta-artist-identity.php` | OK tel quel |
| `admin/views/meta-artist-links.php` | OK tel quel |
| `admin/views/meta-artist-press.php` | OK tel quel |
| `admin/views/meta-artist-assets.php` | OK tel quel |
| `admin/views/meta-audio.php` | Stripper bloc 380px lock + section patchwork |
| `admin/views/meta-epk-link.php` | OK tel quel (générique pour artist+release) |
| `assets/css/admin.css` | Auditer et nettoyer si CSS spécifique Pro |
| `assets/css/frontend.css` | Auditer (style patchwork = Pro) |
| `assets/css/theme-dark-minimal.css` | Conserver |
| `assets/js/admin.js` | Auditer (logique patchwork = Pro) |
| `assets/js/frontend.js` | Auditer (analytics tracking AJAX = Pro) |
| `readme.txt` | Réécrire intégralement (norme WP.org + EN strings) |
| `LICENSE`, `README.md` | OK tel quel |

### 3.2 Fichiers à SUPPRIMER du Free (et migrer vers Pro)
```
includes/class-license.php             → artistkit-pro/includes/class-license.php
includes/class-analytics.php           → artistkit-pro/includes/class-analytics.php
templates/epk-release.php              → artistkit-pro/templates/epk-release.php
templates/password-form.php            → artistkit-pro/templates/password-form.php
admin/views/analytics.php              → artistkit-pro/admin/views/analytics.php (refonte sans teaser)
admin/views/meta-release-main.php      → artistkit-pro/admin/views/meta-release-main.php
admin/views/meta-release-story.php     → artistkit-pro/admin/views/meta-release-story.php
admin/views/meta-release-links.php     → artistkit-pro/admin/views/meta-release-links.php
admin/views/meta-release-radio.php     → artistkit-pro/admin/views/meta-release-radio.php
admin/views/meta-release-assets.php    → artistkit-pro/admin/views/meta-release-assets.php
admin/views/pro-gate.php               → SUPPRIMER (plus utilisé)
assets/css/theme-bold-contrast.css     → artistkit-pro/assets/css/
assets/css/theme-warm-organic.css      → artistkit-pro/assets/css/
assets/css/theme-neon-dark.css         → artistkit-pro/assets/css/
```

### 3.3 Fichiers à CRÉER dans Free
| Fichier | Rôle |
|---------|------|
| `admin/views/upgrade.php` | Page admin "Upgrade to Pro" (cf. brief §2.8) |
| `uninstall.php` | Cleanup propre à la désinstallation |
| `languages/artistkit.pot` | Catalogue traduction regénéré (Phase 2.12) |

### 3.4 Fichiers à CRÉER dans Pro (artistkit-pro/)
| Fichier | Rôle |
|---------|------|
| `artistkit-pro.php` | Entry point + dependency check sur Free v2.0.0+ |
| `includes/class-post-types.php` | CPT `ak_release_epk` |
| `includes/class-admin.php` | Hook into Free admin menu, meta-boxes release, page analytics |
| `includes/class-frontend.php` | Hook into Free templates, render release EPK, password protection, tracking |
| `includes/class-license.php` | (migré + adapté préfixe AKP_) |
| `includes/class-analytics.php` | (migré + adapté préfixe AKP_) |
| `includes/class-pdf-export.php` | À créer (n'existe pas dans v1.3.7, mentionné dans le brief) |
| `templates/patchwork-section.php` | Extrait du bloc patchwork de `epk-artist.php` |
| `templates/releases-grid-section.php` | Extrait du bloc Releases de `epk-artist.php` |
| `templates/epk-release.php` | (migré) |
| `templates/password-form.php` | (migré) |
| `admin/views/upgrade-card.php` | Card "Licence Pro" injectée dans Settings Free |
| `admin/views/analytics.php` | Refonte sans teaser flouté |
| `admin/views/meta-release-*.php` | 5 vues (migrées) |
| `assets/css/theme-light-clean.css` | (déplacé depuis Free — cf. §4) |
| `assets/css/theme-bold-contrast.css` | (déplacé) |
| `assets/css/theme-warm-organic.css` | (déplacé) |
| `assets/css/theme-neon-dark.css` | (déplacé) |
| `languages/artistkit-pro.pot` | Catalogue traduction Pro |
| `readme.txt` | (interne, pas pour WP.org) |
| `LICENSE` | Proprietary |

---

## 4. Discordances détectées entre brief et code actuel

### 4.1 Template `light-clean` : Free ou Pro ?
- **Brief** §"Répartition Free vs Pro" : Free a "1 template par défaut (`dark-minimal`)" — donc **light-clean = Pro**
- **Code actuel** (`settings.php:63`) : `light-clean` n'est **PAS** locked (uniquement bold/warm/neon le sont)

**Décision proposée** : suivre le brief → `light-clean` migre vers Pro avec les 3 autres. Free n'expose que `dark-minimal`. **Validation David requise.**

### 4.2 Fonts Free vs Pro
- **Brief** : Pro = "8 paires de polices"
- **Code actuel** (`settings.php:108-109`) : 1 Free (`inter`) + 7 Pro (poppins, syne, dmserif, space, playfair, bebas, outfit)

**Soit le brief sous-compte (1 Free + 8 Pro = 9 total)**, soit `poppins` est aussi Free. Décision proposée : 1 Free (`inter`) + 7 Pro. **Validation David.**

### 4.3 Export PDF
- **Brief** : Pro inclut "Export PDF mis en page"
- **Code actuel** : il y a un bouton "Télécharger PDF" dans `epk-artist.php:59-62` qui appelle `akPrintPDF(this)`, mais **aucune classe `AK_PDF` ni `class-pdf-export.php`** n'existe dans le repo

→ La fonctionnalité PDF actuelle est probablement basée sur `window.print()` côté JS. À **créer de zéro** dans Pro (ou conserver un wrapper print propre). **Question à David : version 1.0.0 du Pro doit-elle inclure un vrai export PDF serveur ou on garde le `window.print()` comme baseline ?**

### 4.4 Limite de sites
- **Brief Stratégie WP.org** : "no site limit" (Free) ; mais le brief mentionne aussi en gap diagnostic "no site limit" comme manque côté API
- → **Hors scope refactoring** pour cette session. Note pour plus tard.

### 4.5 État du diagnostic
- `ARTISTKIT-DIAGNOSTIC.md` existe déjà à `/Users/davidabakan/Desktop/promoTracker-saas/` (12 KB) avec un diagnostic backend complet (webhook Stripe, validation API, DB schema, etc.)
- Cet inventaire-ci complète le diagnostic côté **plugin WordPress** (côté serveur déjà couvert)

---

## 5. Backup ZIP de référence

Backup en local **non encore créé** — sera fait en fin de Phase 1.3 :
```
/tmp/artistkit-v1.3.7-backup/artistkit-v1.3.7-backup.zip
```

Le tag `v1.3.7-pre-refactor` est déjà poussé sur GitHub et fait office de backup primaire.

---

## 6. Hooks d'extension à implémenter dans le Free (résumé)

Pattern Elementor/WooCommerce — le Pro s'accroche à ces points sans modifier le Free :

### Actions
| Hook | Quand | Usage Pro |
|------|-------|-----------|
| `artistkit_init` | Fin de `ak_init()` | Boot Pro |
| `artistkit_register_post_types` | Après registre Free CPT | Register CPT release |
| `artistkit_admin_menu` | Après submenus Free, arg = parent slug | Submenu Releases + Analytics |
| `artistkit_admin_enqueue` | Après assets Free, arg = `$hook` | Enqueue assets Pro |
| `artistkit_register_meta_boxes` | Après meta-boxes Artist | Meta-boxes Release |
| `artistkit_save_post` | Fin de `save_meta_boxes`, args = `$post_id, $post_type` | Save champs Release |
| `artistkit_settings_after` | Fin form Settings | Card License Pro |
| `artistkit_settings_templates` | Dans grid templates, arg = current | Injecter templates Pro |
| `artistkit_settings_fonts` | Dans select fonts, arg = current | Injecter fonts Pro |
| `artistkit_before_render` | Début `render_artist_epk`, args = `$epk, 'artist'` | Tracking analytics |
| `artistkit_nav_actions` | Dans nav frontend | Bouton PDF |
| `artistkit_after_audio_player` | Après section "Écouter", arg = `$post` | Patchwork tracks |
| `artistkit_after_press_quotes` | Après section citations, arg = `$post` | Releases grid |
| `artistkit_after_dashboard_cards` | Après cards dashboard | Card Releases |
| `artistkit_audio_meta_after` | Fin meta-audio, arg = `$post` | Section patchwork meta |
| `artistkit_ajax_log_event` | (alternative) AJAX endpoint dans Pro directement | Tracking events |

### Filters
| Hook | Sur | Usage Pro |
|------|-----|-----------|
| `artistkit_artist_data` | Payload de `get_artist_data()` | Pro ajoute `featured_tracks`, `releases` |
| `artistkit_embed_height` | Hauteur iframe streaming | Pro override 152 → 380 si licence |
| `artistkit_settings_defaults` | `ak_settings` defaults | Pro peut étendre |

---

## 7. Risques et points d'attention

1. **Données existantes** : si un user a déjà rempli des champs Release sur la v1.3.7 (avec bypass `return true`), ces données sont en DB sous `ak_release_epk`. Après refactor : tant que Pro pas installé, ces posts deviennent inaccessibles (CPT plus enregistré). **Le brief précise "0 licence en DB"** mais il pourrait y avoir des données locales chez David. À vérifier avant prod.
2. **Backwards compatibility** : URL `/epk/{slug}` casse si Pro absent. Free affiche soit 404, soit redirige vers `/epk` racine. À décider.
3. **Mass move des fichiers** : `git mv` perd le diff hash sur GitHub si les fichiers sont modifiés en parallèle du déplacement. On va `cp` les fichiers vers `artistkit-pro/` (nouveau repo) puis `rm` du Free (commit séparé pour clarté).
4. **Strings i18n** : le code v1.3.7 a des strings sources en FR. La refonte Phase 2.12 doit anglicisation systématique (norme WP.org). Travail manuel important sur ~300 chaînes.
5. **AJAX nonce** : `ak_log_event` ajax est utilisé côté frontend pour tracker (download_mp3, etc.). Si on déplace tout vers Pro, le Free ne devrait plus exposer ce nonce ni le handler. Vérifier que `frontend.js` ne reste pas avec des fetch orphelins.

---

## 8. Estimation Phase 2

Sur la base de cet inventaire :
- Réécriture `artistkit.php` : 30 min
- Refonte `class-admin.php` (le plus gros) : 1h30
- Refonte `class-frontend.php` : 45 min
- Refonte `class-post-types.php` : 15 min
- Refonte `templates/epk-artist.php` : 1h
- Refonte `dashboard.php` + `settings.php` : 45 min
- Refonte `meta-audio.php` (split) : 30 min
- Nouveau `admin/views/upgrade.php` : 30 min
- Nouveau `uninstall.php` : 15 min
- Audit/clean `admin.js`, `frontend.js`, CSS : 30 min
- Anglicisation strings + `.pot` regen : 1h
- Nouveau `readme.txt` (norme WP.org) : 30 min
- Tests installation + commit : 30 min

**Total Phase 2 estimé : 8h** (au-dessus du 3-4h du brief — la masse de strings i18n + audit JS/CSS est sous-estimée dans le brief).

---

## 9. Questions ouvertes avant Phase 2

1. **Template `light-clean`** : Free ou Pro ? (§4.1)
2. **Fonts** : 1 Free vs 2 Free ? (§4.2)
3. **Export PDF** : `window.print()` ou vraie génération serveur dans Pro v1.0.0 ? (§4.3)
4. **URL `/epk/{slug}` quand Pro absent** : 404 ou redirect `/epk` ?
5. **Anglicisation strings** : on convertit toutes les strings FR → EN dans le code source (norme WP.org), ou on garde un dual catalog ?
6. **Estimation 8h pour Phase 2** vs 3-4h prévu : OK pour étendre le scope temps ?
