<?php
/**
 * Admin Panel Page Template
 * Simplified for admin users - only product management
 * Debtors and other features are super admin only
 */

if (!defined('ABSPATH')) {
    exit;
}

// Only admins can access this page
if (!CFI_Auth::is_cfi_admin()) {
    echo '<div class="cfi-main"><div class="cfi-container"><div class="cfi-glass" style="text-align: center; padding: 3rem;"><i class="fa-solid fa-lock" style="font-size: 3rem; color: var(--cfi-danger); margin-bottom: 1rem;"></i><h2>' . esc_html__('Access Denied', 'chinemerem-foods') . '</h2><p>' . esc_html__('You do not have permission to access this page.', 'chinemerem-foods') . '</p></div></div></div>';
    return;
}

$products = CFI_Products::get_all('');
$is_super_admin = CFI_Auth::is_super_admin();
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fa-solid fa-gear"></i>
                <?php esc_html_e('Admin Panel', 'chinemerem-foods'); ?>
            </h1>
        </div>
        
        <!-- Products Section - Available to all admins -->
        <div class="cfi-admin-section cfi-glass" style="margin-bottom: 1.5rem;">
            <h3><i class="fa-solid fa-box"></i> <?php esc_html_e('Products Management', 'chinemerem-foods'); ?></h3>
            
            <form id="cfi-admin-add-product" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end; margin-bottom: 1.5rem; padding: 1rem; background: var(--cfi-light); border-radius: var(--cfi-radius-sm);">
                <div class="cfi-form-group" style="flex: 2; min-width: 180px; margin: 0;">
                    <label for="prod-name"><?php esc_html_e('Product Name', 'chinemerem-foods'); ?></label>
                    <input type="text" id="prod-name" name="name" class="cfi-input" placeholder="Enter product name" required>
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 120px; margin: 0;">
                    <label for="prod-price"><?php esc_html_e('Price (₦)', 'chinemerem-foods'); ?></label>
                    <input type="number" id="prod-price" name="price" class="cfi-input" step="0.01" min="0" placeholder="0.00" required>
                </div>
                <button type="submit" class="cfi-btn cfi-btn-success">
                    <i class="fa-solid fa-plus"></i>
                    <?php esc_html_e('Add Product', 'chinemerem-foods'); ?>
                </button>
            </form>
            
            <div class="cfi-table-wrapper">
                <table class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Name', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Price', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Status', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Actions', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)) : ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">
                                <i class="fa-solid fa-box" style="font-size: 2rem; color: var(--cfi-gray); margin-bottom: 1rem;"></i>
                                <p><?php esc_html_e('No products yet. Add your first product above.', 'chinemerem-foods'); ?></p>
                            </td>
                        </tr>
                        <?php else : ?>
                        <?php foreach ($products as $product) : ?>
                        <tr data-id="<?php echo esc_attr($product->id); ?>">
                            <td data-label="<?php esc_attr_e('Name', 'chinemerem-foods'); ?>"><?php echo esc_html($product->name); ?></td>
                            <td data-label="<?php esc_attr_e('Price', 'chinemerem-foods'); ?>"><?php echo esc_html(CFI_Products::format_price($product->price)); ?></td>
                            <td data-label="<?php esc_attr_e('Status', 'chinemerem-foods'); ?>">
                                <span class="cfi-badge <?php echo $product->status === 'active' ? 'cfi-badge-success' : 'cfi-badge-danger'; ?>">
                                    <?php echo esc_html(ucfirst($product->status)); ?>
                                </span>
                            </td>
                            <td data-label="<?php esc_attr_e('Actions', 'chinemerem-foods'); ?>">
                                <button class="cfi-btn cfi-btn-outline cfi-btn-sm cfi-edit-product" data-id="<?php echo esc_attr($product->id); ?>" data-name="<?php echo esc_attr($product->name); ?>" data-price="<?php echo esc_attr($product->price); ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="cfi-btn cfi-btn-danger cfi-btn-sm cfi-delete-product" data-id="<?php echo esc_attr($product->id); ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if ($is_super_admin) : ?>
        <!-- Debtors Section - Super Admin Only -->
        <div class="cfi-admin-section cfi-glass" style="margin-bottom: 1.5rem;">
            <h3><i class="fa-solid fa-user-tag"></i> <?php esc_html_e('Debtors Management', 'chinemerem-foods'); ?> <span style="font-size: 0.75rem; color: var(--cfi-warning);">(Super Admin)</span></h3>
            
            <form id="cfi-admin-add-debtor" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end; margin-bottom: 1.5rem; padding: 1rem; background: var(--cfi-light); border-radius: var(--cfi-radius-sm);">
                <div class="cfi-form-group" style="flex: 1; min-width: 150px; margin: 0;">
                    <label for="debtor-name"><?php esc_html_e('Name', 'chinemerem-foods'); ?></label>
                    <input type="text" id="debtor-name" name="name" class="cfi-input" required>
                </div>
                <div class="cfi-form-group" style="flex: 1; min-width: 120px; margin: 0;">
                    <label for="debtor-phone"><?php esc_html_e('Phone', 'chinemerem-foods'); ?></label>
                    <input type="text" id="debtor-phone" name="phone" class="cfi-input">
                </div>
                <button type="submit" class="cfi-btn cfi-btn-success">
                    <i class="fa-solid fa-user-plus"></i>
                    <?php esc_html_e('Add Debtor', 'chinemerem-foods'); ?>
                </button>
            </form>
            
            <?php $debtors = CFI_Debtors::get_all(''); ?>
            <div class="cfi-table-wrapper">
                <table class="cfi-table cfi-table-responsive">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Name', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Phone', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Debt', 'chinemerem-foods'); ?></th>
                            <th><?php esc_html_e('Actions', 'chinemerem-foods'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($debtors as $debtor) : ?>
                        <tr data-id="<?php echo esc_attr($debtor->id); ?>">
                            <td data-label="<?php esc_attr_e('Name', 'chinemerem-foods'); ?>"><?php echo esc_html($debtor->name); ?></td>
                            <td data-label="<?php esc_attr_e('Phone', 'chinemerem-foods'); ?>"><?php echo esc_html($debtor->phone); ?></td>
                            <td data-label="<?php esc_attr_e('Debt', 'chinemerem-foods'); ?>"><?php echo esc_html(CFI_Products::format_price($debtor->total_debt)); ?></td>
                            <td data-label="<?php esc_attr_e('Actions', 'chinemerem-foods'); ?>">
                                <button class="cfi-btn cfi-btn-danger cfi-btn-sm cfi-delete-debtor" data-id="<?php echo esc_attr($debtor->id); ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Backup Section - Super Admin Only -->
        <div class="cfi-admin-section cfi-glass" style="margin-bottom: 1.5rem;">
            <h3><i class="fa-solid fa-database"></i> <?php esc_html_e('Backup & Restore', 'chinemerem-foods'); ?> <span style="font-size: 0.75rem; color: var(--cfi-warning);">(Super Admin)</span></h3>
            
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                <button type="button" id="cfi-create-full-backup" class="cfi-btn cfi-btn-primary">
                    <i class="fa-solid fa-download"></i>
                    <?php esc_html_e('Create Full Backup', 'chinemerem-foods'); ?>
                </button>
                
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="file" id="cfi-restore-file" accept=".json" class="cfi-input" style="max-width: 250px;">
                    <button type="button" id="cfi-restore-backup" class="cfi-btn cfi-btn-warning">
                        <i class="fa-solid fa-upload"></i>
                        <?php esc_html_e('Restore', 'chinemerem-foods'); ?>
                    </button>
                </div>
            </div>
            
            <p class="cfi-info-box" style="padding: 1rem; background: var(--cfi-light); border-radius: var(--cfi-radius-sm);">
                <i class="fa-solid fa-circle-info"></i>
                <?php esc_html_e('Daily automatic backups are created at 11:00 PM.', 'chinemerem-foods'); ?>
            </p>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- Edit Product Modal -->
<div id="cfi-edit-product-modal" class="cfi-modal" style="display: none;">
    <div class="cfi-modal-overlay"></div>
    <div class="cfi-modal-content cfi-glass">
        <h3><i class="fa-solid fa-pen"></i> <?php esc_html_e('Edit Product', 'chinemerem-foods'); ?></h3>
        <form id="cfi-edit-product-form">
            <input type="hidden" name="id" id="edit-prod-id">
            <div class="cfi-form-group">
                <label for="edit-prod-name"><?php esc_html_e('Product Name', 'chinemerem-foods'); ?></label>
                <input type="text" id="edit-prod-name" name="name" class="cfi-input" required>
            </div>
            <div class="cfi-form-group">
                <label for="edit-prod-price"><?php esc_html_e('Price (₦)', 'chinemerem-foods'); ?></label>
                <input type="number" id="edit-prod-price" name="price" class="cfi-input" step="0.01" min="0" required>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" class="cfi-btn cfi-btn-outline cfi-modal-close"><?php esc_html_e('Cancel', 'chinemerem-foods'); ?></button>
                <button type="submit" class="cfi-btn cfi-btn-success"><?php esc_html_e('Save Changes', 'chinemerem-foods'); ?></button>
            </div>
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Debug - log to console if cfiData exists
    console.log('Admin Panel Loaded');
    console.log('cfiData available:', typeof cfiData !== 'undefined');
    if (typeof cfiData !== 'undefined') {
        console.log('AJAX URL:', cfiData.ajaxUrl);
    }
    
    // Ensure CFI.toast exists
    if (typeof CFI === 'undefined') {
        window.CFI = {};
    }
    if (!CFI.toast) {
        CFI.toast = {
            success: function(msg) { alert('✓ ' + msg); },
            error: function(msg) { alert('✗ Error: ' + msg); },
            warning: function(msg) { alert('⚠ ' + msg); },
            info: function(msg) { alert('ℹ ' + msg); }
        };
    }
    
    // Helper function to get AJAX URL
    function getAjaxUrl() {
        if (typeof cfiData !== 'undefined' && cfiData.ajaxUrl) {
            return cfiData.ajaxUrl;
        }
        // Fallback to WordPress default
        return '<?php echo admin_url('admin-ajax.php'); ?>';
    }
    
    // Helper function to get nonce
    function getNonce() {
        if (typeof cfiData !== 'undefined' && cfiData.nonce) {
            return cfiData.nonce;
        }
        // Fallback - generate inline nonce
        return '<?php echo wp_create_nonce('cfi_nonce'); ?>';
    }
    
    // Add Product
    $('#cfi-admin-add-product').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var originalText = btn.html();
        var productName = form.find('[name="name"]').val().trim();
        var productPrice = parseFloat(form.find('[name="price"]').val()) || 0;
        
        if (!productName) {
            CFI.toast.error('Please enter a product name');
            return;
        }
        
        if (productPrice <= 0) {
            CFI.toast.error('Please enter a valid price');
            return;
        }
        
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Adding...');
        
        var ajaxUrl = getAjaxUrl();
        var nonce = getNonce();
        
        console.log('Submitting product:', productName, productPrice);
        console.log('AJAX URL:', ajaxUrl);
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'cfi_add_product',
                nonce: nonce,
                name: productName,
                price: productPrice
            },
            success: function(response) {
                console.log('Response:', response);
                if (response && response.success) {
                    CFI.toast.success(response.data && response.data.message ? response.data.message : 'Product added successfully');
                    form.find('[name="name"]').val('');
                    form.find('[name="price"]').val('');
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                } else {
                    var errMsg = (response && response.data && response.data.message) ? response.data.message : 'Failed to add product. Check console for details.';
                    console.error('Server error:', response);
                    CFI.toast.error(errMsg);
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText);
                CFI.toast.error('Network error: ' + (error || 'Please try again. Check console for details.'));
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Edit Product - Open Modal
    $(document).on('click', '.cfi-edit-product', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var price = $(this).data('price');
        
        $('#edit-prod-id').val(id);
        $('#edit-prod-name').val(name);
        $('#edit-prod-price').val(price);
        $('#cfi-edit-product-modal').fadeIn(200);
    });
    
    // Close Modal
    $(document).on('click', '.cfi-modal-close, .cfi-modal-overlay', function() {
        $('.cfi-modal').fadeOut(200);
    });
    
    // Save Product Edit
    $('#cfi-edit-product-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var originalText = btn.html();
        
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: getAjaxUrl(),
            type: 'POST',
            data: {
                action: 'cfi_update_product',
                nonce: getNonce(),
                id: form.find('[name="id"]').val(),
                name: form.find('[name="name"]').val(),
                price: form.find('[name="price"]').val()
            },
            success: function(response) {
                if (response.success) {
                    CFI.toast.success('Product updated successfully');
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                } else {
                    CFI.toast.error(response.data.message || 'Failed to update product');
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                CFI.toast.error('Network error');
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Delete Product
    $(document).on('click', '.cfi-delete-product', function() {
        if (!confirm('Are you sure you want to delete this product?')) return;
        var id = $(this).data('id');
        var row = $(this).closest('tr');
        
        $.ajax({
            url: getAjaxUrl(),
            type: 'POST',
            data: {
                action: 'cfi_delete_product',
                nonce: getNonce(),
                id: id
            },
            success: function(response) {
                if (response.success) {
                    row.fadeOut(300, function() { $(this).remove(); });
                    CFI.toast.success('Product deleted');
                } else {
                    CFI.toast.error(response.data.message || 'Failed to delete');
                }
            },
            error: function() {
                CFI.toast.error('Network error');
            }
        });
    });
    
    <?php if ($is_super_admin) : ?>
    // Add Debtor
    $('#cfi-admin-add-debtor').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var originalText = btn.html();
        var debtorName = form.find('[name="name"]').val().trim();
        var debtorPhone = form.find('[name="phone"]').val().trim();
        
        if (!debtorName) {
            CFI.toast.error('Please enter debtor name');
            return;
        }
        
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Adding...');
        
        $.ajax({
            url: getAjaxUrl(),
            type: 'POST',
            data: {
                action: 'cfi_add_debtor',
                nonce: getNonce(),
                name: debtorName,
                phone: debtorPhone
            },
            success: function(response) {
                console.log('Debtor Response:', response);
                if (response && response.success) {
                    CFI.toast.success(response.data && response.data.message ? response.data.message : 'Debtor added successfully');
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                } else {
                    var errMsg = (response && response.data && response.data.message) ? response.data.message : 'Failed to add debtor';
                    console.error('Server error:', response);
                    CFI.toast.error(errMsg);
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText);
                CFI.toast.error('Network error: ' + (error || 'Please try again'));
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Delete Debtor
    $(document).on('click', '.cfi-delete-debtor', function() {
        if (!confirm('Delete this debtor?')) return;
        var id = $(this).data('id');
        var row = $(this).closest('tr');
        
        $.ajax({
            url: getAjaxUrl(),
            type: 'POST',
            data: {
                action: 'cfi_delete_debtor',
                nonce: getNonce(),
                id: id
            },
            success: function(response) {
                if (response.success) {
                    row.fadeOut();
                    CFI.toast.success('Deleted');
                }
            }
        });
    });
    
    // Create Backup
    $('#cfi-create-full-backup').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Creating...');
        
        $.ajax({
            url: getAjaxUrl(),
            type: 'POST',
            data: {
                action: 'cfi_download_backup',
                nonce: getNonce()
            },
            success: function(response) {
                if (response.success && response.data.file_url) {
                    window.open(response.data.file_url);
                    CFI.toast.success('Backup created');
                }
                btn.prop('disabled', false).html('<i class="fa-solid fa-download"></i> Create Full Backup');
            }
        });
    });
    <?php endif; ?>
});
</script>

<style>
/* Modal Styles */
.cfi-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cfi-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 25, 67, 0.5);
}

.cfi-modal-content {
    position: relative;
    max-width: 400px;
    width: 90%;
    padding: 2rem;
}

.cfi-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.cfi-badge-success {
    background: #dcfce7;
    color: #166534;
}

.cfi-badge-danger {
    background: #fee2e2;
    color: #991b1b;
}
</style>
