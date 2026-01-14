<?php
/**
 * Debtors Record Page Template - REBUILT FROM SCRATCH
 * Uses direct form POST submissions for reliability
 */

if (!defined('ABSPATH')) {
    exit;
}

// Ensure database tables exist
CFI_Database::create_tables();

$is_admin = CFI_Auth::is_cfi_admin();
$message = '';
$message_type = '';

// Process Take Order Form
if (isset($_POST['cfi_debtor_order_submit']) && wp_verify_nonce($_POST['cfi_debtor_order_nonce'], 'cfi_debtor_order')) {
    $debtor_id = intval($_POST['debtor_id']);
    $items = isset($_POST['order_items']) ? $_POST['order_items'] : array();
    
    if (empty($items)) {
        $message = 'Please add at least one item to the order';
        $message_type = 'error';
    } else {
        global $wpdb;
        $debtor = CFI_Debtors::get($debtor_id);
        
        if ($debtor) {
            $total_amount = 0;
            $order_items = array();
            
            foreach ($items as $item) {
                $product_id = intval($item['product_id']);
                $quantity = floatval($item['quantity']);
                $discount = floatval($item['discount']);
                
                if ($quantity > 0 && $product_id > 0) {
                    $product = CFI_Products::get($product_id);
                    if ($product) {
                        $item_total = ($product->price * $quantity) - $discount;
                        $total_amount += $item_total;
                        $order_items[] = array(
                            'product_id' => $product_id,
                            'product_name' => $product->name,
                            'price' => $product->price,
                            'quantity' => $quantity,
                            'discount' => $discount,
                            'total' => $item_total
                        );
                    }
                }
            }
            
            if ($total_amount > 0) {
                // Create order
                $orders_table = $wpdb->prefix . 'cfi_orders';
                $order_number = 'ORD-' . date('Ymd') . '-' . substr(uniqid(), -6);
                
                $wpdb->insert(
                    $orders_table,
                    array(
                        'order_number' => $order_number,
                        'order_type' => 'credit',
                        'debtor_id' => $debtor_id,
                        'items' => json_encode($order_items),
                        'subtotal' => $total_amount,
                        'total_discount' => 0,
                        'grand_total' => $total_amount,
                        'payment_method' => 'credit',
                        'transfer_amount' => 0,
                        'cash_amount' => 0,
                        'bank_name' => '',
                        'staff_id' => get_current_user_id(),
                        'order_date' => current_time('Y-m-d'),
                        'order_time' => current_time('H:i:s'),
                        'status' => 'completed'
                    ),
                    array('%s', '%s', '%d', '%s', '%f', '%f', '%f', '%s', '%f', '%f', '%s', '%d', '%s', '%s', '%s')
                );
                
                $order_id = $wpdb->insert_id;
                
                // Update debtor balance
                $balance_before = $debtor->total_debt;
                $new_balance = $balance_before + $total_amount;
                
                $debtors_table = $wpdb->prefix . 'cfi_debtors';
                $wpdb->update(
                    $debtors_table,
                    array('total_debt' => $new_balance),
                    array('id' => $debtor_id),
                    array('%f'),
                    array('%d')
                );
                
                // Record transaction history
                $trans_table = $wpdb->prefix . 'cfi_debtor_transactions';
                $wpdb->insert(
                    $trans_table,
                    array(
                        'debtor_id' => $debtor_id,
                        'transaction_type' => 'order',
                        'order_id' => $order_id,
                        'amount' => $total_amount,
                        'balance_before' => $balance_before,
                        'balance_after' => $new_balance,
                        'description' => 'New order: ' . $order_number,
                        'staff_id' => get_current_user_id(),
                        'transaction_date' => current_time('Y-m-d'),
                        'transaction_time' => current_time('H:i:s')
                    ),
                    array('%d', '%s', '%d', '%f', '%f', '%f', '%s', '%d', '%s', '%s')
                );
                
                $message = 'Order added to ' . esc_html($debtor->name) . '\'s debt. New balance: ₦' . number_format($new_balance, 2);
                $message_type = 'success';
            } else {
                $message = 'Invalid order - no valid items';
                $message_type = 'error';
            }
        } else {
            $message = 'Debtor not found';
            $message_type = 'error';
        }
    }
}

