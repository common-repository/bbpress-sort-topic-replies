<?php 
/*
Plugin Name: bbPress - Sort topic replies
Description: Sort topic replies in ascending or descending order for each bbPress forum and topic
Author: Atif N
Version: 1.0.3
Author URI: https://atif.rocks
*/

// No direct access
if ( !defined( 'ABSPATH' ) ) exit;

// Sort the replies
// Checks if the topic bieng loaded belongs to a forum that has be checked to show replies sorted in descending order
// If True, sorts all the replies in descending order on datetime
add_filter('bbp_has_replies_query','bbp_reverse_reply_order');
function bbp_reverse_reply_order( $query = array() ) {
	// Identify post type
	$bbPress_post_id = get_the_ID();
	$bbPress_post_type = get_post_type($bbPress_post_id);
	
	
	if( $bbPress_post_type =='topic' ){
		
		// Get global settings
		$global_bbPress_option = get_option('_bbp_sort_desc_global');
		$global_bbPress_option_no_parent = get_option('_bbp_sort_desc_global_no_parent');
		
		// Get forum for the current topic
		$bbPress_forum_id = get_post_meta($bbPress_post_id, '_bbp_forum_id', true);
		
		// Get sort setting for the current topic
		$bbPress_sort_status = get_post_meta($bbPress_post_id, '_bbp_topic_sort_desc', true);
		
		/** TOPIC **/
		if( $bbPress_sort_status == 'desc' ){
			$query['order']='DESC';
			return $query;
		}elseif( $bbPress_sort_status == 'asc' ){
			$query['order']='ASC';
			return $query;
		}
		
		/** FORUM 
		*** Apply the settings set for the current topic's forum **/
		$bbPress_sort_status = get_post_meta($bbPress_forum_id, '_bbp_sort_desc', true);
		if( $bbPress_sort_status == 'desc' ){
			$query['order']='DESC';
			return $query;
		}if( $bbPress_sort_status == 'asc' ){
			$query['order']='ASC';
			return $query;
		}
		
		/** GLOBAL
		 *  Apply if Topic and Forum setting are not set **/
		if($bbPress_forum_id==$bbPress_post_id){
			//If forum has no parent, apply global setting for topics with no parent
			if( $global_bbPress_option_no_parent == 'desc' ){
				$query['order']='DESC';
				return $query;
			}elseif( $global_bbPress_option_no_parent == 'asc' ){
				$query['order']='ASC';
				return $query;
			}
		}else{
			// If global setting found, apply to the current page
			if( $global_bbPress_option == 'desc' ){
				$query['order']='DESC';
				return $query;
			}elseif( $global_bbPress_option == 'asc' ){
				$query['order']='ASC';
				return $query;
			}
		}
	}
	return $query;
}

// Show Lead Topic Filter
function srt_bbp_show_lead_topic( $show_lead ) {
	// Identify post type
	$bbPress_post_id = get_the_ID();
	
	// Get show lead status for the topic
	$bbPress_topic_sort_show_lead_topic = get_post_meta($bbPress_post_id, '_bbp_topic_sort_show_lead_topic', true);
	
	// Get show lead status for the forum
	$bbPress_topic_sort_show_lead_topic_forum = get_post_meta($bbPress_post_id, '_bbp_topic_sort_show_lead_topic_forum', true);
	
	//Get global show lead status for the topic
	$global_bbPress_show_lead = get_option('_bbp_sort_show_lead_topic');
	
	// TOPIC
	// If option set at individual post level
	if( $bbPress_topic_sort_show_lead_topic == 'yes' ){
		$show_lead[] = 'true';
		return $show_lead;
	}elseif( $bbPress_topic_sort_show_lead_topic == 'no' ){
		return $show_lead;
	}

	// FORUM
	// If option set at forum level
	if( $bbPress_topic_sort_show_lead_topic_forum == 'yes' ){
		$show_lead[] = 'true';
		return $show_lead;
	}elseif( $bbPress_topic_sort_show_lead_topic_forum == 'no' ){
		return $show_lead;
	}
	
	// Global
	// Global option
	if( $global_bbPress_show_lead=='yes' ){
		$show_lead[] = 'true';
		return $show_lead;
	}
}
add_filter('bbp_show_lead_topic', 'srt_bbp_show_lead_topic' );


// Adds meta box to the side bar of the Forums edit pages
add_action( 'add_meta_boxes', 'bbPress_meta_box_add' );
function bbPress_meta_box_add(){
	add_meta_box( 'bbPress_meta_box_sort_desc', 'Sort Replies', 'bbPress_forum_meta_box', 'forum', 'side', 'high' );
}

