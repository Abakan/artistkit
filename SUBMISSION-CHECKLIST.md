# SUBMISSION CHECKLIST — ArtistKit v2.0.0 → WordPress.org

**Plugin slug** : `artistkit`
**Version** : `2.0.0`
**Submission date** : _TBD by David_
**Contributor** : `hexagonwebfr` (existing WP.org account)

---

## ✅ Pré-soumission (état actuel — vérifié 2026-05-25)

### Code & conformité
- [x] `readme.txt` aux normes WordPress.org (Stable tag, Requires at least, Tested up to, License, sections requises)
- [x] License GPLv2-or-later déclarée dans `artistkit.php` header + `readme.txt` + `LICENSE` file
- [x] `uninstall.php` propre testé (supprime `ak_settings` + posts `ak_artist_epk` + orphan postmeta)
- [x] Aucun code Pro physiquement présent dans le ZIP (vérifié : 0 `ak_is_pro`, 0 `AK_License`, 0 `AK_Analytics`, 0 `AK_LICENSE_API`)
- [x] Aucun feature gating type `if (ak_is_pro())` dans le code Free
- [x] Aucune fonction réservée à Pro qui appelle un service externe non documenté
- [x] 1 page admin "Upgrade to Pro" (conforme aux guidelines : 1 page max + lien externe)
- [x] Aucune notice/popup intrusive hors pages plugin
- [x] PHP 7.4+ compatible (pas de syntaxe PHP 8 exclusive)
- [x] WordPress 5.8+ compatible
- [x] Strings anglicisées dans le code source (113 chaînes dans `languages/artistkit.pot`)
- [x] Toutes sorties HTML échappées (`esc_html`, `esc_url`, `esc_attr`)
- [x] Tous formulaires admin protégés par nonce
- [x] Toutes les actions admin vérifient `current_user_can`
- [x] Validation/sanitization de tous les inputs (`sanitize_text_field`, `sanitize_email`, `esc_url_raw`, `wp_unslash`, etc.)
- [x] Aucune dépendance externe Composer
- [x] PHP syntax check : 0 erreur sur 15 fichiers PHP

### Build artefacts
- [x] Tag git `v2.0.0` créé sur le merge commit `3819bae` (branche `main`)
- [x] Tag git `v1.3.7-pre-refactor` créé comme backup primaire (poussé sur origin)
- [x] ZIP final : `/tmp/artistkit-2.0.0.zip` (40017 bytes, 33 fichiers)
  - MD5 actuel : `ce5552eee727ecaecc23e7f3639cd29c` (peut différer après
    rebuild — les MD5 ZIP ne sont pas déterministes à cause des timestamps
    embarqués ; comparer le contenu via `unzip -l` plutôt que le MD5)
  - Structure : `artistkit/` à la racine (folder name = slug)
  - Pas de `.git`, `.DS_Store`, `REFACTOR-INVENTORY.md`, `SUBMISSION-CHECKLIST.md`, ni dirs vides

### Commande de rebuild reproductible
```bash
cd /Users/davidabakan/Desktop/promoTracker-saas
rm -rf /tmp/wporg-build /tmp/artistkit-2.0.0.zip
mkdir -p /tmp/wporg-build
cp -r github-artistkit /tmp/wporg-build/artistkit
cd /tmp/wporg-build/artistkit
rm -rf .git REFACTOR-INVENTORY.md SUBMISSION-CHECKLIST.md
find . -name ".DS_Store" -delete
find . -type d -empty -delete
cd /tmp/wporg-build
zip -rq /tmp/artistkit-2.0.0.zip artistkit/
```

---

## 📦 Assets visuels à préparer (manuel — David)

⚠️ **NON GÉNÉRÉS DANS CE BRIEF** — à préparer avant soumission.

Conventions WP.org pour assets (dossier `/assets/` séparé du plugin, uploadé via SVN après acceptation, **PAS dans le ZIP**) :

