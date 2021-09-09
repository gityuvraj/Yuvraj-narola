<?php 
/*
 * @wordpress-plugin
 * Plugin Name:     NISL
 * Description:     Another custom contact form plugin.
 * Version:         1.0.0
 * Author:          Narola Infotech
 */

require_once(ABSPATH . 'wp-content/plugins/Nisl Contact/delet.php');

function narola_contact_form()
{
	/* create a string variable to hold the content */
	$content = ''; /* create a string */

	$content .= '<form method="post" action="" enctype="multipart/form-data" name="con" class="">';

	$content .= '<style></style>';

	$content .= '<div id="response_div"></div>';

	$content .= '<div class="narola_form">';
	$content .= '<label for="your_name">Name</label>';
	$content .= '<input type="text" name="your_name" id="your_name" placeholder="Name" />';

	$content .= '<label for="your_email">Email </label>';
	$content .= '<input type="email" name="your_email" id="your_email" placeholder="Email" /><br>';

	$content .= '<div style="padding:15px 0px 4px 0px; width:400px;">';
	$content .= '<select id="source" name="source">';
	$content .= '<option value="">Source/Medium</option>';
	$content .= '<option value="Seach Engin">Seach Engin</option>';
	$content .= '<option value="Email">Email</option>';
	$content .= '<option value="Social Media">Social Media</option>';
	$content .= '<option value="Referance">Referance</option>';
	$content .= '<option value="Other">Other</option>';
	$content .= '</select>';
	$content .= '</div>';

	//echo get_option('select_source');

	$content .= '<label for="phone_number">Phone</label>';
	$content .= '<input type="text" name="phone_number" id="phone_number" placeholder="Phone" />';

	$content .= '<label for="your_comments">Type Message</label>';
	$content .= '<textarea name="your_comments" id="your_comments" placeholder="Type Message"></textarea>';

	$content .= '<label for="your_comments">Drag & Drop files here</label>';
	$content .= '<input type="file" name="image"><br>';
	$content .= '<br /><div class="g-recaptcha brochure__form__captcha" data-sitekey="6LdzFzscAAAAAJJ0bFisu9KPSCpwm4m-7sMuyZkV"></div>';
	$content .= '<br /><input type="submit" name="contact_submit" id="contact_submit"  value="SUBMIT" />';

	$content .= '</div>';

	$content .= '</form>';

	return $content;

}
add_shortcode('contact_form','narola_contact_form');
/* createed shorcode for get form */
/* add ajax in contact form */
function narola_add_javascript()
{
	?>
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
	<script src="http://localhost/Narola/Narola-contact/wp-content/plugins/Nisl Contact/js/narola.js"></script>
	
	<?php	
}
add_action('wp_footer','narola_add_javascript');

/* Include custom css file*/
add_action('wp_enqueue_scripts', 'enqueue_my_scripts');
function enqueue_my_scripts() {

	wp_enqueue_style('main-style', plugin_dir_url(__FILE__) . 'css/main-style.css');
		
}

/*create table */
register_activation_hook( __FILE__, 'crudOperationsTable');
function crudOperationsTable() {
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  $table_name = $wpdb->prefix . 'contactstable';
  $sql = "CREATE TABLE `$table_name` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `your_name` varchar(220) DEFAULT NULL,
  `your_email` varchar(220) DEFAULT NULL,
  `phone_number` varchar(220) DEFAULT NULL,
  `your_comments` varchar(220) DEFAULT NULL,
  `source` varchar(220) DEFAULT NULL,
  `image` varchar(220) DEFAULT NULL,


  PRIMARY KEY(user_id)
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
  ";
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
}


add_action('admin_menu', 'addAdminPageContent');
function addAdminPageContent() {
  add_menu_page('Nisl Contact', 'Nisl Contact', 'manage_options', __FILE__, 'contactAdminPage', 'dashicons-wordpress');
    add_submenu_page(__FILE__, 'Email Settings', 'Email Settings', 'manage_options', __FILE__.'/custom', 'adminEmailsetting');
 	//add_submenu_page(__FILE__, 'Add Field', 'Add Field', 'manage_options', __FILE__.'/manage', 'adminAddfield');
 	//add_action( 'admin_init', 'register_plugin_settings' );
  
}

 
/* insert detail in database table*/
global $wpdb;
  $table_name = $wpdb->prefix . 'contactstable';
  if (isset($_POST['contact_submit'])) {
    $name = $_POST['your_name'];
    $email = $_POST['your_email'];
	$sour = $_POST['source'];
	$phone = $_POST['phone_number'];
	$message = $_POST['your_comments'];


	 $imgName = $_FILES['image']['name'];
	 $imgTmp = $_FILES['image']['tmp_name'];
	 $imgSize = $_FILES['image']['size'];

//	$select_source = $_POST['consource'];
    $wpdb->query("INSERT INTO $table_name(your_name,your_email,phone_number,your_comments,source,image) VALUES('$name','$email','$phone','$message','$sour','$imgName')");
    
	}
  