// The content of the meta box
function bbPress_forum_meta_box($post){
	$bbPress_sort_status = get_post_meta($post->ID, '_bbp_sort_desc', true);
	$bbPress_topic_sort_show_lead_topic_forum = get_post_meta($post->ID, '_bbp_topic_sort_show_lead_topic_forum', true);
	?>
	<p>
		<strong class="label">Order by</strong>
		<select name="str-bbpress-sort-replies" id="str-bbpress-sort-replies" >
			<option value="dft" <?php echo $bbPress_sort_status=="dft"?"selected='selected'":""; ?>>Default</option>
			<option value="asc" <?php echo $bbPress_sort_status=="asc"?"selected='selected'":""; ?>>Ascending</option>
			<option value="desc" <?php echo $bbPress_sort_status=="desc"?"selected='selected'":""; ?>>Descending</option>
		</select>
	</p>
	<hr>
	<p>
		<strong class="label">Show Lead</strong>
		<select name="str-bbpress-sort-replies-show-lead-topic-forum" id="str-bbpress-sort-replies-show-lead-topic-forum" >
			<option value="dft" <?php echo $bbPress_topic_sort_show_lead_topic_forum=="dft"?"selected='selected'":""; ?>>Default</option>
			<option value="yes" <?php echo $bbPress_topic_sort_show_lead_topic_forum=="yes"?"selected='selected'":""; ?>>Yes</option>
			<option value="no" <?php echo $bbPress_topic_sort_show_lead_topic_forum=="no"?"selected='selected'":""; ?>>No</option>
		</select>
	</p>
	<?php 
}

// Adds meta box to the side bar of the Topic edit pages
add_action( 'add_meta_boxes', 'bbPress_topic_meta_box_add' );
function bbPress_topic_meta_box_add(){
	add_meta_box( 'bbPress_meta_box_topic_sort_desc', 'Sort Replies', 'bbPress_topic_meta_box', 'topic', 'side', 'high' );
}

// The content of the meta box
function bbPress_topic_meta_box($post){
	$bbPress_topic_sort_status = get_post_meta($post->ID, '_bbp_topic_sort_desc', true);
	$bbPress_topic_sort_show_lead_topic = get_post_meta($post->ID, '_bbp_topic_sort_show_lead_topic', true);
	?>
	<p>
		<strong class="label">Order by</strong>
		<select name="str-bbpress-topic-sort-replies" id="str-bbpress-topic-sort-replies" >
			<option value="dft" <?php echo $bbPress_topic_sort_status=="dft"?"selected='selected'":""; ?>>Default</option>
			<option value="asc" <?php echo $bbPress_topic_sort_status=="asc"?"selected='selected'":""; ?>>Ascending</option>
			<option value="desc" <?php echo $bbPress_topic_sort_status=="desc"?"selected='selected'":""; ?>>Descending</option>
		</select>
	</p>
	<hr>
	<p>
		<strong class="label">Show Lead</strong>
		<select name="str-bbpress-sort-replies-show-lead-topic" id="str-bbpress-sort-replies-show-lead-topic" >
			<option value="dft" <?php echo $bbPress_topic_sort_show_lead_topic=="dft"?"selected='selected'":""; ?>>Default</option>
			<option value="yes" <?php echo $bbPress_topic_sort_show_lead_topic=="yes"?"selected='selected'":""; ?>>Yes</option>
			<option value="no" <?php echo $bbPress_topic_sort_show_lead_topic=="no"?"selected='selected'":""; ?>>No</option>
		</select>
	</p>
	
	<?php 
}

// Saves the meta box settings when the page is saved/updated/published
add_action( 'save_post', 'bbPress_meta_box_save' );
function bbPress_meta_box_save($post_id){
	if( isset($_POST['save']) ){
		// Save sort settings for topic
		if( $_POST['str-bbpress-topic-sort-replies'] == "desc" ){
			update_post_meta($post_id, '_bbp_topic_sort_desc', 'desc');
		}elseif( $_POST['str-bbpress-topic-sort-replies'] == "asc" ){
			update_post_meta($post_id, '_bbp_topic_sort_desc', 'asc');
		}else{
			update_post_meta($post_id, '_bbp_topic_sort_desc', 'dft');
		}
		
		// Save sort settings for forum
		if( $_POST['str-bbpress-sort-replies'] == "desc" ){
			update_post_meta($post_id, '_bbp_sort_desc', 'desc');
		}elseif( $_POST['str-bbpress-sort-replies'] == "asc" ){
			update_post_meta($post_id, '_bbp_sort_desc', 'asc');
		}else{
			update_post_meta($post_id, '_bbp_sort_desc', 'dft');
		}
		
		// Save show lead settings for the topic
		if( $_POST['str-bbpress-sort-replies-show-lead-topic'] == "yes" ){
			update_post_meta($post_id, '_bbp_topic_sort_show_lead_topic', 'yes');
		}elseif( $_POST['str-bbpress-sort-replies-show-lead-topic'] == "no" ){
			update_post_meta($post_id, '_bbp_topic_sort_show_lead_topic', 'no');
		}else{
			update_post_meta($post_id, '_bbp_topic_sort_show_lead_topic', 'dft');
		}
		
		// Save show lead settings for the forum
		if( $_POST['str-bbpress-sort-replies-show-lead-topic-forum'] == "yes" ){
			update_post_meta($post_id, '_bbp_topic_sort_show_lead_topic_forum', 'yes');
		}elseif( $_POST['str-bbpress-sort-replies-show-lead-topic-forum'] == "no" ){
			update_post_meta($post_id, '_bbp_topic_sort_show_lead_topic_forum', 'no');
		}else{
			update_post_meta($post_id, '_bbp_topic_sort_show_lead_topic_forum', 'dft');
		}
	}
}