| Asset | Dimensions | Format | Nom de fichier |
|-------|------------|--------|----------------|
| Banner standard | 1544 × 500 px | PNG ou JPG | `banner-1544x500.png` |
| Banner retina | 772 × 250 px (en plus) | PNG ou JPG | `banner-772x250.png` |
| Icon standard | 256 × 256 px | PNG ou JPG (transparence OK) | `icon-256x256.png` |
| Icon retina | 128 × 128 px (en plus) | PNG ou JPG | `icon-128x128.png` |
| Screenshot 1 | min 1024 px largeur | PNG | `screenshot-1.png` |
| Screenshot 2 | min 1024 px largeur | PNG | `screenshot-2.png` |
| Screenshot 3 | min 1024 px largeur | PNG | `screenshot-3.png` |
| Screenshot 4 | min 1024 px largeur | PNG | `screenshot-4.png` |

Les screenshots doivent matcher l'ordre du bloc `== Screenshots ==` dans `readme.txt` :
1. Artist EPK page frontend
2. Plugin admin dashboard
3. Editing an artist profile
4. Mobile view of an EPK page

⚠️ **Les screenshots nécessitent un WordPress avec ArtistKit v2.0.0 installé + un Artist EPK rempli** — à capturer après installation locale ou sur un site staging.

---

## 🚀 Procédure de soumission (manuel — David)

### Étape 1 — Compte WP.org
1. Vérifier le login `hexagonwebfr` sur https://wordpress.org/
2. Si nécessaire, attacher le mail valide pour les notifications de review

### Étape 2 — Submission du plugin
1. Aller sur https://wordpress.org/plugins/developers/add/
2. Upload `artistkit-2.0.0.zip` (40 KB, MD5 `88d352529e8b7c28c74581315f4393bc`)
3. Compléter le formulaire (description courte, tags, etc.)
4. Soumettre
5. Attendre la confirmation par email (généralement < 24h)

### Étape 3 — Review WP.org
- **Délai** : généralement 2 à 12 semaines (variable)
- L'équipe WP.org peut demander des modifications (security, GPL compliance, naming, etc.)
- Répondre rapidement aux mails de review pour ne pas perdre la place dans la queue
- **Risques connus** :
  - Mention « Pro » dans le code Free → ✅ aucun, vérifié
  - Lien d'affiliation/sponsor → ✅ aucun
  - Tracking utilisateur non consenti → ✅ aucun (analytics restent côté Pro)
  - Téléchargement de code externe → ✅ aucun
  - Mauvais escaping → ✅ vérifié manuellement

### Étape 4 — Acceptation & SVN
Une fois accepté :
1. Récupérer l'accès SVN à `https://plugins.svn.wordpress.org/artistkit/`
2. Checkout : `svn co https://plugins.svn.wordpress.org/artistkit/ artistkit-svn`
3. Copier les fichiers v2.0.0 dans `artistkit-svn/trunk/`
4. Copier les assets visuels dans `artistkit-svn/assets/`
5. Créer le tag : copier `trunk/` → `tags/2.0.0/`
6. Commit SVN avec un message clair : `svn ci -m "Release v2.0.0"`
7. Vérifier sur https://wordpress.org/plugins/artistkit/ que la version 2.0.0 apparaît

