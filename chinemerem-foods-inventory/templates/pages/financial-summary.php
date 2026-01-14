<?php
/**
 * Financial Summary Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$today = current_time('Y-m-d');
$summary = CFI_Financial::get_summary($today);
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-calculator"></i>
                <?php esc_html_e('Financial Summary', 'chinemerem-foods'); ?>
            </h1>
            <div class="cfi-page-actions">
                <?php $financial_history = get_page_by_path('cfi-financial-history'); ?>
                <?php if ($financial_history) : ?>
                <a href="<?php echo esc_url(get_permalink($financial_history->ID)); ?>" class="cfi-btn cfi-btn-outline cfi-btn-sm">
                    <i class="fas fa-history"></i>
                    <?php esc_html_e('View History', 'chinemerem-foods'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="cfi-glass">
            <h3><?php esc_html_e('Today\'s Financial Summary', 'chinemerem-foods'); ?> - <?php echo esc_html(current_time('l, F j, Y')); ?></h3>
            
            <div class="cfi-table-wrapper">
                <table class="cfi-table">
                    <tbody>
                        <tr>
                            <td><strong><i class="fas fa-shopping-cart"></i> <?php esc_html_e('Total Sales (Take Order)', 'chinemerem-foods'); ?></strong></td>
                            <td style="text-align: right; font-size: 1.25rem; font-weight: 600;"><?php echo esc_html(CFI_Products::format_price($summary->total_sales)); ?></td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-credit-card"></i> <?php esc_html_e('Transfer/Card (From Orders)', 'chinemerem-foods'); ?></strong></td>
                            <td style="text-align: right;"><?php echo esc_html(CFI_Products::format_price($summary->transfer_from_orders)); ?></td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-money-bill-wave"></i> <?php esc_html_e('Cash Sales', 'chinemerem-foods'); ?></strong></td>
                            <td style="text-align: right;"><?php echo esc_html(CFI_Products::format_price($summary->cash_sales)); ?></td>
                        </tr>
                        <tr style="background: var(--cfi-light);">
                            <td><strong><i class="fas fa-university"></i> <?php esc_html_e('Transfer/Card (Cash Out)', 'chinemerem-foods'); ?></strong></td>
                            <td style="text-align: right; color: var(--cfi-danger);">-<?php echo esc_html(CFI_Products::format_price($summary->transfer_from_cashout)); ?></td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-user-clock"></i> <?php esc_html_e('Transfer/Card (Debtors)', 'chinemerem-foods'); ?></strong></td>
                            <td style="text-align: right; color: var(--cfi-gray);"><?php echo esc_html(CFI_Products::format_price($summary->transfer_from_debtors)); ?> <small>(not in calc)</small></td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-hand-holding-usd"></i> <?php esc_html_e('Debtors Cash', 'chinemerem-foods'); ?></strong></td>
                            <td style="text-align: right; color: var(--cfi-success);">+<?php echo esc_html(CFI_Products::format_price($summary->debtors_cash)); ?></td>
                        </tr>
                        <tr style="background: var(--cfi-light);">
                            <td><strong><i class="fas fa-file-invoice-dollar"></i> <?php esc_html_e('Expenses', 'chinemerem-foods'); ?></strong></td>
                            <td style="text-align: right; color: var(--cfi-danger);">-<?php echo esc_html(CFI_Products::format_price($summary->expenses)); ?></td>
                        </tr>
                        <tr>
                            <td><strong><i class="fas fa-wallet"></i> <?php esc_html_e('Old Cash (Yesterday)', 'chinemerem-foods'); ?></strong></td>
                            <td style="text-align: right; color: var(--cfi-success);">+<?php echo esc_html(CFI_Products::format_price($summary->old_cash)); ?></td>
                        </tr>
                        <tr>
                            <td>
                                <strong><i class="fas fa-piggy-bank"></i> <?php esc_html_e('Cash to Bank', 'chinemerem-foods'); ?></strong>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem;">
                                    <span style="color: var(--cfi-danger);">-</span>
                                    <input type="number" id="cfi-cash-to-bank" class="cfi-input" style="max-width: 150px;" min="0" step="0.01" value="<?php echo esc_attr($summary->cash_to_bank); ?>">
                                    <button type="button" id="cfi-save-cash-to-bank" class="cfi-btn cfi-btn-primary cfi-btn-sm">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--cfi-success); color: var(--cfi-white);">
                            <td><strong><i class="fas fa-coins"></i> <?php esc_html_e('Cash Left', 'chinemerem-foods'); ?></strong></td>
                            <td style="text-align: right; font-size: 1.5rem;"><strong id="cfi-cash-left"><?php echo esc_html(CFI_Products::format_price($summary->cash_left)); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="cfi-info-box" style="margin-top: 1.5rem; padding: 1rem; background: var(--cfi-light); border-radius: var(--cfi-radius-sm);">
                <h4><i class="fas fa-info-circle"></i> <?php esc_html_e('Calculation Formula', 'chinemerem-foods'); ?></h4>
                <p><strong><?php esc_html_e('Cash Left', 'chinemerem-foods'); ?></strong> = Total Sales - Transfer/Card (Orders) - Transfer/Card (Cash Out) + Debtors Cash - Expenses + Old Cash - Cash to Bank</p>
            </div>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    $('#cfi-save-cash-to-bank').on('click', function() {
        const btn = $(this);
        const amount = parseFloat($('#cfi-cash-to-bank').val()) || 0;
        
        btn.prop('disabled', true);
        
        CFI.ajax.request('update_financial', {
            cash_to_bank: amount
        }).then(function(data) {
            CFI.toast.success(data.message);
            location.reload();
        }).catch(function(error) {
            CFI.toast.error(error);
            btn.prop('disabled', false);
        });
    });
});
</script>
