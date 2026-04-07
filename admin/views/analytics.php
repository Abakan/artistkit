<?php defined( 'ABSPATH' ) || exit;

$is_pro = ak_is_pro();

// ── Pro gate ─────────────────────────────────────────────────────────────────
if ( ! $is_pro ) : ?>
<div class="wrap">
  <h1 style="display:flex;align-items:center;gap:10px">
    <span>📊</span> Analytics EPK
    <span style="font-size:0.65em;font-weight:600;background:#7c3aed;color:#fff;padding:3px 10px;border-radius:20px;letter-spacing:0.05em;vertical-align:middle">PRO</span>
  </h1>

  <div style="margin-top:24px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:40px;max-width:640px">
    <p style="font-size:1.1rem;font-weight:700;margin:0 0 10px">Suivez les vues de vos EPKs en temps réel</p>
    <p style="color:#6b7280;margin:0 0 24px;line-height:1.6">
      Avec ArtistKit Pro, chaque visite sur vos Press Kits est enregistrée : nombre de vues par EPK, évolution jour par jour, sources de trafic.
      Idéal pour savoir quand votre EPK est consulté après l'envoi à un label ou un journaliste.
    </p>

    <!-- Teaser visuel : faux dashboard flouté -->
    <div style="position:relative;border-radius:10px;overflow:hidden;margin-bottom:28px;border:1px solid #e5e7eb">
      <div style="filter:blur(3px);opacity:0.5;padding:20px;background:#f9fafb;pointer-events:none">
        <!-- Faux stats cards -->
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px">
          <?php foreach ( [ ['142', 'Vues (30 j)'], ['3', 'EPKs actifs'], ['74%', 'Direct'] ] as $c ) : ?>
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:14px 16px">
              <div style="font-size:1.5rem;font-weight:800;color:#111"><?php echo $c[0]; ?></div>
              <div style="font-size:0.75rem;color:#9ca3af;margin-top:2px"><?php echo $c[1]; ?></div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Faux bar chart -->
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:16px">
          <div style="font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:12px">Vues par jour</div>
          <div style="display:flex;align-items:flex-end;gap:4px;height:60px">
            <?php
            $fake = [3,7,2,11,5,8,4,15,6,9,3,18,7,12,4,8,14,6,3,9,11,5,16,8,3,7,4,12,9,6];
            $fmax = max( $fake );
            foreach ( $fake as $v ) {
                $h = round( ($v / $fmax) * 60 );
                echo '<div style="flex:1;background:linear-gradient(to top,#7c3aed,#a78bfa);border-radius:3px 3px 0 0;height:' . $h . 'px"></div>';
            }
            ?>
          </div>
        </div>
      </div>
      <!-- Lock overlay -->
      <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;background:rgba(255,255,255,0.5)">
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px 28px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,0.1)">
          <div style="font-size:1.5rem;margin-bottom:8px">🔒</div>
          <div style="font-weight:700;font-size:0.95rem;margin-bottom:6px">Réservé à ArtistKit Pro</div>
          <a href="<?php echo esc_url( admin_url( 'admin.php?page=artistkit-settings' ) ); ?>"
             style="display:inline-block;margin-top:4px;background:#7c3aed;color:#fff;font-weight:700;font-size:0.85rem;padding:8px 20px;border-radius:8px;text-decoration:none">
            Passer en Pro →
          </a>
        </div>
      </div>
    </div>

    <a href="<?php echo esc_url( admin_url( 'admin.php?page=artistkit-settings' ) ); ?>"
       style="background:#7c3aed;color:#fff;font-weight:700;font-size:0.9rem;padding:10px 22px;border-radius:8px;text-decoration:none;display:inline-block">
      Activer ArtistKit Pro
    </a>
  </div>
</div>
<?php return; endif;

// ── Pro : page complète ───────────────────────────────────────────────────────

$period = isset( $_GET['period'] ) ? absint( $_GET['period'] ) : 30;
if ( ! in_array( $period, [ 7, 30, 90 ] ) ) $period = 30;

$stats = AK_Analytics::get_global_stats( $period );

// Build complete day range (fill missing days with 0)
$by_day_views_map     = [];
$by_day_downloads_map = [];
foreach ( $stats['by_day'] as $row ) {
    $by_day_views_map[ $row->day ]     = (int) $row->views;
    $by_day_downloads_map[ $row->day ] = (int) $row->downloads;
}
$days_range           = [];
$days_range_downloads = [];
for ( $i = $period - 1; $i >= 0; $i-- ) {
    $date = date( 'Y-m-d', strtotime( "-{$i} days" ) );
    $days_range[ $date ]           = $by_day_views_map[ $date ] ?? 0;
    $days_range_downloads[ $date ] = $by_day_downloads_map[ $date ] ?? 0;
}
$max_day_views     = max( array_values( $days_range ) ?: [1] );
$max_day_downloads = max( array_values( $days_range_downloads ) ?: [1] );
$avg_per_day       = $period > 0 ? round( $stats['total'] / $period, 1 ) : 0;

