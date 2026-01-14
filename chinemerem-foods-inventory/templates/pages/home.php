<?php
/**
 * Home/Dashboard Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$cards = CFI_Pages::get_home_cards();
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
            <a href="<?php echo esc_url($card['url']); ?>" class="cfi-card">
                <div class="cfi-card-icon" style="background-color: <?php echo esc_attr($card['color']); ?>">
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
        <div class="cfi-admin-section cfi-glass">
            <h3><i class="fas fa-cogs"></i> <?php esc_html_e('Quick Admin Actions', 'chinemerem-foods'); ?></h3>
            <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                <?php
                $admin_page = get_page_by_path('cfi-admin-panel');
                if ($admin_page) :
                ?>
                <a href="<?php echo esc_url(get_permalink($admin_page->ID)); ?>" class="cfi-btn cfi-btn-secondary">
                    <i class="fas fa-plus"></i>
                    <?php esc_html_e('Add Products', 'chinemerem-foods'); ?>
                </a>
                <a href="<?php echo esc_url(get_permalink($admin_page->ID)); ?>" class="cfi-btn cfi-btn-secondary">
                    <i class="fas fa-user-plus"></i>
                    <?php esc_html_e('Manage Debtors', 'chinemerem-foods'); ?>
                </a>
                <a href="<?php echo esc_url(get_permalink($admin_page->ID)); ?>" class="cfi-btn cfi-btn-secondary">
                    <i class="fas fa-users-cog"></i>
                    <?php esc_html_e('Manage Users', 'chinemerem-foods'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>
