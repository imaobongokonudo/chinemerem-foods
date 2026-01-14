<?php
/**
 * Debtors History Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-history"></i>
                <?php esc_html_e('Debtors History', 'chinemerem-foods'); ?>
            </h1>
            <div class="cfi-page-actions">
                <?php $debtors_record = get_page_by_path('cfi-debtors-record'); ?>
                <?php if ($debtors_record) : ?>
                <a href="<?php echo esc_url(get_permalink($debtors_record->ID)); ?>" class="cfi-btn cfi-btn-primary cfi-btn-sm">
                    <i class="fas fa-user-clock"></i>
                    <?php esc_html_e('Current Debtors', 'chinemerem-foods'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="cfi-filters cfi-glass">
            <div class="cfi-filter-group">
                <label for="cfi-debtor-filter"><?php esc_html_e('Debtor:', 'chinemerem-foods'); ?></label>
                <select id="cfi-debtor-filter" class="cfi-select">
                    <option value=""><?php esc_html_e('All Debtors', 'chinemerem-foods'); ?></option>
                    <?php
                    $debtors = CFI_Debtors::get_all();
                    foreach ($debtors as $debtor) :
                    ?>
                    <option value="<?php echo esc_attr($debtor->id); ?>"><?php echo esc_html($debtor->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="button" id="cfi-load-history" class="cfi-btn cfi-btn-primary cfi-btn-sm">
                <i class="fas fa-search"></i>
                <?php esc_html_e('Search', 'chinemerem-foods'); ?>
            </button>
        </div>
        
        <div id="cfi-debtors-history" class="cfi-glass">
            <div class="cfi-table-wrapper">
                <table id="cfi-history-table" class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Date', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Time', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Debtor', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Type', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Amount', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Balance Before', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Balance After', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Payment Method', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Staff', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    function loadHistory() {
        const debtorId = $('#cfi-debtor-filter').val();
        
        CFI.ajax.request('get_debtor_history', {
            debtor_id: debtorId
        }).then(function(data) {
            const tbody = $('#cfi-history-table tbody');
            tbody.empty();
            
            if (!data.history || data.history.length === 0) {
                tbody.append('<tr><td colspan="9" style="text-align: center;"><?php esc_html_e('No transactions found', 'chinemerem-foods'); ?></td></tr>');
                return;
            }
            
            data.history.forEach(function(record) {
                const typeColor = record.transaction_type === 'order' ? 'var(--cfi-danger)' : 'var(--cfi-success)';
                const typeIcon = record.transaction_type === 'order' ? 'fa-cart-plus' : 'fa-money-check';
                
                tbody.append(`
                    <tr>
                        <td data-label="<?php esc_attr_e('Date', 'chinemerem-foods'); ?>">${record.transaction_date}</td>
                        <td data-label="<?php esc_attr_e('Time', 'chinemerem-foods'); ?>">${record.transaction_time}</td>
                        <td data-label="<?php esc_attr_e('Debtor', 'chinemerem-foods'); ?>">${record.debtor_name}</td>
                        <td data-label="<?php esc_attr_e('Type', 'chinemerem-foods'); ?>">
                            <span style="color: ${typeColor};">
                                <i class="fas ${typeIcon}"></i> ${record.transaction_type}
                            </span>
                        </td>
                        <td data-label="<?php esc_attr_e('Amount', 'chinemerem-foods'); ?>">${CFI.utils.formatCurrency(record.amount)}</td>
                        <td data-label="<?php esc_attr_e('Balance Before', 'chinemerem-foods'); ?>">${CFI.utils.formatCurrency(record.balance_before)}</td>
                        <td data-label="<?php esc_attr_e('Balance After', 'chinemerem-foods'); ?>">${CFI.utils.formatCurrency(record.balance_after)}</td>
                        <td data-label="<?php esc_attr_e('Payment Method', 'chinemerem-foods'); ?>">${record.payment_method || '-'}</td>
                        <td data-label="<?php esc_attr_e('Staff', 'chinemerem-foods'); ?>">${record.staff_name || '-'}</td>
                    </tr>
                `);
            });
        }).catch(function(error) {
            CFI.toast.error(error);
        });
    }
    
    $('#cfi-load-history').on('click', function() {
        loadHistory();
    });
    
    loadHistory();
});
</script>
