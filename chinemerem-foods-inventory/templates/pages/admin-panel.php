<?php
/**
 * Admin Panel Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Only admins can access this page
if (!CFI_Auth::is_cfi_admin()) {
    echo '<div class="cfi-main"><div class="cfi-container"><div class="cfi-glass" style="text-align: center; padding: 3rem;"><i class="fas fa-lock" style="font-size: 3rem; color: var(--cfi-danger); margin-bottom: 1rem;"></i><h2>' . esc_html__('Access Denied', 'chinemerem-foods') . '</h2><p>' . esc_html__('You do not have permission to access this page.', 'chinemerem-foods') . '</p></div></div></div>';
    return;
}

$products = CFI_Products::get_all('');
$debtors = CFI_Debtors::get_all('');
$is_super_admin = CFI_Auth::is_super_admin();
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-cogs"></i>
                <?php esc_html_e('Admin Panel', 'chinemerem-foods'); ?>
            </h1>
        </div>
        
        <!-- Products Section -->
        <div class="cfi-admin-section cfi-glass" style="margin-bottom: 1.5rem;">
            <h3><i class="fas fa-box"></i> <?php esc_html_e('Products Management', 'chinemerem-foods'); ?></h3>
            
            <form id="cfi-admin-add-product" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end; margin-bottom: 1.5rem; padding: 1rem; background: var(--cfi-light); border-radius: var(--cfi-radius-sm);">
                <div class="cfi-form-group" style="flex: 1; min-width: 150px; margin: 0;">
                    <label for="prod-name"><?php esc_html_e('Name', 'chinemerem-foods'); ?></label>
                    <input type="text" id="prod-name" name="name" class="cfi-input" required>
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 100px; margin: 0;">
                    <label for="prod-price"><?php esc_html_e('Price (₦)', 'chinemerem-foods'); ?></label>
                    <input type="number" id="prod-price" name="price" class="cfi-input" step="0.01" required>
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 100px; margin: 0;">
                    <label for="prod-unit"><?php esc_html_e('Unit', 'chinemerem-foods'); ?></label>
                    <input type="text" id="prod-unit" name="unit" class="cfi-input" value="unit">
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 100px; margin: 0;">
                    <label for="prod-category"><?php esc_html_e('Category', 'chinemerem-foods'); ?></label>
                    <input type="text" id="prod-category" name="category" class="cfi-input">
                </div>
                <button type="submit" class="cfi-btn cfi-btn-success">
                    <i class="fas fa-plus"></i>
                    <?php esc_html_e('Add', 'chinemerem-foods'); ?>
                </button>
            </form>
            
            <div class="cfi-table-wrapper">
                <table class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Name', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Price', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Unit', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Status', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Actions', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                        <tr data-id="<?php echo esc_attr($product->id); ?>">
                            <td data-label="<?php esc_attr_e('Name', 'chinemerem-foods'); ?>"><?php echo esc_html($product->name); ?></td>
                            <td data-label="<?php esc_attr_e('Price', 'chinemerem-foods'); ?>"><?php echo esc_html(CFI_Products::format_price($product->price)); ?></td>
                            <td data-label="<?php esc_attr_e('Unit', 'chinemerem-foods'); ?>"><?php echo esc_html($product->unit); ?></td>
                            <td data-label="<?php esc_attr_e('Status', 'chinemerem-foods'); ?>"><?php echo esc_html($product->status); ?></td>
                            <td data-label="<?php esc_attr_e('Actions', 'chinemerem-foods'); ?>">
                                <button class="cfi-btn cfi-btn-outline cfi-btn-sm cfi-edit-product" data-id="<?php echo esc_attr($product->id); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="cfi-btn cfi-btn-danger cfi-btn-sm cfi-delete-product" data-id="<?php echo esc_attr($product->id); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Debtors Section -->
        <div class="cfi-admin-section cfi-glass" style="margin-bottom: 1.5rem;">
            <h3><i class="fas fa-user-clock"></i> <?php esc_html_e('Debtors Management', 'chinemerem-foods'); ?></h3>
            
            <form id="cfi-admin-add-debtor" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end; margin-bottom: 1.5rem; padding: 1rem; background: var(--cfi-light); border-radius: var(--cfi-radius-sm);">
                <div class="cfi-form-group" style="flex: 1; min-width: 150px; margin: 0;">
                    <label for="debtor-name"><?php esc_html_e('Name', 'chinemerem-foods'); ?></label>
                    <input type="text" id="debtor-name" name="name" class="cfi-input" required>
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 120px; margin: 0;">
                    <label for="debtor-phone"><?php esc_html_e('Phone', 'chinemerem-foods'); ?></label>
                    <input type="text" id="debtor-phone" name="phone" class="cfi-input">
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 150px; margin: 0;">
                    <label for="debtor-email"><?php esc_html_e('Email', 'chinemerem-foods'); ?></label>
                    <input type="email" id="debtor-email" name="email" class="cfi-input">
                </div>
                <button type="submit" class="cfi-btn cfi-btn-success">
                    <i class="fas fa-user-plus"></i>
                    <?php esc_html_e('Add', 'chinemerem-foods'); ?>
                </button>
            </form>
            
            <div class="cfi-table-wrapper">
                <table class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Name', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Phone', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Debt', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Status', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Actions', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($debtors as $debtor) : ?>
                        <tr data-id="<?php echo esc_attr($debtor->id); ?>">
                            <td data-label="<?php esc_attr_e('Name', 'chinemerem-foods'); ?>"><?php echo esc_html($debtor->name); ?></td>
                            <td data-label="<?php esc_attr_e('Phone', 'chinemerem-foods'); ?>"><?php echo esc_html($debtor->phone); ?></td>
                            <td data-label="<?php esc_attr_e('Debt', 'chinemerem-foods'); ?>"><?php echo esc_html(CFI_Products::format_price($debtor->total_debt)); ?></td>
                            <td data-label="<?php esc_attr_e('Status', 'chinemerem-foods'); ?>"><?php echo esc_html($debtor->status); ?></td>
                            <td data-label="<?php esc_attr_e('Actions', 'chinemerem-foods'); ?>">
                                <button class="cfi-btn cfi-btn-outline cfi-btn-sm cfi-edit-debtor" data-id="<?php echo esc_attr($debtor->id); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="cfi-btn cfi-btn-danger cfi-btn-sm cfi-delete-debtor" data-id="<?php echo esc_attr($debtor->id); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Backup Section (Super Admin Only) -->
        <?php if ($is_super_admin) : ?>
        <div class="cfi-admin-section cfi-glass" style="margin-bottom: 1.5rem;">
            <h3><i class="fas fa-database"></i> <?php esc_html_e('Backup & Restore', 'chinemerem-foods'); ?></h3>
            
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                <button type="button" id="cfi-create-full-backup" class="cfi-btn cfi-btn-primary">
                    <i class="fas fa-download"></i>
                    <?php esc_html_e('Create Full Backup', 'chinemerem-foods'); ?>
                </button>
                
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="file" id="cfi-restore-file" accept=".json" class="cfi-input" style="max-width: 250px;">
                    <button type="button" id="cfi-restore-backup" class="cfi-btn cfi-btn-warning">
                        <i class="fas fa-upload"></i>
                        <?php esc_html_e('Restore', 'chinemerem-foods'); ?>
                    </button>
                </div>
            </div>
            
            <p class="cfi-info-box" style="padding: 1rem; background: var(--cfi-light); border-radius: var(--cfi-radius-sm);">
                <i class="fas fa-info-circle"></i>
                <?php esc_html_e('Daily automatic backups are created at 11:00 PM. You can also create manual backups at any time.', 'chinemerem-foods'); ?>
            </p>
        </div>
        <?php endif; ?>
        
        <!-- System Tools Section -->
        <div class="cfi-admin-section cfi-glass">
            <h3><i class="fas fa-tools"></i> <?php esc_html_e('System Tools', 'chinemerem-foods'); ?></h3>
            
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                <button type="button" id="cfi-recreate-pages" class="cfi-btn cfi-btn-secondary">
                    <i class="fas fa-file-alt"></i>
                    <?php esc_html_e('Recreate Missing Pages', 'chinemerem-foods'); ?>
                </button>
            </div>
            
            <p class="cfi-info-box" style="padding: 1rem; background: var(--cfi-light); border-radius: var(--cfi-radius-sm);">
                <i class="fas fa-info-circle"></i>
                <?php esc_html_e('Use "Recreate Missing Pages" if some pages are missing from your WordPress site. This will create any missing pages with the correct shortcodes.', 'chinemerem-foods'); ?>
            </p>
            
            <!-- Page Shortcodes Reference -->
            <div style="margin-top: 1.5rem;">
                <h4 style="margin-bottom: 1rem;"><i class="fas fa-code"></i> <?php esc_html_e('Page Shortcodes Reference', 'chinemerem-foods'); ?></h4>
                <div class="cfi-table-wrapper">
                    <table class="cfi-table cfi-table-responsive">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Page', 'chinemerem-foods'); ?></th>
                                <th><?php esc_html_e('Shortcode', 'chinemerem-foods'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td data-label="Page"><?php esc_html_e('Login', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="login"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Dashboard/Home', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="home"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Take Order', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="take-order"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Order History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="order-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Transfer History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="transfer-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Stock Record', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="stock-record"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Stock History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="stock-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Packing Store', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="packing-store"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Packing History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="packing-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Debtors Record', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="debtors-record"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Debtors History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="debtors-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Expenses', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="expenses"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Expenses History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="expenses-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Import Record', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="import-record"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Import History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="import-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Not Supplied', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="not-supplied"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Not Supplied History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="not-supplied-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Supplied Today', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="supplied-today"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Supplied Today History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="supplied-today-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Order Product Summary', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="order-product-summary"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Credit Order Summary', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="credit-order-summary"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Cash Out', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="cash-out"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Cash Out History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="cash-out-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Financial Summary', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="financial-summary"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Financial History', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="financial-history"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Reconciliation', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="reconciliation"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Admin Panel', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="admin-panel"]</code></td></tr>
                            <tr><td data-label="Page"><?php esc_html_e('Profile', 'chinemerem-foods'); ?></td><td data-label="Shortcode"><code>[cfi_page template="profile"]</code></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    // Add Product
    $('#cfi-admin-add-product').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        
        btn.prop('disabled', true);
        
        CFI.ajax.request('add_product', {
            name: form.find('[name="name"]').val(),
            price: form.find('[name="price"]').val(),
            unit: form.find('[name="unit"]').val(),
            category: form.find('[name="category"]').val()
        }).then(function(data) {
            CFI.toast.success(data.message);
            location.reload();
        }).catch(function(error) {
            CFI.toast.error(error);
            btn.prop('disabled', false);
        });
    });
    
    // Add Debtor
    $('#cfi-admin-add-debtor').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        
        btn.prop('disabled', true);
        
        CFI.ajax.request('add_debtor', {
            name: form.find('[name="name"]').val(),
            phone: form.find('[name="phone"]').val(),
            email: form.find('[name="email"]').val()
        }).then(function(data) {
            CFI.toast.success(data.message);
            location.reload();
        }).catch(function(error) {
            CFI.toast.error(error);
            btn.prop('disabled', false);
        });
    });
    
    // Delete Product
    $(document).on('click', '.cfi-delete-product', function() {
        if (!confirm('Are you sure you want to delete this product?')) return;
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        
        CFI.ajax.request('delete_product', { id: id })
            .then(function() {
                row.fadeOut();
                CFI.toast.success('Product deleted');
            })
            .catch(function(error) {
                CFI.toast.error(error);
            });
    });
    
    // Delete Debtor
    $(document).on('click', '.cfi-delete-debtor', function() {
        if (!confirm('Are you sure you want to delete this debtor?')) return;
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        
        CFI.ajax.request('delete_debtor', { id: id })
            .then(function() {
                row.fadeOut();
                CFI.toast.success('Debtor deleted');
            })
            .catch(function(error) {
                CFI.toast.error(error);
            });
    });
    
    // Create Backup
    $('#cfi-create-full-backup').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).text('Creating...');
        
        CFI.ajax.request('download_backup', {})
            .then(function(data) {
                CFI.toast.success(data.message);
                if (data.file_url) {
                    window.open(data.file_url);
                }
                btn.prop('disabled', false).html('<i class="fas fa-download"></i> Create Full Backup');
            })
            .catch(function(error) {
                CFI.toast.error(error);
                btn.prop('disabled', false).html('<i class="fas fa-download"></i> Create Full Backup');
            });
    });
    
    // Recreate Pages
    $('#cfi-recreate-pages').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating Pages...');
        
        CFI.ajax.request('recreate_pages', {})
            .then(function(data) {
                CFI.toast.success(data.message);
                btn.prop('disabled', false).html('<i class="fas fa-file-alt"></i> Recreate Missing Pages');
                if (data.created > 0) {
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            })
            .catch(function(error) {
                CFI.toast.error(error);
                btn.prop('disabled', false).html('<i class="fas fa-file-alt"></i> Recreate Missing Pages');
            });
    });
});
</script>
