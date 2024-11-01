<?php
/*
Plugin Name: WP Consultant
Description: Small online consultant for your blog
Version: 1.0
Author: Shchekin Roman
Author URI: http://webstydio.ru/
*/

if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('You are not allowed to download this page remotely.');
}

if (!class_exists('SOC')) {
	class SOC {
		public $data = array();

		// Initialization of the main variables
		function SOC()
		{
			global $wpdb;
			DEFINE('SOC', true);
			$this->plugin_name = plugin_basename(__FILE__);
			$this->plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)));
			$this->tbl_soc_option = $wpdb->prefix . 'soc_option';
			$this->tbl_soc_dialogs = $wpdb->prefix . 'soc_dialogs';
			register_activation_hook($this->plugin_name, array(&$this, 'soc_activate'));
			register_deactivation_hook($this->plugin_name, array(&$this, 'soc_deactivate'));
			register_uninstall_hook($this->plugin_name, array(&$this, 'soc_uninstall'));
			if (is_admin()) {
				add_action('admin_init', array(&$this, 'soc_admin_init'));
				add_action('admin_menu', array(&$this, 'admin_generate_menu'));
			}
			add_action('init', array(&$this, 'session_for_soc'));
			add_action('wp_enqueue_scripts', array(&$this, 'load_additional_files'));
		}

		// Activating the plugin
		function soc_activate()
		{
			global $wpdb;
			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
			$option = $this->tbl_soc_option;
			$dialogs = $this->tbl_soc_dialogs;
			//
			if (version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
				if (!empty($wpdb->charset))
					$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
				if (!empty($wpdb->collate))
					$charset_collate .= " COLLATE $wpdb->collate";
			}
			// Table for option
			$sql_tbl_soc_option = "
			CREATE TABLE `".$wpdb->prefix."soc_option` (
                `option_name` varchar(255) not null default '',
				`option_value` varchar(255) not null default '',
				PRIMARY KEY (`option_name`)
			)".$charset_collate.";";
			// Table for dialogs
			$sql_tbl_soc_dialogs = "
			CREATE TABLE `".$wpdb->prefix."soc_dialogs` (
				`id` int(11) unsigned null auto_increment,
				`dialog_id` varchar(20) not null default '',
				`type` int(2) not null default '0',
				`author` varchar(255) not null default '',
				`email` varchar(255) not null default '',
				`message` text not null,
				`time` int(11) not null default '0',
				`status` int(2) not null default '1',
				PRIMARY KEY (`id`)
			)".$charset_collate.";";
			//
			if ($wpdb->get_var("show tables like '".$option."'") != $option) {
				dbDelta($sql_tbl_soc_option);
			}
			if ($wpdb->get_var("show tables like '".$category."'") != $category) {
				dbDelta($sql_tbl_soc_dialogs);
			}
			//
			$StartCheck = $wpdb->get_results("SELECT * FROM `".$this->tbl_soc_option."`", ARRAY_A);
			if (count($StartCheck) != 4) {
				$wpdb->query("TRUNCATE `".$this->tbl_soc_option."`");
				$wpdb->insert($this->tbl_soc_option, array('option_name' => 'email', 'option_value' => ''), array('%s', '%s'));
				$wpdb->insert($this->tbl_soc_option, array('option_name' => 'operator_online', 'option_value' => '0'), array('%s', '%d'));
				$wpdb->insert($this->tbl_soc_option, array('option_name' => 'last_online', 'option_value' => '0'), array('%s', '%d'));
				$wpdb->insert($this->tbl_soc_option, array('option_name' => 'consultant_image', 'option_value' => ''), array('%s', '%s'));
			}
			// Create folder for consultant image
			if (!is_dir("../wp-content/wp_consultant")) {
				mkdir("../wp-content/wp_consultant", 0755);
			}
			// Copy consult_form.php in the current theme
			if (!file_exists("../wp-content/themes/".get_template()."/consult_form.php")) {
				copy("../wp-content/plugins/wp-consultant/template/consult_form.php", "../wp-content/themes/".get_template()."/consult_form.php");
			}
			// Create config
			if (!file_exists("../wp-content/plugins/wp-consultant/soc.ini.php")) {
				$content = "<?php\r\n";
				$lines = file("../wp-config.php");
				foreach ($lines as $line) {
					if(strstr(htmlspecialchars($line), htmlspecialchars("define('DB_NAME'"))) {
						$content .= htmlspecialchars($line);
					}
					if(strstr(htmlspecialchars($line), htmlspecialchars("define('DB_USER'"))) {
						$content .= htmlspecialchars($line);
					}
					if(strstr(htmlspecialchars($line), htmlspecialchars("define('DB_PASSWORD'"))) {
						$content .= htmlspecialchars($line);
					}
					if(strstr(htmlspecialchars($line), htmlspecialchars("define('DB_HOST'"))) {
						$content .= htmlspecialchars($line);
					}
					if(strstr(htmlspecialchars($line), htmlspecialchars("define('DB_CHARSET'"))) {
						$content .= htmlspecialchars($line);
					}
					if(strstr(htmlspecialchars($line), htmlspecialchars("\$table_prefix"))) {
						$content .= htmlspecialchars($line);
					}
				}
				$content .= "?>";
				chmod("../wp-content/plugins/wp-consultant", 0775);
				$handle = fopen("../wp-content/plugins/wp-consultant/soc.ini.php", 'w');
				fwrite($handle, $content);
				fclose($handle);
			}
		}

		// Deactivating the plugin
		function soc_deactivate()
		{
			return true;
		}

		// Removing the plugin
		function soc_uninstall()
		{
			global $wpdb;
			$wpdb->query("DROP TABLE IF EXISTS `".$wpdb->prefix."soc_option`");
			$wpdb->query("DROP TABLE IF EXISTS `".$wpdb->prefix."soc_dialogs`");
		}
		
		// Reg files for admin panel
		function soc_admin_init()
		{
			// Load admin.js
			wp_deregister_script("soc_admin");
			wp_register_script("soc_admin", plugins_url('/js/admin.js', __FILE__));
			// Load admin.css
			wp_deregister_style("soc_admin_style");
			wp_register_style("soc_admin_style", plugins_url("/css/admin.css", __FILE__));
		}

		// Create a menu in the admin panel
		function admin_generate_menu()
		{
			add_menu_page('Consultant', 'WP Consultant', 'manage_options', 'consult_edit', array(&$this, 'admin_edit_settings'));
			$option = add_submenu_page('consult_edit', 'Settings', 'Options', 'manage_options', 'consult_edit', array(&$this, 'admin_edit_settings'));
			$dialogs = add_submenu_page('consult_edit', 'Show dialogs', 'Dialogs', 'manage_options', 'consult_dialogs', array(&$this, 'admin_show_dialogs'));
			add_action('admin_print_scripts-' . $dialogs, array(&$this, 'soc_admin_scripts'));
		}
		
		// Enable scripts and styles
		function soc_admin_scripts()
		{
			wp_enqueue_script("jquery");
			wp_enqueue_script("soc_admin");
			wp_enqueue_style("soc_admin_style");
		}
		
		// Session enable
		function session_for_soc() {
			if (!session_id())
				session_start();
		}
		
		// Load files
		function load_additional_files()
		{
			// Load JQuery
			wp_enqueue_script("jquery");
			// Load client.js
			wp_deregister_script("soc_client");
			wp_register_script("soc_client", "/wp-content/plugins/wp-consultant/js/client.js", false, false, false);
			wp_enqueue_script("soc_client");
			// Load client.css
			wp_deregister_style("soc_client_style");
			wp_register_style("soc_client_style", "/wp-content/plugins/wp-consultant/css/client.css", false, false, false);
			wp_enqueue_style("soc_client_style");
		}
		
		public function admin_edit_settings()
		{
			global $wpdb;
			$action = isset($_GET['action']) ? $_GET['action'] : null ;
			switch ($action) {
				case 'submit':
					switch ($_POST['type_submit'])
					{
						case 'save':
							//load_consultant_image
                            if (!isset($_POST['email']) || $_POST['email'] == "" || !preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/", $_POST['email'])) {
								print "<p style='color: red;'>E-mail is not valid</p>";
							} else {
								$ConImg = $wpdb->get_row("SELECT `option_value` FROM `".$this->tbl_soc_option."` WHERE `option_name` = 'consultant_image'", ARRAY_A);
								if ($_POST['image_del'] == 1 && $_FILES['image']['tmp_name'] == "" && !empty($ConImg['option_value'])) {
									// delete
									unlink("../wp-content/wp_consultant/".$ConImg['option_value']);
									$image = "";
								} elseif ($_FILES['image']['tmp_name'] != "") {
									// delete & load new
									$check_image = substr($_FILES['image']['name'], 1 + strrpos($_FILES['image']['name'], "."));
									if (($_POST['image_del'] == 1 || $_POST['image_del_flag'] == 0) && !empty($ConImg['option_value'])) {
										unlink("../wp-content/wp_consultant/".$ConImg['option_value']);
									}
									$image_types = explode(", ", "gif, jpg, png, jpeg, GIF, JPG, PNG, JPEG");
									if (in_array($check_image, $image_types) && !empty($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != "") {
										$image = $this->load_consultant_image($_FILES['image']['tmp_name'], $check_image);
									} else {
										$image = "";
									}
								} elseif ($_POST['image_del'] != 1 && $_FILES['image']['tmp_name'] == "") {
									$image = $ConImg['option_value'];
								}
								$wpdb->update($this->tbl_soc_option, array('option_value' => $_POST['email']), array('option_name' => 'email'));
								$wpdb->update($this->tbl_soc_option, array('option_value' => $image), array('option_name' => 'consultant_image'));
							}
							break;
					}
					$this->admin_show_option();
					break;
				default:
					$this->admin_show_option();
            }
     	}

     	private function admin_show_option()
		{
			global $wpdb;
			$this->data['email'] = $wpdb->get_row("SELECT `option_value` FROM `".$this->tbl_soc_option."` WHERE `option_name`= 'email'", ARRAY_A);
			$this->data['image'] = $wpdb->get_row("SELECT `option_value` FROM `".$this->tbl_soc_option."` WHERE `option_name`= 'consultant_image'", ARRAY_A);
			include_once('admin/admin_view_option.php');
		}

		public function admin_show_dialogs()
		{
			global $wpdb;
			include_once('admin/admin_show_dialogs.php');
     	}
		
		private function load_consultant_image($file, $img_type)
		{
			$size = getimagesize($file);
			$w = 100;
			if ($img_type == 'jpg' || $img_type == 'jpeg' || $img_type == 'JPG' || $img_type == 'JPEG') {
				$src = imagecreatefromjpeg($file);
			} elseif ($img_type == 'png' || $img_type == 'PNG') {
				$src = imagecreatefrompng($file);
			} elseif ($img_type == 'gif' || $img_type == 'GIF') {
				$src = imagecreatefromgif($file);
			}
			$w_src = imagesx($src);
        	$h_src = imagesy($src);
        	$ratio = $w_src / $w;
        	$w_dest = round($w_src / $ratio);
        	$h_dest = round($h_src / $ratio);
        	$dest = imagecreatetruecolor($w_dest, $h_dest);
			if ($img_type == 'png') {
				$t_index = imagecolortransparent($src);
				$t_color = array('red' => 255, 'green' => 255, 'blue' => 255);
				if ($t_index >= 0) {
					$t_color = imagecolorsforindex($src, $t_index);
				}
				$t_index = imagecolorallocate($dest, $t_color['red'], $t_color['green'], $t_color['blue']);
				imagefill($dest, 0, 0, $t_index);
				imagecolortransparent($dest, $t_index);
			}
        	imagecopyresized($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
        	$output = time().".".$img_type;
			if ($img_type == 'jpg' || $img_type == 'jpeg' || $img_type == 'JPG' || $img_type == 'JPEG') {
				imagejpeg($dest, "../wp-content/wp_consultant/".$output, 100);
			} elseif ($img_type == 'png' || $img_type == 'PNG') {
				imagepng($dest, "../wp-content/wp_consultant/".$output);
			} elseif ($img_type == 'gif' || $img_type == 'GIF') {
				imagegif($dest, "../wp-content/wp_consultant/".$output, 100);
			}
        	imagedestroy($dest);
        	imagedestroy($src);
        	//
        	if (file_exists("../wp-content/wp_consultant/".$output)) {
				return $output;
			} else {
				return "";
   			}
		}
	}
}

global $soc;
$soc = new SOC();
?>