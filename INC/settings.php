<?php
if (!defined("ABSPATH"))
      exit;
?>
<div class="wrap">
<?php global $succ; if(isset($succ)&&$succ==1): ?>
<div id="" class="updated rate notice is-dismissible">
	<p>Backup created.</p>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
</div>
<?php endif; ?>
<?php global $max_backup; if(isset($max_backup)&&$max_backup==1): ?>
<div id="" class="updated rate notice is-dismissible">
	<p style="font-weight: bold;">You can make up to 10 backups.</p>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
</div>
<?php endif; ?>
<?php if(isset($_GET['delete'])&&$_GET['delete']=='delete'): ?>
<div id="" class="updated rate notice is-dismissible">
	<p>Backup deleted.</p>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
</div>
<?php endif; ?>
<h1>Backup master for your website</h1>
<form id="sunarcwpdb_backup_form" action="<?php echo admin_url(); ?>options-general.php?page=wordpress-data-backup" method="post">
	<table class="form-table" role="presentation">
		<tbody>
			<tr valign="top">
				<th scope="row">
					<label for="sunarcwpdb_backup">Backup</label>
				</th>
				<td>
					<select name="sunarcwpdb_backup" id="sunarcwpdb_backup">
						<option selected="selected" value="database">Database only</option>
						<option value="complete">Both Database &amp; files</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<!-- <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Create Backup"></p> -->
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Create Backup">
				</th>
				<td>
					<p>*You can make up to 10 backups.</p>
				</td>
			</tr>
		</tbody>
	</table>
</form>




<?php
$dir = WP_CONTENT_DIR.'/bmfyw_backup/';

if(is_dir($dir)){

// Sort in descending order
$backups = scandir($dir,1); ?>



<h2>All Backups</h2>

<table id="storedata">
  <tr>
    <th>Backup Date</th>
    <th>Size</th>
    <th>Type</th>
    <th>Actions</th>
  </tr>
  <?php foreach ($backups as $backup) {
  	if(strlen($backup) > 5){
  	$backup_date = date('d M Y h:i a', (int) $backup);
  	$b_file = scandir($dir.$backup,1);
  	$ext = explode('.', $b_file[0]);

  	$bytes = filesize($dir.$backup.'/'.$b_file[0]);
  	$size = bmfyw_FileSizeUnits($bytes);
  	?>
  	<tr>
		<td><?php echo $backup_date; ?></td>
		<td><?php echo $size; ?></td>
		<td><?php echo ($ext[0]!=='database') ? 'Database & files' : 'Database'; ?></td>
		<td><a href="<?php echo get_admin_url().'admin-post.php?url='.base64_encode(content_url().'/bmfyw_backup/'.$backup.'/'.$b_file[0]);?>&act=download&action=sunarcwpdb_backup_download">Download</a> | 
			<a class="sunarcwpdb_delete" href="<?php echo get_admin_url().'/admin-post.php?url='.base64_encode($dir.$backup);?>&act=delete&action=sunarcwpdb_backup_download">Delete</a></td>
	</tr>
  <?php } } ?>
</table>


<?php } ?>
</div>