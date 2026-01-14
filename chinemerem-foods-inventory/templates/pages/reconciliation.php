<?php
/**
 * Reconciliation Calendar Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_month = current_time('Y-m');
$calendar = CFI_Reconciliation::get_month($current_month);
$is_admin = CFI_Auth::is_cfi_admin();
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-calendar-check"></i>
                <?php esc_html_e('Reconciliation Calendar', 'chinemerem-foods'); ?>
            </h1>
        </div>
        
        <div class="cfi-glass">
            <div class="cfi-filters" style="background: transparent; padding: 0; margin-bottom: 1.5rem;">
                <div class="cfi-filter-group">
                    <label for="cfi-reconcile-month"><?php esc_html_e('Month:', 'chinemerem-foods'); ?></label>
                    <input type="month" id="cfi-reconcile-month" class="cfi-input" value="<?php echo esc_attr($current_month); ?>">
                </div>
                <button type="button" id="cfi-load-calendar" class="cfi-btn cfi-btn-primary cfi-btn-sm">
                    <i class="fas fa-sync"></i>
                    <?php esc_html_e('Load', 'chinemerem-foods'); ?>
                </button>
            </div>
            
            <h3><?php echo esc_html(date_i18n('F Y', strtotime($current_month . '-01'))); ?></h3>
            
            <div class="cfi-calendar">
                <div class="cfi-calendar-header"><?php esc_html_e('Sun', 'chinemerem-foods'); ?></div>
                <div class="cfi-calendar-header"><?php esc_html_e('Mon', 'chinemerem-foods'); ?></div>
                <div class="cfi-calendar-header"><?php esc_html_e('Tue', 'chinemerem-foods'); ?></div>
                <div class="cfi-calendar-header"><?php esc_html_e('Wed', 'chinemerem-foods'); ?></div>
                <div class="cfi-calendar-header"><?php esc_html_e('Thu', 'chinemerem-foods'); ?></div>
                <div class="cfi-calendar-header"><?php esc_html_e('Fri', 'chinemerem-foods'); ?></div>
                <div class="cfi-calendar-header"><?php esc_html_e('Sat', 'chinemerem-foods'); ?></div>
                
                <?php
                // Add empty cells for days before the first day of month
                $first_day = date('w', strtotime($current_month . '-01'));
                for ($i = 0; $i < $first_day; $i++) {
                    echo '<div class="cfi-calendar-day disabled"></div>';
                }
                
                foreach ($calendar as $day) :
                    $classes = array('cfi-calendar-day');
                    if ($day['is_today']) $classes[] = 'today';
                    if ($day['is_reconciled']) $classes[] = 'reconciled';
                    elseif ($day['has_first_signature']) $classes[] = 'partial';
                    if ($day['is_reconciled'] && !$day['is_today']) $classes[] = 'disabled';
                ?>
                <div class="<?php echo esc_attr(implode(' ', $classes)); ?>" 
                     data-date="<?php echo esc_attr($day['date']); ?>"
                     <?php if ($is_admin && !$day['is_reconciled']) : ?>onclick="reconcileDate('<?php echo esc_attr($day['date']); ?>')"<?php endif; ?>>
                    <span class="day-number"><?php echo esc_html($day['day']); ?></span>
                    <?php if ($day['is_reconciled']) : ?>
                    <i class="fas fa-check" title="<?php esc_attr_e('Reconciled', 'chinemerem-foods'); ?>"></i>
                    <?php elseif ($day['has_first_signature']) : ?>
                    <i class="fas fa-user-check" title="<?php esc_attr_e('Waiting for 2nd admin', 'chinemerem-foods'); ?>"></i>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cfi-legend" style="display: flex; gap: 2rem; margin-top: 1.5rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="width: 20px; height: 20px; background: var(--cfi-success); border-radius: 4px;"></span>
                    <span><?php esc_html_e('Reconciled', 'chinemerem-foods'); ?></span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="width: 20px; height: 20px; background: var(--cfi-warning); border-radius: 4px;"></span>
                    <span><?php esc_html_e('Waiting for 2nd Admin', 'chinemerem-foods'); ?></span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="width: 20px; height: 20px; background: var(--cfi-white); border: 1px solid var(--cfi-border); border-radius: 4px;"></span>
                    <span><?php esc_html_e('Not Reconciled', 'chinemerem-foods'); ?></span>
                </div>
            </div>
            
            <?php if (!$is_admin) : ?>
            <div class="cfi-info-box" style="margin-top: 1.5rem; padding: 1rem; background: var(--cfi-light); border-radius: var(--cfi-radius-sm);">
                <p><i class="fas fa-info-circle"></i> <?php esc_html_e('Only admins can mark dates as reconciled. Two different admins must sign each date.', 'chinemerem-foods'); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
function reconcileDate(date) {
    if (!confirm('Are you sure you want to reconcile ' + date + '?')) {
        return;
    }
    
    CFI.ajax.request('reconcile_date', { date: date })
        .then(function(data) {
            CFI.toast.success(data.message);
            location.reload();
        })
        .catch(function(error) {
            CFI.toast.error(error);
        });
}

jQuery(document).ready(function($) {
    $('#cfi-load-calendar').on('click', function() {
        const month = $('#cfi-reconcile-month').val();
        window.location.href = window.location.pathname + '?month=' + month;
    });
});
</script>
