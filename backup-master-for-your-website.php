<?php
/*
  * Plugin Name: Backup master for your website
  * Plugin URI: 
  * Description: "Backup master for your website" plugin will allow to admin to create backup of database and files for wordpress store. This plugin provides backup of files and database backups with a single click.
  * Author: Sunarc
  * Author URI: https://www.suncartstore.com/
  * Author URI: 
  * Version: 1.0.7
 */

if (!defined("ABSPATH"))
      exit;

if (!defined("BMFYW_PLUGIN_DIR_PATH"))
define("BMFYW_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

if (!defined("BMFYW_PLUGIN_URL"))
define("BMFYW_PLUGIN_URL", plugins_url('backup-master-for-your-website'));  



/**
 * Enqueue scripts and styles
 */
function bmfyw_include_assets() {
  if (is_admin()) {
    wp_enqueue_style('sunarcwpdb-style.css', BMFYW_PLUGIN_URL . '/assets/css/style.css');
    wp_enqueue_script('sunarcwpdb-script.js', BMFYW_PLUGIN_URL . '/assets/js/script.js', '', true);
    wp_localize_script("sunarcwpdb-script.js","sunarcwpdbajaxurl",admin_url("admin-ajax.php"));
  }
}
add_action("init", "bmfyw_include_assets");


require_once BMFYW_PLUGIN_DIR_PATH.'/INC/sunarcwpdb_functions.php';

/**
 * Create backup
 */
if(!empty($_POST['sunarcwpdb_backup'])){

  
  $dir    = WP_CONTENT_DIR.'/bmfyw_backup/';
  $folder = time();
  if ( !is_dir( $dir ) ) { mkdir( $dir,0777); }

  $backups = scandir($dir,1);
  foreach ($backups as $backup) {
    if(strlen($backup) > 5){
      $allbackups[] = $backup;
    }
  }
  if (isset($allbackups) && count($allbackups) >= 10) {
    $max_backup = 1;
  }

  if (!$max_backup) {

  
  if ( !is_dir( WP_CONTENT_DIR.'/bmfyw_backup/'.$folder ) ) { mkdir( WP_CONTENT_DIR.'/bmfyw_backup/'.$folder,0777); }

  
    
    if ( $_POST['sunarcwpdb_backup']=='database' || $_POST['sunarcwpdb_backup']=='complete' ) {

      $data = file_put_contents($dir.$folder.'/database.sql',bmfyw_export_database());


      if($data){
        $zip = new ZipArchive();
        $zip->open($dir.$folder.'/database.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile($dir.$folder.'/database.sql', 'database.sql');
        $zip->close();
        $succ = 1;
        ob_get_clean();
      }

    }

    if ( $_POST['sunarcwpdb_backup']=='complete' ) {

      $rootPath = ABSPATH;
      $zip = new ZipArchive();
      $zip->open($dir.$folder.'/files.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

      $files = new RecursiveIteratorIterator(
          new RecursiveDirectoryIterator($rootPath),
          RecursiveIteratorIterator::LEAVES_ONLY
      );

      foreach ($files as $file)
      {
          // Skip directories (they would be added automatically)
          if (!$file->isDir())
          {
              // Get real and relative path for current file
              $filePath = $file->getRealPath();
              //$relativePath = substr($filePath, strlen($rootPath) + 1);
              $relativePath = substr($filePath, strlen($rootPath));

              if (strpos($relativePath, 'bmfyw_backup') == false) {
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
              }
              
          }
      }
      $zip->addFile($dir.$folder.'/database.sql', 'database.sql');
      $zip->close();
      
      $succ = 1;
    }

    unlink($dir.$folder.'/database.sql');

  }
  

}




function bmfyw_menus() {
  add_submenu_page("options-general.php", "Backup master for your website", "Backup master for your website", "manage_options", "wordpress-data-backup", "bmfyw_data_backup");
}

add_action("admin_menu", "bmfyw_menus");


function bmfyw_data_backup() {
  include_once BMFYW_PLUGIN_DIR_PATH . '/INC/settings.php';
}





function bmfyw_backup_download_func() {

  //print_r(base64_decode($_GET['url']) );die;
  //check_admin_referer( 'hmbkp_download_backup', 'hmbkp_download_backup_nonce' );

  $url = esc_url_raw(base64_decode($_GET['url']));
  if ( $_GET['act'] == 'download' ) {
    wp_safe_redirect( $url, 303 );
    die;
  }

  if ( $_GET['act'] == 'delete' ) {
    $all_files = scandir($url,1);
    if (!empty($all_files)) {
      foreach ($all_files as $all_file) {
        unlink($url.'/'.$all_file);
      }
    }
    rmdir( $url );
    wp_safe_redirect( $_SERVER['HTTP_REFERER'].'&delete=delete', 303 );
    die;
  }

}
add_action( 'admin_post_sunarcwpdb_backup_download', 'bmfyw_backup_download_func' );