// Add Submenu page under settings
function add_forums_sort_options_page(){
	if( isset($_POST['forums_sort_options_save']) ){
		// Save global settings
		if( $_POST['str-bbpress-sort-replies-global'] == "desc" ){
			update_option('_bbp_sort_desc_global', 'desc');
		}elseif( $_POST['str-bbpress-sort-replies-global'] == "asc" ){
			update_option('_bbp_sort_desc_global', 'asc');
		}else{
			update_option('_bbp_sort_desc_global', 'dft');
		}
		// Save No Forum global settings
		if( $_POST['str-bbpress-sort-replies-global-no-parent'] == "desc" ){
			update_option('_bbp_sort_desc_global_no_parent', 'desc');
		}elseif( $_POST['str-bbpress-sort-replies-global-no-parent'] == "asc" ){
			update_option('_bbp_sort_desc_global_no_parent', 'asc');
		}else{
			update_option('_bbp_sort_desc_global_no_parent', 'dft');
		}
		
		// Save topic settings
		if( $_POST['str-bbpress-sort-replies-show-lead-topic'] == "yes" ){
			update_option('_bbp_sort_show_lead_topic', 'yes');
		}elseif( $_POST['str-bbpress-sort-replies-show-lead-topic'] == "no" ){
			update_option('_bbp_sort_show_lead_topic', 'no');
		}else{
			update_option('_bbp_sort_show_lead_topic', 'dft');
		}
	}
	
	$bbPress_sort_status_global = get_option('_bbp_sort_desc_global');
	$bbPress_sort_status_global_no_parent = get_option('_bbp_sort_desc_global_no_parent');
	$bbPress_sort_show_lead_topic = get_option('_bbp_sort_show_lead_topic');
	?>
	<form method="post" action="">
		
		<div class="metabox-holder">
			<div class="postbox">
				<h3 class="hndle">
					<span>Forums Sort Options</span>
				</h3>
				<div class="inside">
					<div class="main">
					
						<p>Choose the setting that best suit your needs</p>
						<table class="form-table">
				            <tbody>
				            <tr>
				                <th>Global:</th>
				                <td>
				                   <select name="str-bbpress-sort-replies-global" id="str-bbpress-sort-replies-global" >
										<option value="dft" <?php echo $bbPress_sort_status_global=="dft"?"selected='selected'":""; ?>>Default</option>
										<option value="asc" <?php echo $bbPress_sort_status_global=="asc"?"selected='selected'":""; ?>>Ascending</option>
										<option value="desc" <?php echo $bbPress_sort_status_global=="desc"?"selected='selected'":""; ?>>Descending</option>
									</select>
				                    <br><span class="description">Overrides and applies to all forums if global is set other than default.</span>
				                </td>
				            </tr>
				            <tr>
				                <th>No Parent:</th>
				                <td>
				                    <select name="str-bbpress-sort-replies-global-no-parent" id="str-bbpress-sort-replies-global-no-parent" >
										<option value="dft" <?php echo $bbPress_sort_status_global_no_parent=="dft"?"selected='selected'":""; ?>>Default</option>
										<option value="asc" <?php echo $bbPress_sort_status_global_no_parent=="asc"?"selected='selected'":""; ?>>Ascending</option>
										<option value="desc" <?php echo $bbPress_sort_status_global_no_parent=="desc"?"selected='selected'":""; ?>>Descending</option>
									</select>
				                    <br><span class="description">This setting applies only to topics that have no Parent Forum</span>	
				                </td>
				            </tr>
				            <tr>
				                <th>Always show lead Topic</th>
				                <td>
				                    <select name="str-bbpress-sort-replies-show-lead-topic" id="str-bbpress-sort-replies-show-lead-topic" >
										<option value="dft" <?php echo $bbPress_sort_show_lead_topic=="dft"?"selected='selected'":""; ?>>Default</option>
										<option value="yes" <?php echo $bbPress_sort_show_lead_topic=="yes"?"selected='selected'":""; ?>>Yes</option>
										<option value="no" <?php echo $bbPress_sort_show_lead_topic=="no"?"selected='selected'":""; ?>>No</option>
									</select>
				                    <br><span class="description">Shows the original post at the top of each page</span>	
				                </td>
				            </tr>
				            </tbody>
				    	</table>
				    	
				    	<p class="submit">
							<input type="submit" name="forums_sort_options_save" class="button-primary" value="Save settings">
						</p>
				    	
					</div>
				</div>
			</div>
		</div>	
		
	</form>
	<?php 
}
function call_add_submenu_for_ForumsSortOptions(){
	add_submenu_page('options-general.php', 'Forums Sort Options', 'Forums Sort Options', 'publish_pages', 'forums_sort_options', 'add_forums_sort_options_page');
}
add_action('admin_menu', 'call_add_submenu_for_ForumsSortOptions');
?>