// Process Clear Debt Form
if (isset($_POST['cfi_clear_debt_submit']) && wp_verify_nonce($_POST['cfi_clear_debt_nonce'], 'cfi_clear_debt')) {
    $debtor_id = intval($_POST['debtor_id']);
    $payment_method = sanitize_text_field($_POST['payment_method']);
    $transfer_amount = floatval($_POST['transfer_amount']);
    $cash_amount = floatval($_POST['cash_amount']);
    $home_amount = floatval($_POST['home_amount']);
    $bank_name = sanitize_text_field($_POST['bank_name']);
    
    $total_payment = $transfer_amount + $cash_amount + $home_amount;
    
    if ($total_payment <= 0) {
        $message = 'Please enter a valid payment amount';
        $message_type = 'error';
    } else {
        global $wpdb;
        $debtor = CFI_Debtors::get($debtor_id);
        
        if ($debtor) {
            if ($total_payment > $debtor->total_debt) {
                $message = 'Payment amount exceeds outstanding debt';
                $message_type = 'error';
            } else {
                $balance_before = $debtor->total_debt;
                $new_balance = $balance_before - $total_payment;
                
                // Update debtor balance
                $debtors_table = $wpdb->prefix . 'cfi_debtors';
                $wpdb->update(
                    $debtors_table,
                    array('total_debt' => $new_balance),
                    array('id' => $debtor_id),
                    array('%f'),
                    array('%d')
                );
                
                // Record transaction history
                $trans_table = $wpdb->prefix . 'cfi_debtor_transactions';
                $wpdb->insert(
                    $trans_table,
                    array(
                        'debtor_id' => $debtor_id,
                        'transaction_type' => 'payment',
                        'amount' => $total_payment,
                        'payment_method' => $payment_method,
                        'bank_name' => $bank_name,
                        'transfer_amount' => $transfer_amount,
                        'cash_amount' => $cash_amount,
                        'home_calculation_amount' => $home_amount,
                        'balance_before' => $balance_before,
                        'balance_after' => $new_balance,
                        'description' => 'Debt payment received',
                        'staff_id' => get_current_user_id(),
                        'transaction_date' => current_time('Y-m-d'),
                        'transaction_time' => current_time('H:i:s')
                    ),
                    array('%d', '%s', '%f', '%s', '%s', '%f', '%f', '%f', '%f', '%f', '%s', '%d', '%s', '%s')
                );
                
                // Record transfer if applicable
                if ($transfer_amount > 0) {
                    $transfer_table = $wpdb->prefix . 'cfi_transfers';
                    $wpdb->insert(
                        $transfer_table,
                        array(
                            'source_type' => 'debtor',
                            'source_id' => $debtor_id,
                            'amount' => $transfer_amount,
                            'bank_name' => $bank_name,
                            'staff_id' => get_current_user_id(),
                            'transfer_date' => current_time('Y-m-d'),
                            'transfer_time' => current_time('H:i:s')
                        ),
                        array('%s', '%d', '%f', '%s', '%d', '%s', '%s')
                    );
                }
                
                $message = 'Payment of ₦' . number_format($total_payment, 2) . ' recorded for ' . esc_html($debtor->name) . '. New balance: ₦' . number_format($new_balance, 2);
                $message_type = 'success';
            }
        } else {
            $message = 'Debtor not found';
            $message_type = 'error';
        }
    }
}

// Get debtors and products
$debtors = CFI_Debtors::get_all();
$products = CFI_Products::get_all();

