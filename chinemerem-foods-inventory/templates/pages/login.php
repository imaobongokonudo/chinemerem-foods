<?php
/**
 * Login Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Redirect if already logged in
if (is_user_logged_in()) {
    $home_page = get_page_by_path('cfi-home');
    if ($home_page) {
        wp_redirect(get_permalink($home_page->ID));
        exit;
    }
}
?>
<div class="cfi-login-wrapper">
    <div class="cfi-login-box cfi-glass">
        <div class="cfi-login-logo">
            <img src="<?php echo esc_url(CFI_PLUGIN_URL . 'assets/images/logo.svg'); ?>" alt="Chinemerem Foods">
            <h1><?php esc_html_e('Chinemerem Foods', 'chinemerem-foods'); ?></h1>
            <p><?php esc_html_e('Inventory Management System', 'chinemerem-foods'); ?></p>
        </div>
        
        <form id="cfi-login-form" class="cfi-login-form">
            <div class="cfi-form-group">
                <label for="cfi-username"><?php esc_html_e('Username', 'chinemerem-foods'); ?></label>
                <input type="text" id="cfi-username" name="username" class="cfi-input" required autofocus>
            </div>
            
            <div class="cfi-form-group">
                <label for="cfi-password"><?php esc_html_e('Password', 'chinemerem-foods'); ?></label>
                <input type="password" id="cfi-password" name="password" class="cfi-input" required>
            </div>
            
            <div class="cfi-form-group">
                <label class="cfi-checkbox">
                    <input type="checkbox" name="remember">
                    <span><?php esc_html_e('Remember me', 'chinemerem-foods'); ?></span>
                </label>
            </div>
            
            <button type="submit" class="cfi-btn cfi-btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                <?php esc_html_e('Login', 'chinemerem-foods'); ?>
            </button>
        </form>
        
        <div class="cfi-login-footer" style="text-align: center; margin-top: 2rem; font-size: 0.875rem; color: var(--cfi-gray);">
            <p>&copy; <?php echo esc_html(gmdate('Y')); ?> Chinemerem Foods</p>
            <p><?php esc_html_e('Designed by', 'chinemerem-foods'); ?> <a href="https://bendlestech.com" target="_blank" rel="noopener">BendlessTech</a></p>
        </div>
    </div>
</div>