// Current page URL for period switcher
$base_url = admin_url( 'admin.php?page=artistkit-analytics' );
?>

<div class="wrap" id="ak-analytics-wrap">
  <h1 style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
    <span>📊</span> Analytics EPK
  </h1>

  <!-- Period switcher -->
  <div style="display:flex;gap:6px;margin-bottom:24px">
    <?php foreach ( [ 7 => '7 jours', 30 => '30 jours', 90 => '90 jours' ] as $d => $label ) :
      $active = ($d === $period);
    ?>
      <a href="<?php echo esc_url( $base_url . '&period=' . $d ); ?>"
         style="font-size:0.82rem;font-weight:700;padding:6px 16px;border-radius:6px;text-decoration:none;
         <?php echo $active
           ? 'background:#7c3aed;color:#fff;border:1px solid #7c3aed'
           : 'background:#fff;color:#374151;border:1px solid #d1d5db'; ?>">
        <?php echo esc_html( $label ); ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Stat cards -->
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;max-width:1060px;margin-bottom:28px">
    <?php
    $epk_with_views = count( $stats['by_epk'] );
    $cards = [
      [ 'label' => 'Vues totales',        'value' => number_format( $stats['total'] ),            'icon' => '👁' ],
      [ 'label' => 'Moy. par jour',       'value' => $avg_per_day,                                'icon' => '📈' ],
      [ 'label' => 'EPKs consultés',      'value' => $epk_with_views,                             'icon' => '🎤' ],
      [ 'label' => 'Téléchargements MP3', 'value' => number_format( $stats['total_downloads'] ),  'icon' => '⬇️' ],
    ];
    foreach ( $cards as $c ) : ?>
      <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px 22px">
        <div style="font-size:1.2rem;margin-bottom:6px"><?php echo $c['icon']; ?></div>
        <div style="font-size:1.8rem;font-weight:800;color:#111;line-height:1"><?php echo esc_html( $c['value'] ); ?></div>
        <div style="font-size:0.75rem;color:#9ca3af;margin-top:4px;font-weight:500"><?php echo esc_html( $c['label'] ); ?></div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Bar chart -->
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px 24px;max-width:1060px;margin-bottom:28px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
      <div style="font-size:0.88rem;font-weight:700;color:#374151">
        Vues & téléchargements — <?php echo esc_html( $period ); ?> derniers jours
      </div>
      <!-- Legend -->
      <div style="display:flex;gap:14px;font-size:0.75rem;color:#6b7280">
        <span style="display:flex;align-items:center;gap:5px">
          <span style="width:10px;height:10px;border-radius:2px;background:linear-gradient(to top,#7c3aed,#a78bfa);display:inline-block"></span>
          Vues
        </span>
        <span style="display:flex;align-items:center;gap:5px">
          <span style="width:10px;height:10px;border-radius:2px;background:linear-gradient(to top,#0ea5e9,#7dd3fc);display:inline-block"></span>
          Téléchargements
        </span>
      </div>
    </div>

    <?php if ( $stats['total'] === 0 && $stats['total_downloads'] === 0 ) : ?>
      <p style="color:#9ca3af;font-size:0.88rem;margin:0">Aucune vue enregistrée sur cette période. Les vues sont comptées dès la prochaine consultation d'un EPK publié.</p>
    <?php else :
      $max_combined = max( $max_day_views, $max_day_downloads, 1 );
      $gap_style    = $period > 30 ? '2' : '4';
    ?>
      <div style="display:flex;align-items:flex-end;gap:<?php echo $gap_style; ?>px;height:90px;overflow:hidden">
        <?php
        $dates_arr = array_keys( $days_range );
        foreach ( $dates_arr as $date ) :
          $v  = $days_range[ $date ];
          $dl = $days_range_downloads[ $date ];
          $hv  = $max_combined > 0 ? round( ($v  / $max_combined) * 86 ) : 0;
          $hdl = $max_combined > 0 ? round( ($dl / $max_combined) * 86 ) : 0;
          $tip = $date . ' : ' . $v . ' vue' . ($v !== 1 ? 's' : '') . ', ' . $dl . ' dl';
        ?>
          <div title="<?php echo esc_attr( $tip ); ?>"
               style="flex:1;min-width:0;display:flex;align-items:flex-end;gap:1px;cursor:default"
               onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
            <div style="flex:1;min-width:0;background:<?php echo $v  > 0 ? 'linear-gradient(to top,#7c3aed,#a78bfa)' : '#f3f4f6'; ?>;
                        border-radius:3px 3px 0 0;height:<?php echo max(2,$hv); ?>px"></div>
            <div style="flex:1;min-width:0;background:<?php echo $dl > 0 ? 'linear-gradient(to top,#0ea5e9,#7dd3fc)' : '#f3f4f6'; ?>;
                        border-radius:3px 3px 0 0;height:<?php echo max(2,$hdl); ?>px"></div>
          </div>
        <?php endforeach; ?>
      </div>
      <!-- Axis labels: first and last date -->
      <?php
      $first = date_i18n( 'd M', strtotime( $dates_arr[0] ) );
      $last  = date_i18n( 'd M', strtotime( end( $dates_arr ) ) );
      ?>
      <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:0.72rem;color:#9ca3af">
        <span><?php echo esc_html( $first ); ?></span>
        <span><?php echo esc_html( $last ); ?></span>
      </div>
    <?php endif; ?>
  </div>

  <!-- Per-EPK table -->
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;max-width:1060px;overflow:hidden;margin-bottom:28px">
    <div style="padding:16px 22px;font-size:0.88rem;font-weight:700;color:#374151;border-bottom:1px solid #f3f4f6">
      Vues par EPK
    </div>
    <?php if ( empty( $stats['by_epk'] ) ) : ?>
      <p style="padding:20px 22px;color:#9ca3af;font-size:0.88rem;margin:0">Aucune vue pour le moment.</p>
    <?php else : ?>
      <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
        <thead>
          <tr style="background:#f9fafb;color:#6b7280;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em">
            <th style="padding:10px 22px;text-align:left">EPK</th>
            <th style="padding:10px 22px;text-align:left">Type</th>
            <th style="padding:10px 22px;text-align:right">Vues</th>
            <th style="padding:10px 22px;text-align:right">DL MP3</th>
            <th style="padding:10px 22px;text-align:left">Répartition</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $grand_total = max( 1, array_sum( array_column( (array) $stats['by_epk'], 'views' ) ) );
          foreach ( $stats['by_epk'] as $row ) :
            $post  = get_post( $row->epk_id );
            $title = $post ? get_the_title( $post ) : '(EPK #' . $row->epk_id . ')';
            $type  = $row->epk_type === 'artist' ? '🎤 Artiste' : '💿 Release';
            $pct   = round( ($row->views / $grand_total) * 100 );
            $edit_url = $post ? get_edit_post_link( $post->ID ) : '#';
            $view_url = $post ? get_permalink( $post->ID ) : '#';
            $dl_count = isset( $row->downloads ) ? (int) $row->downloads : 0;
          ?>
            <tr style="border-top:1px solid #f3f4f6">
              <td style="padding:12px 22px;font-weight:600;color:#111">
                <a href="<?php echo esc_url( $edit_url ); ?>" style="color:#111;text-decoration:none">
                  <?php echo esc_html( $title ); ?>
                </a>
                <?php if ( $view_url && $view_url !== '#' ) : ?>
                  <a href="<?php echo esc_url( $view_url ); ?>" target="_blank"
                     style="margin-left:6px;font-size:0.75rem;color:#7c3aed;text-decoration:none;font-weight:500">↗</a>
                <?php endif; ?>
              </td>
              <td style="padding:12px 22px;color:#6b7280"><?php echo esc_html( $type ); ?></td>
              <td style="padding:12px 22px;text-align:right;font-weight:800;font-size:1rem;color:#111">
                <?php echo number_format( (int) $row->views ); ?>
              </td>
              <td style="padding:12px 22px;text-align:right;font-weight:700;color:<?php echo $dl_count > 0 ? '#0ea5e9' : '#9ca3af'; ?>">
                <?php echo $dl_count > 0 ? number_format( $dl_count ) : '—'; ?>
              </td>
              <td style="padding:12px 22px">
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="flex:1;background:#f3f4f6;border-radius:4px;height:6px;max-width:140px">
                    <div style="width:<?php echo $pct; ?>%;background:linear-gradient(to right,#7c3aed,#a78bfa);height:100%;border-radius:4px"></div>
                  </div>
                  <span style="font-size:0.75rem;color:#9ca3af;min-width:32px"><?php echo $pct; ?>%</span>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- Top referers -->
  <?php if ( ! empty( $stats['top_referers'] ) ) : ?>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;max-width:1060px;overflow:hidden;margin-bottom:28px">
    <div style="padding:16px 22px;font-size:0.88rem;font-weight:700;color:#374151;border-bottom:1px solid #f3f4f6">
      Sources de trafic
    </div>
    <table style="width:100%;border-collapse:collapse;font-size:0.85rem">
      <thead>
        <tr style="background:#f9fafb;color:#6b7280;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em">
          <th style="padding:10px 22px;text-align:left">Source</th>
          <th style="padding:10px 22px;text-align:right">Vues</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $stats['top_referers'] as $ref ) : ?>
          <tr style="border-top:1px solid #f3f4f6">
            <td style="padding:10px 22px;color:#374151;word-break:break-all;max-width:600px">
              <?php echo esc_html( $ref->referer ?: '(direct)' ); ?>
            </td>
            <td style="padding:10px 22px;text-align:right;font-weight:700;color:#111">
              <?php echo number_format( (int) $ref->views ); ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <p style="font-size:0.78rem;color:#9ca3af;margin:0">
    Les bots et crawlers sont automatiquement filtrés. Les vues sont comptées par visite unique de page.
  </p>
</div>