// Check for selected debtor and action
$selected_debtor_id = isset($_GET['debtor']) ? intval($_GET['debtor']) : 0;
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
$selected_debtor = $selected_debtor_id ? CFI_Debtors::get($selected_debtor_id) : null;
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .cfi-debtors-container { max-width: 1200px; margin: 0 auto; padding: 1rem; }
        .cfi-page-header { background: linear-gradient(135deg, #001943, #003366); color: white; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .cfi-page-header h1 { margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .cfi-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; font-size: 0.85rem; transition: all 0.3s; }
        .cfi-btn-primary { background: #001943; color: white; }
        .cfi-btn-success { background: #16a34a; color: white; }
        .cfi-btn-danger { background: #dc2626; color: white; }
        .cfi-btn-outline { background: transparent; border: 2px solid #001943; color: #001943; }
        .cfi-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,25,67,0.3); }
        .cfi-alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .cfi-alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .cfi-alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .cfi-cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
        .cfi-debtor-card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,25,67,0.1); border: 2px solid rgba(0,25,67,0.1); transition: all 0.3s; }
        .cfi-debtor-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,25,67,0.15); }
        .cfi-debtor-name { font-size: 1.2rem; color: #001943; margin: 0 0 0.5rem 0; }
        .cfi-debtor-phone { color: #64748b; font-size: 0.85rem; margin: 0 0 1rem 0; }
        .cfi-debtor-balance { font-size: 1.5rem; font-weight: 700; color: #dc2626; margin-bottom: 1rem; }
        .cfi-debtor-balance.zero { color: #16a34a; }
        .cfi-debtor-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .cfi-debtor-actions .cfi-btn { flex: 1; justify-content: center; }
        .cfi-glass { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,25,67,0.1); border: 2px solid rgba(0,25,67,0.1); margin-bottom: 1.5rem; }
        .cfi-form-group { margin-bottom: 1rem; }
        .cfi-form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #001943; font-size: 0.85rem; }
        .cfi-input { width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s; }
        .cfi-input:focus { outline: none; border-color: #001943; }
        .cfi-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        .cfi-table th { background: #001943; color: white; padding: 0.75rem 0.5rem; text-align: left; }
        .cfi-table td { padding: 0.5rem; border-bottom: 1px solid #e2e8f0; }
        .cfi-table input { width: 70px; padding: 0.4rem; border: 1px solid #e2e8f0; border-radius: 4px; text-align: center; }
        .cfi-table select { width: 100%; padding: 0.4rem; border: 1px solid #e2e8f0; border-radius: 4px; }
        .cfi-order-total { background: #001943; color: white; padding: 1rem; border-radius: 8px; margin-top: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .cfi-order-total-value { font-size: 1.5rem; font-weight: 700; }
        .cfi-payment-methods { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .cfi-payment-method { flex: 1; min-width: 120px; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .cfi-payment-method.selected { border-color: #001943; background: rgba(0,25,67,0.05); }
        .cfi-payment-method i { display: block; font-size: 1.5rem; color: #001943; margin-bottom: 0.5rem; }
        .cfi-bank-options { display: none; margin-bottom: 1rem; }
        .cfi-bank-option { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 0.5rem; cursor: pointer; }
        .cfi-bank-option input { width: auto; }
        .cfi-back-link { color: white; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; }
        .cfi-no-debtors { text-align: center; padding: 3rem; color: #64748b; }
        .cfi-no-debtors i { font-size: 3rem; margin-bottom: 1rem; display: block; }
        @media (max-width: 768px) {
            .cfi-page-header { flex-direction: column; text-align: center; }
            .cfi-debtor-actions { flex-direction: column; }
            .cfi-table { font-size: 0.75rem; }
            .cfi-table input { width: 50px; }
        }
    </style>
</head>
<body>
<main class="cfi-debtors-container">
    <!-- Page Header -->
    <div class="cfi-page-header">
        <?php if ($selected_debtor && ($action === 'order' || $action === 'pay')) : ?>
            <a href="<?php echo esc_url(remove_query_arg(array('debtor', 'action'))); ?>" class="cfi-back-link">
                <i class="fas fa-arrow-left"></i> Back to Debtors
            </a>
            <h1>
                <?php if ($action === 'order') : ?>
                    <i class="fas fa-cart-plus"></i> Take Order - <?php echo esc_html($selected_debtor->name); ?>
                <?php else : ?>
                    <i class="fas fa-money-check"></i> Clear Debt - <?php echo esc_html($selected_debtor->name); ?>
                <?php endif; ?>
            </h1>
        <?php else : ?>
            <h1><i class="fas fa-user-clock"></i> Debtors Record</h1>
            <a href="/debtors-history/" class="cfi-btn cfi-btn-outline" style="background: white;">
                <i class="fas fa-history"></i> View History
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Alert Messages -->
    <?php if ($message) : ?>
    <div class="cfi-alert cfi-alert-<?php echo $message_type; ?>">
        <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo esc_html($message); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($selected_debtor && $action === 'order') : ?>
    <!-- Take Order Form -->
    <div class="cfi-glass">
        <h3 style="color: #001943; margin-top: 0;"><i class="fas fa-shopping-cart"></i> Order Items</h3>
        <p><strong>Current Debt:</strong> <span style="color: #dc2626;">₦<?php echo number_format($selected_debtor->total_debt, 2); ?></span></p>
        
        <form method="POST" id="debtor-order-form">
            <?php wp_nonce_field('cfi_debtor_order', 'cfi_debtor_order_nonce'); ?>
            <input type="hidden" name="debtor_id" value="<?php echo esc_attr($selected_debtor->id); ?>">
            
            <div class="cfi-table-wrapper" style="overflow-x: auto;">
                <table class="cfi-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Item</th>
                            <th>Price (₦)</th>
                            <th>Qty</th>
                            <th>Disc (₦)</th>
                            <th>Total (₦)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $index => $product) : ?>
                        <tr class="order-row" data-price="<?php echo esc_attr($product->price); ?>">
                            <td>
                                <?php echo esc_html($product->name); ?>
                                <input type="hidden" name="order_items[<?php echo $index; ?>][product_id]" value="<?php echo esc_attr($product->id); ?>">
                            </td>
                            <td style="color: #001943; font-weight: 600;"><?php echo number_format($product->price, 2); ?></td>
                            <td>
                                <input type="number" name="order_items[<?php echo $index; ?>][quantity]" class="qty-input" value="0" min="0" step="0.5" onchange="calculateRowTotal(this)">
                            </td>
                            <td>
                                <input type="number" name="order_items[<?php echo $index; ?>][discount]" class="disc-input" value="0" min="0" step="0.01" onchange="calculateRowTotal(this)">
                            </td>
                            <td class="row-total" style="font-weight: 600; color: #001943;">0.00</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="cfi-order-total">
                <span>Grand Total:</span>
                <span class="cfi-order-total-value" id="grand-total">₦0.00</span>
            </div>
            
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="<?php echo esc_url(remove_query_arg(array('debtor', 'action'))); ?>" class="cfi-btn cfi-btn-outline">Cancel</a>
                <button type="submit" name="cfi_debtor_order_submit" class="cfi-btn cfi-btn-primary">
                    <i class="fas fa-plus"></i> Add to Debt
                </button>
            </div>
        </form>
    </div>
    
    <script>
    function calculateRowTotal(input) {
        var row = input.closest('.order-row');
        var price = parseFloat(row.dataset.price) || 0;
        var qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        var disc = parseFloat(row.querySelector('.disc-input').value) || 0;
        var total = (price * qty) - disc;
        if (total < 0) total = 0;
        row.querySelector('.row-total').textContent = total.toFixed(2);
        calculateGrandTotal();
    }
    
    function calculateGrandTotal() {
        var totals = document.querySelectorAll('.row-total');
        var grand = 0;
        totals.forEach(function(el) {
            grand += parseFloat(el.textContent) || 0;
        });
        document.getElementById('grand-total').textContent = '₦' + grand.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
    </script>
    
    <?php elseif ($selected_debtor && $action === 'pay') : ?>
    <!-- Clear Debt Form -->
    <div class="cfi-glass">
        <h3 style="color: #001943; margin-top: 0;"><i class="fas fa-money-check"></i> Record Payment</h3>
        <p><strong>Debtor:</strong> <?php echo esc_html($selected_debtor->name); ?></p>
        <p><strong>Outstanding Balance:</strong> <span style="color: #dc2626; font-size: 1.5rem; font-weight: 700;">₦<?php echo number_format($selected_debtor->total_debt, 2); ?></span></p>
        
        <?php if ($selected_debtor->total_debt <= 0) : ?>
        <div class="cfi-alert cfi-alert-success">
            <i class="fas fa-check-circle"></i> This debtor has no outstanding debt!
        </div>
        <a href="<?php echo esc_url(remove_query_arg(array('debtor', 'action'))); ?>" class="cfi-btn cfi-btn-primary">Back to Debtors</a>
        <?php else : ?>
        
        <form method="POST" id="clear-debt-form">
            <?php wp_nonce_field('cfi_clear_debt', 'cfi_clear_debt_nonce'); ?>
            <input type="hidden" name="debtor_id" value="<?php echo esc_attr($selected_debtor->id); ?>">
            <input type="hidden" name="payment_method" id="payment-method-input" value="transfer">
            
            <h4 style="color: #001943;">Select Payment Method</h4>
            <div class="cfi-payment-methods">
                <div class="cfi-payment-method selected" data-method="transfer" onclick="selectPaymentMethod(this)">
                    <i class="fas fa-credit-card"></i>
                    <span>Transfer/Card</span>
                </div>
                <div class="cfi-payment-method" data-method="cash" onclick="selectPaymentMethod(this)">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Cash</span>
                </div>
                <?php if ($is_admin) : ?>
                <div class="cfi-payment-method" data-method="home" onclick="selectPaymentMethod(this)">
                    <i class="fas fa-home"></i>
                    <span>Home Calc</span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="cfi-bank-options" id="bank-options">
                <h4 style="color: #001943;">Select Bank</h4>
                <label class="cfi-bank-option">
                    <input type="radio" name="bank_name" value="Moniepoint MFB" checked>
                    <span>Moniepoint MFB</span>
                </label>
                <label class="cfi-bank-option">
                    <input type="radio" name="bank_name" value="Access Bank PLC">
                    <span>Access Bank PLC</span>
                </label>
            </div>
            
            <div id="payment-amounts">
                <div class="cfi-form-group" id="transfer-group">
                    <label for="transfer_amount">Transfer Amount (₦)</label>
                    <input type="number" id="transfer_amount" name="transfer_amount" class="cfi-input" value="0" min="0" step="0.01" max="<?php echo esc_attr($selected_debtor->total_debt); ?>">
                </div>
                <div class="cfi-form-group" id="cash-group" style="display: none;">
                    <label for="cash_amount">Cash Amount (₦)</label>
                    <input type="number" id="cash_amount" name="cash_amount" class="cfi-input" value="0" min="0" step="0.01" max="<?php echo esc_attr($selected_debtor->total_debt); ?>">
                </div>
                <?php if ($is_admin) : ?>
                <div class="cfi-form-group" id="home-group" style="display: none;">
                    <label for="home_amount">Home Calculation Amount (₦)</label>
                    <input type="number" id="home_amount" name="home_amount" class="cfi-input" value="0" min="0" step="0.01" max="<?php echo esc_attr($selected_debtor->total_debt); ?>">
                </div>
                <?php else : ?>
                <input type="hidden" name="home_amount" value="0">
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="<?php echo esc_url(remove_query_arg(array('debtor', 'action'))); ?>" class="cfi-btn cfi-btn-outline">Cancel</a>
                <button type="submit" name="cfi_clear_debt_submit" class="cfi-btn cfi-btn-success">
                    <i class="fas fa-check"></i> Record Payment
                </button>
            </div>
        </form>
        
        <script>
        function selectPaymentMethod(el) {
            document.querySelectorAll('.cfi-payment-method').forEach(function(m) { m.classList.remove('selected'); });
            el.classList.add('selected');
            var method = el.dataset.method;
            document.getElementById('payment-method-input').value = method;
            
            document.getElementById('transfer-group').style.display = (method === 'transfer') ? 'block' : 'none';
            document.getElementById('cash-group').style.display = (method === 'cash') ? 'block' : 'none';
            var homeGroup = document.getElementById('home-group');
            if (homeGroup) homeGroup.style.display = (method === 'home') ? 'block' : 'none';
            document.getElementById('bank-options').style.display = (method === 'transfer') ? 'block' : 'none';
            
            // Reset amounts
            document.getElementById('transfer_amount').value = (method === 'transfer') ? document.getElementById('transfer_amount').max : 0;
            document.getElementById('cash_amount').value = (method === 'cash') ? document.getElementById('cash_amount').max : 0;
            var homeInput = document.getElementById('home_amount');
            if (homeInput) homeInput.value = (method === 'home') ? homeInput.max : 0;
        }
        // Initialize bank options visibility
        document.getElementById('bank-options').style.display = 'block';
        </script>
        <?php endif; ?>
    </div>
    
    <?php else : ?>
    <!-- Debtors List -->
    <?php if (empty($debtors)) : ?>
    <div class="cfi-glass cfi-no-debtors">
        <i class="fas fa-users"></i>
        <h3>No Debtors Found</h3>
        <p>Admin can add debtors from the Admin Panel.</p>
        <?php if ($is_admin) : ?>
        <a href="/admin-panel/" class="cfi-btn cfi-btn-primary" style="margin-top: 1rem;">
            <i class="fas fa-user-plus"></i> Add Debtors
        </a>
        <?php endif; ?>
    </div>
    <?php else : ?>
    <div class="cfi-cards-grid">
        <?php foreach ($debtors as $debtor) : ?>
        <div class="cfi-debtor-card">
            <h3 class="cfi-debtor-name"><?php echo esc_html($debtor->name); ?></h3>
            <?php if ($debtor->phone) : ?>
            <p class="cfi-debtor-phone"><i class="fas fa-phone"></i> <?php echo esc_html($debtor->phone); ?></p>
            <?php endif; ?>
            <div class="cfi-debtor-balance <?php echo $debtor->total_debt <= 0 ? 'zero' : ''; ?>">
                ₦<?php echo number_format($debtor->total_debt, 2); ?>
            </div>
            <div class="cfi-debtor-actions">
                <a href="<?php echo esc_url(add_query_arg(array('debtor' => $debtor->id, 'action' => 'order'))); ?>" class="cfi-btn cfi-btn-primary">
                    <i class="fas fa-cart-plus"></i> Order
                </a>
                <a href="<?php echo esc_url(add_query_arg(array('debtor' => $debtor->id, 'action' => 'pay'))); ?>" class="cfi-btn cfi-btn-success">
                    <i class="fas fa-money-check"></i> Clear Debt
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</main>
</body>
</html>
