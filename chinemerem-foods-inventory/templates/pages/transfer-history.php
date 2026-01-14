<?php
/**
 * Transfer History Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$today = current_time('Y-m-d');
$order_transfers = CFI_Financial::get_transfer_history($today, $today, 'order');
$cashout_transfers = CFI_Financial::get_transfer_history($today, $today, 'cashout');
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-exchange-alt"></i>
                <?php esc_html_e('Transfer History', 'chinemerem-foods'); ?>
            </h1>
        </div>
        
        <div class="cfi-filters cfi-glass">
            <div class="cfi-filter-group">
                <label for="cfi-transfer-start"><?php esc_html_e('From:', 'chinemerem-foods'); ?></label>
                <input type="date" id="cfi-transfer-start" class="cfi-input" value="<?php echo esc_attr($today); ?>">
            </div>
            <div class="cfi-filter-group">
                <label for="cfi-transfer-end"><?php esc_html_e('To:', 'chinemerem-foods'); ?></label>
                <input type="date" id="cfi-transfer-end" class="cfi-input" value="<?php echo esc_attr($today); ?>">
            </div>
            <button type="button" id="cfi-filter-transfers" class="cfi-btn cfi-btn-primary cfi-btn-sm">
                <i class="fas fa-filter"></i>
                <?php esc_html_e('Filter', 'chinemerem-foods'); ?>
            </button>
        </div>
        
        <div class="cfi-glass" style="margin-bottom: 1.5rem;">
            <h3><i class="fas fa-shopping-cart"></i> <?php esc_html_e('Transfers from Take Order', 'chinemerem-foods'); ?></h3>
            
            <div class="cfi-table-wrapper">
                <table class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Date', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Time', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Amount (₦)', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Bank', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Staff', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="cfi-order-transfers">
                        <?php if (empty($order_transfers)) : ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">
                                <?php esc_html_e('No transfer records found', 'chinemerem-foods'); ?>
                            </td>
                        </tr>
                        <?php else : ?>
                        <?php 
                        $total = 0;
                        foreach ($order_transfers as $transfer) : 
                            $total += $transfer->amount;
                        ?>
                        <tr>
                            <td data-label="<?php esc_attr_e('Date', 'chinemerem-foods'); ?>"><?php echo esc_html($transfer->transfer_date); ?></td>
                            <td data-label="<?php esc_attr_e('Time', 'chinemerem-foods'); ?>"><?php echo esc_html(substr($transfer->transfer_time, 0, 5)); ?></td>
                            <td data-label="<?php esc_attr_e('Amount', 'chinemerem-foods'); ?>"><?php echo esc_html(CFI_Products::format_price($transfer->amount)); ?></td>
                            <td data-label="<?php esc_attr_e('Bank', 'chinemerem-foods'); ?>"><?php echo esc_html($transfer->bank_name); ?></td>
                            <td data-label="<?php esc_attr_e('Staff', 'chinemerem-foods'); ?>"><?php echo esc_html($transfer->staff_name); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($order_transfers)) : ?>
                    <tfoot>
                        <tr style="background: var(--cfi-primary); color: var(--cfi-white);">
                            <td colspan="2"><strong><?php esc_html_e('Total', 'chinemerem-foods'); ?></strong></td>
                            <td colspan="3"><strong><?php echo esc_html(CFI_Products::format_price($total)); ?></strong></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        <div class="cfi-glass">
            <h3><i class="fas fa-money-bill-wave"></i> <?php esc_html_e('Transfers from Cash Out', 'chinemerem-foods'); ?></h3>
            
            <div class="cfi-table-wrapper">
                <table class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Date', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Time', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Amount (₦)', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Bank', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Staff', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="cfi-cashout-transfers">
                        <?php if (empty($cashout_transfers)) : ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">
                                <?php esc_html_e('No cash out records found', 'chinemerem-foods'); ?>
                            </td>
                        </tr>
                        <?php else : ?>
                        <?php 
                        $total_cashout = 0;
                        foreach ($cashout_transfers as $transfer) : 
                            $total_cashout += $transfer->amount;
                        ?>
                        <tr>
                            <td data-label="<?php esc_attr_e('Date', 'chinemerem-foods'); ?>"><?php echo esc_html($transfer->transfer_date); ?></td>
                            <td data-label="<?php esc_attr_e('Time', 'chinemerem-foods'); ?>"><?php echo esc_html(substr($transfer->transfer_time, 0, 5)); ?></td>
                            <td data-label="<?php esc_attr_e('Amount', 'chinemerem-foods'); ?>"><?php echo esc_html(CFI_Products::format_price($transfer->amount)); ?></td>
                            <td data-label="<?php esc_attr_e('Bank', 'chinemerem-foods'); ?>"><?php echo esc_html($transfer->bank_name); ?></td>
                            <td data-label="<?php esc_attr_e('Staff', 'chinemerem-foods'); ?>"><?php echo esc_html($transfer->staff_name); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($cashout_transfers)) : ?>
                    <tfoot>
                        <tr style="background: var(--cfi-warning); color: var(--cfi-white);">
                            <td colspan="2"><strong><?php esc_html_e('Total', 'chinemerem-foods'); ?></strong></td>
                            <td colspan="3"><strong><?php echo esc_html(CFI_Products::format_price($total_cashout)); ?></strong></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</main>
