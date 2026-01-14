<?php
/**
 * Expenses Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$today = current_time('Y-m-d');
$expenses_data = CFI_Expenses::get_by_date($today);
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-file-invoice-dollar"></i>
                <?php esc_html_e('Expenses Record', 'chinemerem-foods'); ?>
            </h1>
            <div class="cfi-page-actions">
                <?php $expenses_history = get_page_by_path('cfi-expenses-history'); ?>
                <?php if ($expenses_history) : ?>
                <a href="<?php echo esc_url(get_permalink($expenses_history->ID)); ?>" class="cfi-btn cfi-btn-outline cfi-btn-sm">
                    <i class="fas fa-history"></i>
                    <?php esc_html_e('View History', 'chinemerem-foods'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="cfi-glass" style="margin-bottom: 1.5rem;">
            <h3><?php esc_html_e('Add Expense', 'chinemerem-foods'); ?></h3>
            <form id="cfi-expense-form" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
                <div class="cfi-form-group" style="flex: 2; min-width: 250px; margin: 0;">
                    <label for="expense-description"><?php esc_html_e('Description', 'chinemerem-foods'); ?></label>
                    <input type="text" id="expense-description" name="description" class="cfi-input" placeholder="<?php esc_attr_e('Enter expense description...', 'chinemerem-foods'); ?>" required>
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 150px; margin: 0;">
                    <label for="expense-amount"><?php esc_html_e('Amount (₦)', 'chinemerem-foods'); ?></label>
                    <input type="number" id="expense-amount" name="amount" class="cfi-input" min="0" step="0.01" required>
                </div>
                <button type="submit" class="cfi-btn cfi-btn-primary">
                    <i class="fas fa-plus"></i>
                    <?php esc_html_e('Add Expense', 'chinemerem-foods'); ?>
                </button>
            </form>
        </div>
        
        <div class="cfi-glass">
            <h3><?php esc_html_e('Today\'s Expenses', 'chinemerem-foods'); ?></h3>
            
            <div class="cfi-table-wrapper">
                <table id="cfi-expenses-table" class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Time', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Description', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Amount (₦)', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Staff', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($expenses_data['expenses'])) : ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">
                                <?php esc_html_e('No expenses recorded today', 'chinemerem-foods'); ?>
                            </td>
                        </tr>
                        <?php else : ?>
                        <?php foreach ($expenses_data['expenses'] as $expense) : ?>
                        <tr>
                            <td data-label="<?php esc_attr_e('Time', 'chinemerem-foods'); ?>"><?php echo esc_html(substr($expense->expense_time, 0, 5)); ?></td>
                            <td data-label="<?php esc_attr_e('Description', 'chinemerem-foods'); ?>"><?php echo esc_html($expense->description); ?></td>
                            <td data-label="<?php esc_attr_e('Amount', 'chinemerem-foods'); ?>"><?php echo esc_html(CFI_Products::format_price($expense->amount)); ?></td>
                            <td data-label="<?php esc_attr_e('Staff', 'chinemerem-foods'); ?>"><?php echo esc_html($expense->staff_name); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--cfi-primary); color: var(--cfi-white);">
                            <td colspan="2"><strong><?php esc_html_e('Total', 'chinemerem-foods'); ?></strong></td>
                            <td colspan="2"><strong id="cfi-expenses-total"><?php echo esc_html(CFI_Products::format_price($expenses_data['total'])); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    $('#cfi-expense-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        
        btn.prop('disabled', true).text('Adding...');
        
        CFI.ajax.request('add_expense', {
            description: form.find('[name="description"]').val(),
            amount: form.find('[name="amount"]').val()
        }).then(function(data) {
            CFI.toast.success(data.message);
            location.reload();
        }).catch(function(error) {
            CFI.toast.error(error);
            btn.prop('disabled', false).html('<i class="fas fa-plus"></i> Add Expense');
        });
    });
});
</script>
