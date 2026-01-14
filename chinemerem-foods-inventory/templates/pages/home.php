<?php
/**
 * Home/Dashboard Page Template - REBUILT FROM SCRATCH
 * Uses inline styles for reliability
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define cards with simple, reliable Font Awesome 6 Free icons
$cards = array(
    array(
        'slug' => 'take-order',
        'title' => 'Take Order',
        'icon' => 'fa fa-shopping-cart',
        'description' => 'Take new customer orders with cash or transfer payment',
    ),
    array(
        'slug' => 'stock-record',
        'title' => 'Stock Inventory',
        'icon' => 'fa fa-warehouse',
        'description' => 'View and manage daily stock inventory records',
    ),
    array(
        'slug' => 'packing-store',
        'title' => 'Packing Store',
        'icon' => 'fa fa-box',
        'description' => 'Manage packing store transfers and inventory',
    ),
    array(
        'slug' => 'debtors-record',
        'title' => 'Debtors Record',
        'icon' => 'fa fa-users',
        'description' => 'Manage debtor accounts and payments',
    ),
    array(
        'slug' => 'expenses',
        'title' => 'Expenses Record',
        'icon' => 'fa fa-receipt',
        'description' => 'Record and track daily business expenses',
    ),
    array(
        'slug' => 'import-record',
        'title' => 'Import Record',
        'icon' => 'fa fa-truck',
        'description' => 'Record product imports and deliveries',
    ),
    array(
        'slug' => 'not-supplied',
        'title' => 'Not Supplied Record',
        'icon' => 'fa fa-times-circle',
        'description' => 'Track orders that were not supplied',
    ),
    array(
        'slug' => 'supplied-today',
        'title' => 'Supplied Today',
        'icon' => 'fa fa-check-circle',
        'description' => 'Record products supplied today',
    ),
    array(
        'slug' => 'order-product-summary',
        'title' => 'Order Product Summary',
        'icon' => 'fa fa-chart-bar',
        'description' => 'View daily order product analytics',
    ),
    array(
        'slug' => 'credit-order-summary',
        'title' => 'Credit Order Summary',
        'icon' => 'fa fa-chart-pie',
        'description' => 'View credit order product analytics',
    ),
    array(
        'slug' => 'cash-out',
        'title' => 'Cash Out Record',
        'icon' => 'fa fa-money-bill',
        'description' => 'Record cash transfers to bank accounts',
    ),
    array(
        'slug' => 'transfer-history',
        'title' => 'Transfer History',
        'icon' => 'fa fa-exchange-alt',
        'description' => 'View all transfer/card payment history',
    ),
    array(
        'slug' => 'cash-out-history',
        'title' => 'Cash Out History',
        'icon' => 'fa fa-history',
        'description' => 'View cash out transfer history',
    ),
    array(
        'slug' => 'financial-summary',
        'title' => 'Financial Summary',
        'icon' => 'fa fa-calculator',
        'description' => 'View daily financial summary and reports',
    ),
    array(
        'slug' => 'reconciliation',
        'title' => 'Reconciliation Calendar',
        'icon' => 'fa fa-calendar-check',
        'description' => 'Track daily reconciliation status',
    ),
);

// Build URLs for each card
foreach ($cards as &$card) {
    $page = get_page_by_path($card['slug']);
    if ($page) {
        $card['url'] = get_permalink($page->ID);
    } else {
        $card['url'] = home_url('/' . $card['slug'] . '/');
    }
}
unset($card);
?>
<style>
.cfi-home-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}
.cfi-home-card {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(0, 25, 67, 0.15);
    border-radius: 16px;
    padding: 1.5rem;
    text-decoration: none;
    color: #001943;
    box-shadow: 0 4px 20px rgba(0, 25, 67, 0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.cfi-home-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0, 25, 67, 0.2);
    border-color: #001943;
}
.cfi-home-card-icon {
    width: 56px;
    height: 56px;
    background: #001943;
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}
.cfi-home-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #001943;
    margin-bottom: 0.5rem;
}
.cfi-home-card-desc {
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 1rem;
    line-height: 1.5;
}
.cfi-home-card-btn {
    background: #001943;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: auto;
}
</style>

<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title" style="margin-bottom: 2rem;">
            <h1 style="color: #001943; font-size: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fa fa-home"></i>
                Dashboard
            </h1>
            <span style="color: #64748b; font-size: 0.95rem;"><?php echo esc_html(current_time('l, F j, Y')); ?></span>
        </div>
        
        <div class="cfi-home-grid">
            <?php foreach ($cards as $card) : ?>
            <a href="<?php echo esc_url($card['url']); ?>" class="cfi-home-card">
                <div class="cfi-home-card-icon">
                    <i class="<?php echo esc_attr($card['icon']); ?>"></i>
                </div>
                <div class="cfi-home-card-title"><?php echo esc_html($card['title']); ?></div>
                <div class="cfi-home-card-desc"><?php echo esc_html($card['description']); ?></div>
                <span class="cfi-home-card-btn">
                    Open <i class="fa fa-arrow-right"></i>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
        
        <?php if (CFI_Auth::is_cfi_admin()) : ?>
        <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.95); border: 1px solid rgba(0,25,67,0.15); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,25,67,0.1);">
            <h3 style="color: #001943; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa fa-cog"></i> Quick Admin Actions
            </h3>
            <a href="<?php echo esc_url(home_url('/admin-panel/')); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #001943; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fa fa-plus"></i>
                Manage Products
            </a>
        </div>
        <?php endif; ?>
    </div>
</main>
