<?php
require_once ('data/modules/albums/functions.php');

function albums_theme_main() {
	//Don't show something that isn't there.
	if (count(glob('data/settings/modules/albums/*/*')) > 1) {
		//Open the module-folder
		$albums = read_dir_contents('data/settings/modules/albums', 'dirs');

		//Loop through dirs.
		foreach ($albums as $album) {
			include_once('data/settings/modules/albums/'.$album.'.php');
			?>
				<div class="album">
					<table>
						<tr>
							<td>
								<a href="?module=albums&amp;page=viewalbum&amp;album=<?php echo $album; ?>&amp;pageback=<?php echo CURRENT_PAGE_SEONAME; ?>" title="album <?php echo $album_name; ?>"><img alt="<?php echo $album_name; ?>" title="<?php echo $album_name; ?>" src="data/modules/albums/albums_getimage.php?image=<?php echo $album; ?>/thumb/image1.jpg" /></a>
							</td>
							<td>
								<span class="albuminfo">
									<a href="?module=albums&amp;page=viewalbum&amp;album=<?php echo $album; ?>&amp;pageback=<?php echo CURRENT_PAGE_SEONAME; ?>" title="album <?php echo $album_name; ?>"><?php echo $album_name; ?></a>
								</span>
							</td>
						</tr>
					</table>
				</div>
			<?php
		}
		unset($albums);
	}
}

function albums_theme_meta() {
	global $module;

	//Only insert LyteBox when we're viewing an album
	if (isset($module) && $module == 'albums') {
	?>
		<script type="text/javascript" src="data/inc/lytebox/lytebox.js"></script>
		<link rel="stylesheet" href="data/inc/lytebox/lytebox.css" type="text/css" media="screen" />
	<?php
	}
}

function albums_page_site_list() {
	global $lang;

	if (file_exists(MODULE_SETTINGS.'/'.$_GET['album'].'.php')) {
		include(MODULE_SETTINGS.'/'.$_GET['album'].'.php');
		$module_page_site[] = array(
			'func'  => 'viewalbum',
			'title' => $album_name
		);
	}
	else {
		$module_page_site[] = array(
			'func'  => 'viewalbum',
			'title' => $lang['general']['404']
		);
	}
	return $module_page_site;
}

function albums_page_site_viewalbum() {
	global $lang, $lang_albums18;

	//Predefined variable
	$album = $_GET['album'];
	if (isset($_GET['pageback']))
		$pageback = $_GET['pageback'];

	if (!file_exists('data/settings/modules/albums/'.$album))
		echo '<p>'.$lang_albums18.'</p>';

	//If the album exists
	else {
		//Start reading out those images...
		$files = read_dir_contents('data/settings/modules/albums/'.$album, 'files');
		if ($files) {
			natcasesort($files);
			foreach ($files as $file) {
				//Check if the files are JPG
				list($fdirname, $ext) = explode('.', $file);
				if ($ext == 'jpg') {
					include_once ('data/settings/modules/albums/'.$album.'/'.$fdirname.'.php');
					?>
						<div class="album">
							<table>
								<tr>
									<td>
										<a href="data/modules/albums/albums_getimage.php?image=<?php echo $album; ?>/<?php echo $fdirname; ?>.jpg" rel="lytebox[album]" title="<?php echo $name; ?>">
											<img src="data/modules/albums/albums_getimage.php?image=<?php echo $album; ?>/thumb/<?php echo $fdirname; ?>.jpg" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" />
										</a>
									</td>
									<td>
										<span class="albuminfo"><?php echo $name; ?></span>
										<br />
										<i><?php echo $info; ?></i>
									</td>
								</tr>
							</table>
						</div>
					<?php
				}
			}
			unset($file);
		}
	}
	if (isset($pageback)) {
		?>
			<p>
				<a href="?file=<?php echo $pageback; ?>" title="<?php echo $lang['general']['back']; ?>">&lt;&lt;&lt; <?php echo $lang['general']['back']; ?></a>
			</p>
		<?php
	}
}
?>
