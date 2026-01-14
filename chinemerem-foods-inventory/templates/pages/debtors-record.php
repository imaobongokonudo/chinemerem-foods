<?php
/**
 * Debtors Record Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$debtors = CFI_Debtors::get_all();
$is_admin = CFI_Auth::is_cfi_admin();
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-user-clock"></i>
                <?php esc_html_e('Debtors Record', 'chinemerem-foods'); ?>
            </h1>
            <div class="cfi-page-actions">
                <?php $debtors_history = get_page_by_path('cfi-debtors-history'); ?>
                <?php if ($debtors_history) : ?>
                <a href="<?php echo esc_url(get_permalink($debtors_history->ID)); ?>" class="cfi-btn cfi-btn-outline cfi-btn-sm">
                    <i class="fas fa-history"></i>
                    <?php esc_html_e('View History', 'chinemerem-foods'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($is_admin) : ?>
        <div class="cfi-admin-section cfi-glass" style="margin-bottom: 1.5rem;">
            <h3><i class="fas fa-user-plus"></i> <?php esc_html_e('Add New Debtor', 'chinemerem-foods'); ?></h3>
            <form id="cfi-add-debtor-form" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
                <div class="cfi-form-group" style="flex: 1; min-width: 200px; margin: 0;">
                    <label for="debtor-name"><?php esc_html_e('Name', 'chinemerem-foods'); ?></label>
                    <input type="text" id="debtor-name" name="name" class="cfi-input" required>
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 150px; margin: 0;">
                    <label for="debtor-phone"><?php esc_html_e('Phone', 'chinemerem-foods'); ?></label>
                    <input type="text" id="debtor-phone" name="phone" class="cfi-input">
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 200px; margin: 0;">
                    <label for="debtor-email"><?php esc_html_e('Email', 'chinemerem-foods'); ?></label>
                    <input type="email" id="debtor-email" name="email" class="cfi-input">
                </div>
                <button type="submit" class="cfi-btn cfi-btn-primary">
                    <i class="fas fa-plus"></i>
                    <?php esc_html_e('Add Debtor', 'chinemerem-foods'); ?>
                </button>
            </form>
        </div>
        <?php endif; ?>
        
        <div class="cfi-cards-grid">
            <?php if (empty($debtors)) : ?>
            <div class="cfi-glass" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <i class="fas fa-users" style="font-size: 3rem; color: var(--cfi-gray); margin-bottom: 1rem;"></i>
                <h3><?php esc_html_e('No Debtors Found', 'chinemerem-foods'); ?></h3>
                <p><?php esc_html_e('Admin can add debtors using the form above.', 'chinemerem-foods'); ?></p>
            </div>
            <?php else : ?>
            <?php foreach ($debtors as $debtor) : ?>
            <div class="cfi-card cfi-debtor-card" data-debtor-id="<?php echo esc_attr($debtor->id); ?>">
                <div class="cfi-debtor-info">
                    <h3 class="cfi-debtor-name"><?php echo esc_html($debtor->name); ?></h3>
                    <?php if ($debtor->phone) : ?>
                    <p style="color: var(--cfi-gray); margin: 0;"><i class="fas fa-phone"></i> <?php echo esc_html($debtor->phone); ?></p>
                    <?php endif; ?>
                </div>
                <div class="cfi-debtor-balance">
                    <?php echo esc_html(CFI_Products::format_price($debtor->total_debt)); ?>
                </div>
                <div class="cfi-debtor-actions" style="display: flex; gap: 0.5rem;">
                    <button type="button" class="cfi-btn cfi-btn-primary cfi-btn-sm cfi-debtor-order" data-debtor-id="<?php echo esc_attr($debtor->id); ?>">
                        <i class="fas fa-cart-plus"></i>
                        <?php esc_html_e('Order', 'chinemerem-foods'); ?>
                    </button>
                    <button type="button" class="cfi-btn cfi-btn-success cfi-btn-sm cfi-debtor-pay" data-debtor-id="<?php echo esc_attr($debtor->id); ?>">
                        <i class="fas fa-money-check"></i>
                        <?php esc_html_e('Clear Debt', 'chinemerem-foods'); ?>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Debtor Order Modal -->
<div id="cfi-debtor-order-modal" class="cfi-modal-overlay">
    <div class="cfi-modal" style="max-width: 800px;">
        <div class="cfi-modal-header">
            <h3><i class="fas fa-cart-plus"></i> <span id="cfi-modal-debtor-name"></span></h3>
            <button class="cfi-modal-close">&times;</button>
        </div>
        <div class="cfi-modal-body">
            <p><strong><?php esc_html_e('Current Balance:', 'chinemerem-foods'); ?></strong> <span id="cfi-modal-debtor-balance"></span></p>
            
            <div class="cfi-table-wrapper">
                <table id="cfi-debtor-order-table" class="cfi-table cfi-order-table">
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
            
            <div class="cfi-order-total" id="cfi-debtor-order-total">
                <div class="cfi-order-total-item">
                    <span class="cfi-order-total-label"><?php esc_html_e('Order Total', 'chinemerem-foods'); ?></span>
                    <span class="cfi-order-total-value" id="cfi-debtor-order-amount">₦0.00</span>
                </div>
            </div>
        </div>
        <div class="cfi-modal-footer">
            <button class="cfi-btn cfi-btn-outline cfi-modal-cancel"><?php esc_html_e('Cancel', 'chinemerem-foods'); ?></button>
            <button class="cfi-btn cfi-btn-primary" id="cfi-submit-debtor-order"><?php esc_html_e('Add to Debt', 'chinemerem-foods'); ?></button>
        </div>
    </div>
</div>

<!-- Debtor Payment Modal -->
<div id="cfi-debtor-pay-modal" class="cfi-modal-overlay">
    <div class="cfi-modal">
        <div class="cfi-modal-header">
            <h3><i class="fas fa-money-check"></i> <?php esc_html_e('Clear Debt', 'chinemerem-foods'); ?></h3>
            <button class="cfi-modal-close">&times;</button>
        </div>
        <div class="cfi-modal-body">
            <p><strong><?php esc_html_e('Debtor:', 'chinemerem-foods'); ?></strong> <span id="cfi-pay-debtor-name"></span></p>
            <p><strong><?php esc_html_e('Outstanding Balance:', 'chinemerem-foods'); ?></strong> <span id="cfi-pay-debtor-balance" style="color: var(--cfi-danger);"></span></p>
            
            <div class="cfi-payment-methods" style="margin-top: 1.5rem;">
                <div class="cfi-payment-method" data-method="transfer">
                    <i class="fas fa-credit-card"></i>
                    <span><?php esc_html_e('Transfer/Card', 'chinemerem-foods'); ?></span>
                </div>
                <div class="cfi-payment-method" data-method="cash">
                    <i class="fas fa-money-bill-wave"></i>
                    <span><?php esc_html_e('Cash', 'chinemerem-foods'); ?></span>
                </div>
                <?php if ($is_admin) : ?>
                <div class="cfi-payment-method" data-method="home">
                    <i class="fas fa-home"></i>
                    <span><?php esc_html_e('Home Calculation', 'chinemerem-foods'); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div id="cfi-pay-bank-options" class="cfi-bank-options" style="display: none;">
                <h4><?php esc_html_e('Select Bank', 'chinemerem-foods'); ?></h4>
                <label class="cfi-bank-option">
                    <input type="radio" name="pay-bank" value="Moniepoint MFB" checked>
                    <span><?php esc_html_e('Moniepoint MFB', 'chinemerem-foods'); ?></span>
                </label>
                <label class="cfi-bank-option">
                    <input type="radio" name="pay-bank" value="Access Bank PLC">
                    <span><?php esc_html_e('Access Bank PLC', 'chinemerem-foods'); ?></span>
                </label>
            </div>
            
            <div id="cfi-pay-amounts" style="margin-top: 1.5rem; display: none;">
                <div class="cfi-form-group" id="cfi-pay-transfer-group" style="display: none;">
                    <label for="cfi-pay-transfer-amount"><?php esc_html_e('Transfer Amount (₦)', 'chinemerem-foods'); ?></label>
                    <input type="number" id="cfi-pay-transfer-amount" class="cfi-input" min="0" step="0.01" value="0">
                </div>
                <div class="cfi-form-group" id="cfi-pay-cash-group" style="display: none;">
                    <label for="cfi-pay-cash-amount"><?php esc_html_e('Cash Amount (₦)', 'chinemerem-foods'); ?></label>
                    <input type="number" id="cfi-pay-cash-amount" class="cfi-input" min="0" step="0.01" value="0">
                </div>
                <div class="cfi-form-group" id="cfi-pay-home-group" style="display: none;">
                    <label for="cfi-pay-home-amount"><?php esc_html_e('Home Calculation Amount (₦)', 'chinemerem-foods'); ?></label>
                    <input type="number" id="cfi-pay-home-amount" class="cfi-input" min="0" step="0.01" value="0" <?php echo $is_admin ? '' : 'readonly'; ?>>
                </div>
            </div>
        </div>
        <div class="cfi-modal-footer">
            <button class="cfi-btn cfi-btn-outline cfi-modal-cancel"><?php esc_html_e('Cancel', 'chinemerem-foods'); ?></button>
            <button class="cfi-btn cfi-btn-success" id="cfi-submit-debtor-payment"><?php esc_html_e('Record Payment', 'chinemerem-foods'); ?></button>
        </div>
    </div>
</div>
