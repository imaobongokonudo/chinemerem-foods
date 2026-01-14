<?php
/**
 * Packing Store Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-box-open"></i>
                <?php esc_html_e('Packing Store', 'chinemerem-foods'); ?>
            </h1>
            <div class="cfi-page-actions">
                <?php $packing_history = get_page_by_path('cfi-packing-history'); ?>
                <?php if ($packing_history) : ?>
                <a href="<?php echo esc_url(get_permalink($packing_history->ID)); ?>" class="cfi-btn cfi-btn-outline cfi-btn-sm">
                    <i class="fas fa-history"></i>
                    <?php esc_html_e('View History', 'chinemerem-foods'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="cfi-filters cfi-glass">
            <div class="cfi-filter-group">
                <label for="cfi-packing-date"><?php esc_html_e('Date:', 'chinemerem-foods'); ?></label>
                <input type="date" id="cfi-packing-date" class="cfi-input" value="<?php echo esc_attr(current_time('Y-m-d')); ?>">
            </div>
            <button type="button" id="cfi-load-packing" class="cfi-btn cfi-btn-primary cfi-btn-sm">
                <i class="fas fa-sync"></i>
                <?php esc_html_e('Load', 'chinemerem-foods'); ?>
            </button>
        </div>
        
        <div id="cfi-packing-form" class="cfi-glass">
            <div class="cfi-table-wrapper">
                <table id="cfi-packing-table" class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Item', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Opening', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('To Packing', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('From Packing', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Balance', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('From Sales', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('To Sales', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Remarks', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Closing', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 1.5rem; text-align: right;">
                <button type="button" id="cfi-save-packing" class="cfi-btn cfi-btn-success">
                    <i class="fas fa-save"></i>
                    <?php esc_html_e('Save Changes', 'chinemerem-foods'); ?>
                </button>
            </div>
        </div>
        
        <div class="cfi-info-box cfi-glass" style="margin-top: 1.5rem;">
            <h4><i class="fas fa-info-circle"></i> <?php esc_html_e('Calculation Formula', 'chinemerem-foods'); ?></h4>
            <p><strong><?php esc_html_e('Closing', 'chinemerem-foods'); ?></strong> = Opening - To Packing + From Packing + From Sales - To Sales</p>
            <p><em><?php esc_html_e('Note: Balance in packing store is carried over to the next day but not included in closing calculation.', 'chinemerem-foods'); ?></em></p>
        </div>
    </div>
</main>
