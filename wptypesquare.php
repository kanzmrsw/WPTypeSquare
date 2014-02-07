<?php
/**
 Plugin Name: WPTypeSquare
 Plugin URI: https://github.com/kanzmrsw
 Description: たいぷぅすくうぇあ
 Author: super typesquare user
 Version: 0.1
 Author URI: https://github.com/kanzmrsw
 */

if (!is_admin()) {
	function register_tsjs() {
		$tsjs_name = 'typesquarejs';
		$tsjs_url = '//typesquare.com/accessor/script/typesquare.js';
		$tsjs_uid = get_tsoptions('uid');
    $ts_font = get_tsoptions('font');
    $ts_element = get_tsoptions('element');
		$ts_usefadein = get_tsoptions('usefadein');
		$ts_fadein = get_tsoptions('fadein');

		$tsjs_uid = str_replace('%3D', urlencode('%3D'), $tsjs_uid);

		if ($ts_usefadein) {
			wp_register_script($tsjs_name, $tsjs_url . '?' . $tsjs_uid . '&fadein=' . $ts_fadein);
		}
		else {
			wp_register_script($tsjs_name, $tsjs_url . '?' . $tsjs_uid);
		}
		wp_enqueue_script($tsjs_name);

		//TODO: set font fallback
		echo <<<EOM
<style type="text/css">
	$ts_element {
		font-family: $ts_font; 
	}
</style>
EOM;
	}

	add_action('wp_print_scripts', 'register_tsjs');
}

function get_tsoptions($id) {
	$opt = get_option('showts_options');
	return isset($opt[$id]) ? $opt[$id] : null;
}

function register_tsmenu() {
	add_menu_page('WPTypeSquare Preference', 'WPTypeSquare Plugin', 8, __FILE__, 'show_tsmenu');
}
function show_tsmenu() {
       //$_POST['showts_options'])があったら保存
       if ( isset($_POST['showts_options'])) {
           check_admin_referer('shoptions');
           $opt = $_POST['showts_options'];
           update_option('showts_options', $opt);
           ?><div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p></div><?php
       }
       ?>
       <div class="wrap">
       <script type="text/javascript">
        function fadeincheck() {
          var usefadein = document.getElementsByName('showts_options[usefadein]')[0];
          var fadein = document.getElementsByName('showts_options[fadein]')[0];
          if (usefadein.checked) {
            fadein.disabled = false;
          } else {
            fadein.disabled = true;
          }
        }
       </script>
       <style type="text/css">
       	.subdescription {
       		font-size: 10px;
       	}
       </style>
       <div id="icon-options-general" class="icon32"><br /></div><h2>設定</h2>
           <form action="" method="post">
               <?php
               wp_nonce_field('shoptions');
               $opt = get_option('showts_options');
               $show_uid = isset($opt['uid']) ? $opt['uid'] : null;
               $show_font = isset($opt['font']) ? $opt['font'] : null;
               $show_element = isset($opt['element']) ? $opt['element'] : null;
               $show_usefadein = $opt['usefadein'];
               $show_fadein = isset($opt['fadein']) ? $opt['fadein'] : null;
               ?> 
               <table class="form-table">
                   <tr valign="top">
                       <th scope="row"><label for="uid">UID</label></th>
                       <td><input name="showts_options[uid]" type="text" id="uid" value="<?php echo $show_uid ?>" class="regular-text" /><br />
                           <span class="subdescription">//typesquare.com/accessor/script/typesquare.js?[Your UID]</span></td>
                   </tr>
                   <tr valign="top">
                       <th scope="row"><label for="font">Font</label></th>
                       <td><input name="showts_options[font]" type="text" id="font" value="<?php echo $show_font ?>" class="regular-text" /><br />
                           <span class="subdescription">Please select font from your registered fonts (<a href="http://typesquare.com/service/fontlist" target="_blank">Font list</a>).</span></td>
                   </tr>
                   <tr valign="top">
                       <th scope="row"><label for="element">Font</label></th>
                       <td><input name="showts_options[element]" type="text" id="element" value="<?php echo $show_element ?>" class="regular-text" /><br />
                           <span class="subdescription">Please select elements which use TypeSquare fonts (e.g. "h1, .page_item"). </span></td>
                   </tr>
                   <tr valign="top">
                       <th scope="row"><label for="fadein">Enable fade-in</label></th>
                       <td><input name="showts_options[usefadein]" type="checkbox" id="usefadein" value="1" onclick="fadeincheck();"  <?php if ($show_usefadein) echo('checked'); ?> /></td>
                   </tr>
                   <tr valign="top">
                       <th scope="row"><label for="fadein">Fade-in duration</label></th>
                       <td><input name="showts_options[fadein]" type="number" id="fadein" value="<?php echo $show_fadein ?>" min="0" max="100" step="10" <?php if (!$show_usefadein) echo('disabled'); ?> /></td>
                   </tr>
               </table>
               <p class="submit"><input type="submit" name="Submit" class="button-primary" value="Save changes" /></p>
           </form>
       <!-- /.wrap --></div>
       <?php
   
}

add_action('admin_menu', 'register_tsmenu');
