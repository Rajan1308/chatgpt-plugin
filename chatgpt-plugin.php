<?php
/*
Plugin Name: CONF Secure Docs
Description: A plugin that creates a custom menu item and admin screen to save a "Doc API Key" option.
*/

class CONF_Secure_Docs {
	// Constructor
	public function __construct() {
		// Add custom menu item
		add_action('admin_menu', array($this, 'add_secure_docs_menu_item'));
		
		// Register settings and fields
		add_action('admin_init', array($this, 'register_secure_docs_settings'));
	}
	
	// Add custom menu item
	public function add_secure_docs_menu_item() {
		add_menu_page(
			'CONF Secure Docs Settings', // Page title
			'CONF Secure Docs', // Menu title
			'manage_options', // Capability
			'conf-secure-docs-settings', // Menu slug
			array($this, 'display_secure_docs_settings_screen'), // Function to render screen
			'dashicons-admin-generic', // Icon URL
			99 // Menu position
		);
	}
	
	// Register settings and fields
	public function register_secure_docs_settings() {
		register_setting(
			'conf_secure_docs_settings', // Option group
			'conf_secure_docs_api_key', // Option name
			array($this, 'validate_secure_docs_api_key') // Sanitize callback
		);
		
		add_settings_section(
			'conf_secure_docs_api_key_section', // ID
			'Doc API Key', // Title
			array($this, 'display_secure_docs_api_key_section_info'), // Callback
			'conf-secure-docs-settings' // Page
		);
		
		add_settings_field(
			'conf_secure_docs_api_key_field', // ID
			'Doc API Key', // Title
			array($this, 'display_secure_docs_api_key_field'), // Callback
			'conf-secure-docs-settings', // Page
			'conf_secure_docs_api_key_section' // Section
		);
	}
	
	// Display Doc API Key section info
	public function display_secure_docs_api_key_section_info() {
		echo '<p>Enter your Doc API Key to enable secure access to your documents.</p>';
	}
	
	// Display Doc API Key field
	public function display_secure_docs_api_key_field() {
		// Get existing option value
		$api_key = get_option('conf_secure_docs_api_key');
		
		// Display text field
		echo '<input type="text" id="conf_secure_docs_api_key" name="conf_secure_docs_api_key" value="' . $api_key . '" />';
	}
	
	// Validate Doc API Key field
	public function validate_secure_docs_api_key($input) {
		// Only allow alphanumeric values
		return preg_replace('/[^a-zA-Z0-9]/', '', $input);
	}
	
	// Render settings screen
	public function display_secure_docs_settings_screen() {
		// Check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}
		
		// Display settings screen HTML
		echo '<div class="wrap">';
		echo '<h1>CONF Secure Docs Settings</h1>';
		echo '<form method="post" action="options.php">';
		
		// Output security fields and settings sections
		settings_fields('conf_secure_docs_settings');
		do_settings_sections('conf-secure-docs-settings');
		
		// Display submit button
		submit_button();
		
		echo '</form>';
		echo '</div>';
	}
}

new CONF_Secure_Docs();