### Étape 5 — Communication
- Mettre à jour la landing PromoTracker (FR + EN) pour pointer vers WP.org si pertinent
- Communiquer la sortie aux users Pro existants (mais 0 licence en DB pour l'instant)
- Annoncer la disponibilité du Free sur WP.org

---

## 🔄 Process post-soumission pour les futures versions

### Patches (2.0.x — bugfix only)
1. Bugfix sur la branche `main` (ou hotfix branch)
2. Update `Stable tag` dans `readme.txt`
3. Update `Version:` dans header de `artistkit.php` + `AK_VERSION` constant
4. Update `== Changelog ==` dans `readme.txt`
5. Commit + tag `v2.0.1`
6. Build ZIP propre (cf. section "Build artefacts")
7. SVN : `svn cp trunk/ tags/2.0.1/` + commit
8. Auto-update se déclenche pour les users dans les heures qui suivent

### Minor (2.x.0 — nouvelle feature non-breaking)
- Idem que patches, mais ajouter une section dans changelog
- Tester la rétro-compatibilité des hooks (briefer le repo Pro pour s'assurer qu'aucun hook n'a été supprimé/renommé)

### Major (3.0.0 — breaking change)
- Documenter le breaking change dans `== Upgrade Notice ==`
- Coordonner avec la version Pro pour la compatibilité (mettre à jour `AKP_REQUIRED_FREE_VERSION` côté Pro)
- Tester un upgrade depuis v2.x → v3.x sur un site existant

---

## 🧪 Tests de soumission manuels (à effectuer AVANT submission)

⚠️ **NON RÉALISÉS DANS CE BRIEF** — à exécuter par David sur un WordPress de test (Local, Docker ou staging) :

- [ ] Installer `artistkit-2.0.0.zip` sur un WP propre 6.x → s'active sans erreur
- [ ] `WP_DEBUG=true` + `WP_DEBUG_LOG=true` → aucun warning/notice/deprecation dans `debug.log`
- [ ] Créer un Artist EPK avec tous les champs remplis → s'enregistre sans erreur
- [ ] `yoursite.com/epk` → affiche l'EPK correctement
- [ ] Page "Upgrade to Pro" accessible → ne contient aucune feature gated
- [ ] Désactiver le plugin → menu disparaît, aucune erreur
- [ ] Désinstaller le plugin → `ak_settings` supprimée + posts `ak_artist_epk` supprimés
- [ ] Réinstaller → fonctionne en clean install
- [ ] Tester avec un thème WP classique (Twenty Twenty-Four)
- [ ] Tester avec un thème WP block (Twenty Twenty-Five)
- [ ] Test mobile (375 px) → responsive OK
- [ ] Test PHP 7.4 → aucune erreur
- [ ] Test PHP 8.2 → aucune erreur

---

## 🔗 Intégration Free + Pro (test manuel)

⚠️ **À effectuer après acceptation Free WP.org** :

- [ ] Installer Free v2.0.0 depuis WP.org → fonctionne
- [ ] Installer Pro v1.0.0 (manuellement depuis ZIP `/tmp/artistkit-pro-1.0.0.zip`) → notice "requires ArtistKit v2.0.0+" si Free absent
- [ ] Avec Free actif, activer Pro → features Pro disponibles
- [ ] Désactiver Free quand Pro actif → Pro se désactive automatiquement
- [ ] Entrer une mauvaise licence → message "Invalid license"
- [ ] Entrer une bonne licence (à créer manuellement en DB via SQL ou via le webhook Stripe) → features débloquées

---

## 📋 Estimations délais

| Étape | Estimation |
|-------|-----------|
| Préparation assets visuels (banner, icon, screenshots) | 2-4h |
| Tests de soumission manuels | 2-3h |
| Soumission WP.org | 30 min |
| **Review WP.org (variable)** | **2-12 semaines** |
| Possibles allers-retours pendant la review | 1-4h cumulé |
| SVN setup + premier commit après acceptation | 1h |

---

## ❗ À NE PAS FAIRE

- ❌ Soumettre le ZIP `artistkit-pro-1.0.0.zip` à WP.org (c'est un plugin commercial)
- ❌ Inclure des assets visuels DANS le ZIP du plugin (ils vont dans `/assets/` SVN séparément)
- ❌ Distribuer la version Pro publiquement (privé / via licence uniquement)
- ❌ Modifier la version dans le ZIP sans bump version dans `artistkit.php` + `readme.txt` (cohérence sinon WP.org refuse)
- ❌ Soumettre une version qui n'a pas été testée localement
