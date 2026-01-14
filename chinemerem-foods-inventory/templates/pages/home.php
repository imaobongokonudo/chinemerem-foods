<?php
/**
 * Home/Dashboard Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define cards directly - these will always display
$cards = array(
    array(
        'slug' => 'take-order',
        'title' => __('Take Order', 'chinemerem-foods'),
        'icon' => 'fa-cart-plus',
        'description' => __('Take new customer orders with cash or transfer payment', 'chinemerem-foods'),
        'color' => '#0a1628',
    ),
    array(
        'slug' => 'stock-record',
        'title' => __('Stock Inventory', 'chinemerem-foods'),
        'icon' => 'fa-boxes',
        'description' => __('View and manage daily stock inventory records', 'chinemerem-foods'),
        'color' => '#1e3a5f',
    ),
    array(
        'slug' => 'packing-store',
        'title' => __('Packing Store', 'chinemerem-foods'),
        'icon' => 'fa-box-open',
        'description' => __('Manage packing store transfers and inventory', 'chinemerem-foods'),
        'color' => '#2563eb',
    ),
    array(
        'slug' => 'debtors-record',
        'title' => __('Debtors Record', 'chinemerem-foods'),
        'icon' => 'fa-user-clock',
        'description' => __('Manage debtor accounts and payments', 'chinemerem-foods'),
        'color' => '#0f2744',
    ),
    array(
        'slug' => 'expenses',
        'title' => __('Expenses Record', 'chinemerem-foods'),
        'icon' => 'fa-file-invoice-dollar',
        'description' => __('Record and track daily business expenses', 'chinemerem-foods'),
        'color' => '#1d4ed8',
    ),
    array(
        'slug' => 'import-record',
        'title' => __('Import Record', 'chinemerem-foods'),
        'icon' => 'fa-truck-loading',
        'description' => __('Record product imports and deliveries', 'chinemerem-foods'),
        'color' => '#0a1628',
    ),
    array(
        'slug' => 'not-supplied',
        'title' => __('Not Supplied Record', 'chinemerem-foods'),
        'icon' => 'fa-times-circle',
        'description' => __('Track orders that were not supplied', 'chinemerem-foods'),
        'color' => '#dc2626',
    ),
    array(
        'slug' => 'supplied-today',
        'title' => __('Supplied Today', 'chinemerem-foods'),
        'icon' => 'fa-check-circle',
        'description' => __('Record products supplied today', 'chinemerem-foods'),
        'color' => '#059669',
    ),
    array(
        'slug' => 'order-product-summary',
        'title' => __('Order Product Summary', 'chinemerem-foods'),
        'icon' => 'fa-chart-bar',
        'description' => __('View daily order product analytics', 'chinemerem-foods'),
        'color' => '#7c3aed',
    ),
    array(
        'slug' => 'credit-order-summary',
        'title' => __('Credit Order Summary', 'chinemerem-foods'),
        'icon' => 'fa-chart-pie',
        'description' => __('View credit order product analytics', 'chinemerem-foods'),
        'color' => '#db2777',
    ),
    array(
        'slug' => 'cash-out',
        'title' => __('Cash Out Record', 'chinemerem-foods'),
        'icon' => 'fa-money-bill-wave',
        'description' => __('Record cash transfers to bank accounts', 'chinemerem-foods'),
        'color' => '#d97706',
    ),
    array(
        'slug' => 'transfer-history',
        'title' => __('Transfer History', 'chinemerem-foods'),
        'icon' => 'fa-exchange-alt',
        'description' => __('View all transfer/card payment history', 'chinemerem-foods'),
        'color' => '#0891b2',
    ),
    array(
        'slug' => 'cash-out-history',
        'title' => __('Cash Out History', 'chinemerem-foods'),
        'icon' => 'fa-history',
        'description' => __('View cash out transfer history', 'chinemerem-foods'),
        'color' => '#64748b',
    ),
    array(
        'slug' => 'financial-summary',
        'title' => __('Financial Summary', 'chinemerem-foods'),
        'icon' => 'fa-calculator',
        'description' => __('View daily financial summary and reports', 'chinemerem-foods'),
        'color' => '#047857',
    ),
    array(
        'slug' => 'reconciliation',
        'title' => __('Reconciliation Calendar', 'chinemerem-foods'),
        'icon' => 'fa-calendar-check',
        'description' => __('Track daily reconciliation status', 'chinemerem-foods'),
        'color' => '#92400e',
    ),
);

// Build URLs for each card
foreach ($cards as &$card) {
    $page = get_page_by_path($card['slug']);
    if ($page) {
        $card['url'] = get_permalink($page->ID);
    } else {
        // Fallback to slug-based URL
        $card['url'] = home_url('/' . $card['slug'] . '/');
    }
}
unset($card);
?>
<main class="cfi-main">
    <div class="cfi-container">
        <div class="cfi-page-title">
            <h1>
                <i class="fas fa-home"></i>
                <?php esc_html_e('Dashboard', 'chinemerem-foods'); ?>
            </h1>
            <span class="cfi-date-display"><?php echo esc_html(current_time('l, F j, Y')); ?></span>
        </div>
        
        <div class="cfi-cards-grid">
            <?php foreach ($cards as $card) : ?>
            <a href="<?php echo esc_url($card['url']); ?>" class="cfi-card cfi-glass">
                <div class="cfi-card-icon" style="background: <?php echo esc_attr($card['color']); ?>;">
                    <i class="fas <?php echo esc_attr($card['icon']); ?>"></i>
                </div>
                <h3 class="cfi-card-title"><?php echo esc_html($card['title']); ?></h3>
                <p class="cfi-card-description"><?php echo esc_html($card['description']); ?></p>
                <span class="cfi-btn cfi-btn-primary cfi-btn-sm cfi-card-btn">
                    <?php esc_html_e('Open', 'chinemerem-foods'); ?>
                    <i class="fas fa-arrow-right"></i>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
        
        <?php if (CFI_Auth::is_cfi_admin()) : ?>
        <div class="cfi-admin-section cfi-glass" style="margin-top: 2rem;">
            <h3><i class="fas fa-cogs"></i> <?php esc_html_e('Quick Admin Actions', 'chinemerem-foods'); ?></h3>
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 1rem;">
                <a href="<?php echo esc_url(home_url('/admin-panel/')); ?>" class="cfi-btn cfi-btn-secondary">
                    <i class="fas fa-plus"></i>
                    <?php esc_html_e('Add Products', 'chinemerem-foods'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/admin-panel/')); ?>" class="cfi-btn cfi-btn-secondary">
                    <i class="fas fa-user-plus"></i>
                    <?php esc_html_e('Manage Debtors', 'chinemerem-foods'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/admin-panel/')); ?>" class="cfi-btn cfi-btn-secondary">
                    <i class="fas fa-users-cog"></i>
                    <?php esc_html_e('Manage Users', 'chinemerem-foods'); ?>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>
