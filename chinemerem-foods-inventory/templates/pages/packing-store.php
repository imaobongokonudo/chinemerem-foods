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
                <a href="<?php echo esc_url(home_url('/packing-history/')); ?>" class="cfi-btn cfi-btn-outline cfi-btn-sm">
                    <i class="fas fa-history"></i>
                    <?php esc_html_e('View History', 'chinemerem-foods'); ?>
                </a>
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
                <table id="cfi-packing-table" class="cfi-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Item', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Open', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('To Pack', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Fr Pack', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Balance', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Fr Sales', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('To Sales', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Remarks', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Close', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="cfi-packing-tbody">
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
            <p style="font-size: 0.85rem;"><strong><?php esc_html_e('Close', 'chinemerem-foods'); ?></strong> = Open - To Pack + From Pack + From Sales - To Sales</p>
            <p style="font-size: 0.8rem;"><em><?php esc_html_e('Note: Balance is carried over but not included in closing.', 'chinemerem-foods'); ?></em></p>
        </div>
    </div>
</main>
