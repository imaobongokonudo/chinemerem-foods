<?php
/**
 * Take Order Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-cart-plus"></i>
                <?php esc_html_e('Take Order', 'chinemerem-foods'); ?>
            </h1>
            <div class="cfi-page-actions">
                <?php
                $order_history = get_page_by_path('cfi-order-history');
                $transfer_history = get_page_by_path('cfi-transfer-history');
                ?>
                <?php if ($order_history) : ?>
                <a href="<?php echo esc_url(get_permalink($order_history->ID)); ?>" class="cfi-btn cfi-btn-outline cfi-btn-sm">
                    <i class="fas fa-history"></i>
                    <?php esc_html_e('Order History', 'chinemerem-foods'); ?>
                </a>
                <?php endif; ?>
                <?php if ($transfer_history) : ?>
                <a href="<?php echo esc_url(get_permalink($transfer_history->ID)); ?>" class="cfi-btn cfi-btn-outline cfi-btn-sm">
                    <i class="fas fa-exchange-alt"></i>
                    <?php esc_html_e('Transfer History', 'chinemerem-foods'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="cfi-order-form" class="cfi-glass">
            <h3><?php esc_html_e('Order Items', 'chinemerem-foods'); ?></h3>
            
            <div class="cfi-table-wrapper">
                <table id="cfi-order-table" class="cfi-table cfi-table-responsive cfi-order-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Item', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Price (₦)', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Quantity', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Discount (₦)', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Total (₦)', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div class="cfi-order-total">
                <div class="cfi-order-total-item">
                    <span class="cfi-order-total-label"><?php esc_html_e('Total Quantity', 'chinemerem-foods'); ?></span>
                    <span class="cfi-order-total-value" id="cfi-total-qty">0</span>
                </div>
                <div class="cfi-order-total-item">
                    <span class="cfi-order-total-label"><?php esc_html_e('Total Amount', 'chinemerem-foods'); ?></span>
                    <span class="cfi-order-total-value" id="cfi-total-amount">₦0.00</span>
                </div>
                <div class="cfi-order-total-item">
                    <span class="cfi-order-total-label"><?php esc_html_e('Total Discount', 'chinemerem-foods'); ?></span>
                    <span class="cfi-order-total-value" id="cfi-total-discount">₦0.00</span>
                </div>
                <div class="cfi-order-total-item">
                    <span class="cfi-order-total-label"><?php esc_html_e('Grand Total', 'chinemerem-foods'); ?></span>
                    <span class="cfi-order-total-value" id="cfi-grand-total" style="color: var(--cfi-success); font-size: 2rem;">₦0.00</span>
                </div>
            </div>
        </div>
        
        <div class="cfi-payment-section cfi-glass" style="margin-top: 1.5rem;">
            <h3><?php esc_html_e('Payment Method', 'chinemerem-foods'); ?></h3>
            
            <div class="cfi-payment-methods">
                <div class="cfi-payment-method" data-method="transfer">
                    <i class="fas fa-credit-card"></i>
                    <span><?php esc_html_e('Transfer/Card', 'chinemerem-foods'); ?></span>
                </div>
                <div class="cfi-payment-method active" data-method="cash">
                    <i class="fas fa-money-bill-wave"></i>
                    <span><?php esc_html_e('Cash', 'chinemerem-foods'); ?></span>
                </div>
            </div>
            
            <div id="cfi-bank-options" class="cfi-bank-options">
                <h4><?php esc_html_e('Select Bank', 'chinemerem-foods'); ?></h4>
                <label class="cfi-bank-option">
                    <input type="radio" name="bank" value="Moniepoint MFB" checked>
                    <span><?php esc_html_e('Moniepoint MFB', 'chinemerem-foods'); ?></span>
                </label>
                <label class="cfi-bank-option">
                    <input type="radio" name="bank" value="Access Bank PLC">
                    <span><?php esc_html_e('Access Bank PLC', 'chinemerem-foods'); ?></span>
                </label>
            </div>
            
            <div id="cfi-split-payment" class="cfi-split-payment">
                <div class="cfi-form-group" style="flex: 1; min-width: 150px;">
                    <label for="cfi-transfer-amount"><?php esc_html_e('Transfer Amount (₦)', 'chinemerem-foods'); ?></label>
                    <input type="number" id="cfi-transfer-amount" class="cfi-input" min="0" step="0.01" value="0">
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 150px;">
                    <label for="cfi-cash-amount"><?php esc_html_e('Cash Amount (₦)', 'chinemerem-foods'); ?></label>
                    <input type="number" id="cfi-cash-amount" class="cfi-input" min="0" step="0.01" value="0">
                </div>
            </div>
            
            <div class="cfi-form-group" style="margin-top: 1.5rem;">
                <label class="cfi-checkbox">
                    <input type="checkbox" id="cfi-payment-confirm">
                    <span><?php esc_html_e('I confirm that payment has been received', 'chinemerem-foods'); ?></span>
                </label>
            </div>
            
            <button type="button" id="cfi-submit-order" class="cfi-btn cfi-btn-success cfi-btn-lg" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-check-circle"></i>
                <?php esc_html_e('Submit Order', 'chinemerem-foods'); ?>
            </button>
        </div>
    </div>
</main>
