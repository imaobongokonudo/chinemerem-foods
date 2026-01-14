<?php
/**
 * Stock Record Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-boxes"></i>
                <?php esc_html_e('Stock Inventory', 'chinemerem-foods'); ?>
            </h1>
            <div class="cfi-page-actions">
                <?php $stock_history = get_page_by_path('cfi-stock-history'); ?>
                <?php if ($stock_history) : ?>
                <a href="<?php echo esc_url(get_permalink($stock_history->ID)); ?>" class="cfi-btn cfi-btn-outline cfi-btn-sm">
                    <i class="fas fa-history"></i>
                    <?php esc_html_e('View History', 'chinemerem-foods'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="cfi-filters cfi-glass">
            <div class="cfi-filter-group">
                <label for="cfi-stock-date"><?php esc_html_e('Date:', 'chinemerem-foods'); ?></label>
                <input type="date" id="cfi-stock-date" class="cfi-input" value="<?php echo esc_attr(current_time('Y-m-d')); ?>">
            </div>
            <button type="button" id="cfi-load-stock" class="cfi-btn cfi-btn-primary cfi-btn-sm">
                <i class="fas fa-sync"></i>
                <?php esc_html_e('Load', 'chinemerem-foods'); ?>
            </button>
        </div>
        
        <div id="cfi-stock-form" class="cfi-glass">
            <div class="cfi-table-wrapper">
                <table id="cfi-stock-table" class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Item', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Opening', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Import', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Cash Supply', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Credit Supply', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Not Supplied', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Supplied Today', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('To Packing', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('From Packing', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Closing', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 1.5rem; text-align: right;">
                <button type="button" id="cfi-save-stock" class="cfi-btn cfi-btn-success">
                    <i class="fas fa-save"></i>
                    <?php esc_html_e('Save Changes', 'chinemerem-foods'); ?>
                </button>
            </div>
        </div>
        
        <div class="cfi-info-box cfi-glass" style="margin-top: 1.5rem;">
            <h4><i class="fas fa-info-circle"></i> <?php esc_html_e('Calculation Formula', 'chinemerem-foods'); ?></h4>
            <p><strong><?php esc_html_e('Closing', 'chinemerem-foods'); ?></strong> = Opening + Import - Cash Supply - Credit Supply + Not Supplied - Supplied Today - To Packing + From Packing</p>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    // Load stock on date change
    $('#cfi-load-stock, #cfi-stock-date').on('change click', function() {
        CFI.stock.loadStock();
    });
});
</script>