/* get all contact details in backend  */
 
 function contactAdminPage() {
  
	if (isset($_GET['del'])) {
		$del_id = $_GET['del'];
		$wpdb->query("DELETE FROM $table_name WHERE user_id='$del_id'");
		
		echo "<script>location.replace('admin.php?page=delet.php');</script>";
	  }
  
  ?>
  <div class="wrap">
    <h2>Contact Details</h2>
    <table class="wp-list-table widefat striped">
      <thead>
        <tr>
          <th width="25%">User ID</th>
          <th width="25%">Name</th>
          <th width="25%">Email</th>
		  <th width="25%">Source/Medium</th>
          <th width="25%">Phone</th>
		  <th width="25%">Message</th>
		  <th width="25%">File</th>
        </tr>
      </thead>
      
    </table>
    <br>
    <br>
  <?php
  global $wpdb;
  $table_name = $wpdb->prefix . 'contactstable';
	$result = $wpdb->get_results("SELECT * FROM $table_name");
  
  foreach ($result as $print) {?>
	<table class="wp-list-table widefat striped">
	<thead>
	<?php
    echo "
      <tr>
        <td width='25%'>$print->user_id</td>
        <td width='25%'>$print->your_name</td>
        <td width='25%'>$print->your_email</td>
		<td width='25%'>$print->source</td>
		<td width='25%'>$print->phone_number</td>
		<td width='25%'>$print->your_comments</td>
		<td width='25%'>$print->image</td>

      </tr>
    ";
  }?>
  
	</thead>
      
	  </table>
  </div>
  <?php
 }
 

/* Admin side email template setting  */
//1
function adminEmailsetting(){
   ?>
   <div class='wrap'>
   
    
    <div class="wrap">
	<?php settings_errors();?>
		
		<form method="post" action="options.php">
			<?php 
			settings_fields( 'email-templates' );	//pass slug name of page, also referred
                        
			do_settings_sections( 'email-templates' ); 	//pass slug name of page
			submit_button();
			?>
		</form>
	</div>
   </div>
   <?php
 }	

//2

function narola_email_template_settings() {
 	// Section 
 	add_settings_section(
		'email_templates_section',
		'Email Templates',
		'narola_email_templates_section',
		'email-templates'
	);
 	
 	// Field 
 	add_settings_field(
		'email_template_user',
		'Message Body :',
		'email_template_user_cb',
		'email-templates',
		'email_templates_section'
	);
 	
 	// Value under which we are saving the data by $_POST
 	register_setting( 'email-templates', 'email_template_user' );
 	register_setting( 'email-templates', 'subject' );
 	register_setting( 'email-templates', 'from' );
 }
 //3
 add_action( 'admin_init', 'narola_email_template_settings' );

 function narola_email_templates_section() {
 	echo '';
 }

 function email_template_user_cb() {
 	$content = ''; /* create a string */

 	$content = get_option('email_template_user');
 	wp_editor( $content, 'email_template_user' );

 	$content = get_option('subject');
 	$content = get_option('from');
 	
	?>
	<tr valign="top">
			  
	    <th scope="row"><strong style="font-size:14px;color:#002B2B">From :</strong></th>
		<td><input  style="width:70%;height:40px" type="text" name="from" value="<?php echo esc_attr( get_option('from') ); ?>"></td>				   
			  			   
	</tr>
	<tr valign="top">
			  
		<th scope="row"><strong style="font-size:14px;color:#002B2B">Subject :</strong></th>
		<td><input  style="width:70%;height:40px" type="text" name="subject" value="<?php echo esc_attr( get_option('subject') ); ?>"></td>				   
			  			   
	</tr>

	<?php
 }


// manage field from backend

add_action('admin_menu', 'nisl_plugin_create_menu');
function nisl_plugin_create_menu() 
{

	add_submenu_page(__FILE__, 'Manage field', 'Manage fields', 'manage_options', __FILE__.'/manage', 'other_theme_settings_page');
	add_action( 'admin_init', 'register_plugin_settings' );
}


/*register options*/    
function register_plugin_settings()
{
	register_setting( 'my-cool-plugin-settings-group', 'select_source' );
}

function other_theme_settings_page() 
{

if (isset($_POST['submit'])) {

   $manage_source=esc_attr(get_option('select_source'));



}	

?>
<div class="wrap">
	<?php settings_errors();?>
	<form method="post" action="options.php" class="manage-field">
		<table class="source-form">
		<?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
		<?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
	   
			  <h2 style="text-align:center;font-size:25px"> Manage field Settings</h2>
			 
			<?php

			$content_option = get_option('select_source');
 			//wp_editor( $content, 'select_source' );
			?>
			 <tr id="row_1">
			 	<td>
				 <label><?php _e('Select Source :');?></label>	
 					<input  style="width:40%;height:40px" type="text" name="select_source[]"   value="">
 				</td>
 			</tr>
			
		</table>	
		<input  type="hidden" id="row_count" name="row_count" value="1">
		<p><a href="javascript:void(0)" type="button" id="add_source" class="button-primary">Add Source</a></p>			
    	<?php submit_button(); ?>
	</form>
</div>

<?php 
}

add_action('admin_footer', 'admin_side_javascript');

function admin_side_javascript() {
	?>
<script type="text/javascript">
jQuery(document).ready(function(){
var sour = jQuery('#row_count').val();
var content = '';
 jQuery('#add_source').click(function(){

    sour++;
    content += "<tr id='row_"+sour+"'>";
    content += "<td>";
    content += "<input  type='text' style='width:50%;height:40px'  name='select_source[]' value='' />";
    content += "<button type='button' name='remove-source' id='"+sour+"' class='btn btn-danger remove_source'>X</button>";
    content += "</td>"; 
    content += "</tr>";   

    jQuery('.source-form').append(content);
    jQuery('#row_count').val(sour);

    

  });

 jQuery(document).on('click', '.remove_source', function(){
           var remove_id = jQuery(this).attr("id");
           var its_count = parseInt(jQuery('#row_count').val());
           jQuery('#row_'+remove_id+'').remove();
           jQuery('#row_count').val(its_count - 1);
      });

});

	
</script>
<?php
}