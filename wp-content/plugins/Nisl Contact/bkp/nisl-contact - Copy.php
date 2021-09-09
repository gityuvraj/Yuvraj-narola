<?php 
/**
* Plugin Name: Narola Contact
* 
**/
require_once(ABSPATH . 'wp-content/plugins/Nisl Contact/delet.php');

function ideapro_contact_form()
{
	/* create a string variable to hold the content */
	$content = ''; /* create a string */

	$content .= '<form method="post" action="" enctype="multipart/form-data" name="con">';

	$content .= '<style>.ideapro_form label { display:block; padding:15px 0px 4px 0px; } .ideapro_form input[type=text],input[type=email] { width:400px; padding:8px; } .ideapro_form textarea { width:400px; height:200px; padding:8px;}</style>';

	$content .= '<div id="response_div"></div>';

	$content .= '<div class="ideapro_form">';
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
	$content .= '<input type="file" name="file">';

	?>
	<div class="recap"><?php
	 echo do_shortcode('[cf7sr-simple-recaptcha]');

	 //echo get_option('email_template_user');
	  //echo get_option('subject');
?>
</div>
<?php
	$content .= '<br /><br /><input type="submit" name="ideapro_contact_submit" id="ideapro_contact_submit"  value="SUBMIT" />';

	$content .= '</div>';

	$content .= '</form>';
	// echo get_site_url() .'/wp-admin/admin.php?page=delet.php';
	return $content;

	

}
add_shortcode('contact_form','ideapro_contact_form');
/* createed shorcode for get form */
/* add ajax in contact form */
function ideapro_add_javascript()
{
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
	<script src="http://localhost/Narola/Narola-contact/wp-content/plugins/Nisl Contact/js/ideapro.js"></script>
	
	<?php	
}
add_action('wp_footer','ideapro_add_javascript');


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
    add_submenu_page(__FILE__, 'Email Setting', 'Email Setting', 'manage_options', __FILE__.'/custom', 'adminEmailsetting');
 	//add_submenu_page(__FILE__, 'Add Field', 'Add Field', 'manage_options', __FILE__.'/manage', 'adminAddfield');
 	//add_action( 'admin_init', 'register_my_cool_plugin_settings' );
  
}

 

global $wpdb;
  $table_name = $wpdb->prefix . 'contactstable';
  if (isset($_POST['ideapro_contact_submit'])) {
    $name = $_POST['your_name'];
    $email = $_POST['your_email'];
	$sour = $_POST['source'];
	$phone = $_POST['phone_number'];
	$message = $_POST['your_comments'];
//	$select_source = $_POST['consource'];
    $wpdb->query("INSERT INTO $table_name(your_name,your_email,phone_number,your_comments,source) VALUES('$name','$email','$phone','$message','$sour')");
    
  }
  

/* get all contact details in backend  */
 
 function contactAdminPage() {
  
	if (isset($_GET['del'])) {
		$del_id = $_GET['del'];
		$wpdb->query("DELETE FROM $table_name WHERE user_id='$del_id'");
		
		echo "<script>location.replace('admin.php?page=crud.php');</script>";
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
		
      </tr>
    ";
  }?>
  
	</thead>
      
	  </table>
  </div>
  <?php
 }
 



/* Admin side email template  */
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

function ibenic_email_template_settings() {
 	// Section 
 	add_settings_section(
		'email_templates_section',
		'Email Templates',
		'ibenic_email_templates_section',
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
 add_action( 'admin_init', 'ibenic_email_template_settings' );

 function ibenic_email_templates_section() {
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


// email.


// manage field from backend


add_action('admin_menu', 'my_cool_plugin_create_menu');
function my_cool_plugin_create_menu() 
{

	//add_submenu_page('Theme Settings', 'Theme Settings', 'manage_options', __FILE__, 'other_theme_settings_page' ,'',20 );
	add_submenu_page(__FILE__, 'Manage field', 'Manage field', 'manage_options', __FILE__.'/manage', 'other_theme_settings_page');
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


    
function register_my_cool_plugin_settings()
{
	register_setting( 'my-cool-plugin-settings-group', 'select_source' );
	//register_setting( 'my-cool-plugin-settings-group', 'color' );

}

function other_theme_settings_page() 
{
?>
<div class="wrap">
	<?php settings_errors();?>
	<form method="post" action="options.php">
		<?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
		<?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
	   
			  <h2 style="text-align:center;font-size:25px"> Manage field Settings</h2>
			 
			<?php

			$content = get_option('select_source');
 	wp_editor( $content, 'select_source' );
			?>
		
			<tr>
				<td style="border-bottom: 3px solid grey">					
				</td>
			</tr>		
			 			
			
		</table>			
    	<?php submit_button(); ?>
	</form>
</div>

<?php 
}

class Smashing_Fields_Plugin {
    // Our code will go here

public function __construct() {
    // Hook into the admin menu
    add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
    add_action( 'admin_init', array( $this, 'setup_sections' ) );
    add_action( 'admin_init', array( $this, 'setup_fields' ) );
}

public function create_plugin_settings_page() {
    // Add the menu item and page
    $page_title = 'My Awesome Settings Page';
    $menu_title = 'Awesome Plugin';
    $capability = 'manage_options';
    $slug = 'smashing_fields';
    $callback = array( $this, 'plugin_settings_page_content' );
    $icon = 'dashicons-admin-plugins';
    $position = 100;

    //add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
}

public function plugin_settings_page_content() { ?>
    <div class="wrap">
        <h2>My Awesome Settings Page</h2>
        <form method="post" action="options.php">
            <?php
                settings_fields( 'smashing_fields' );
                do_settings_sections( 'smashing_fields' );
                submit_button();
            ?>
        </form>
    </div> <?php
}

public function setup_sections() {
    add_settings_section( 'our_first_section', 'My First Section Title', array( $this, 'section_callback' ), 'smashing_fields' );
    //add_settings_section( 'our_second_section', 'My Second Section Title', array( $this, 'section_callback' ), 'smashing_fields' );
    //add_settings_section( 'our_third_section', 'My Third Section Title', array( $this, 'section_callback' ), 'smashing_fields' );


}
public function section_callback( $arguments ) {
    switch( $arguments['id'] ){
        case 'our_first_section':
            echo 'This is the first description here!';
            break;
        case 'our_second_section':
            echo 'This one is number two';
            break;
        case 'our_third_section':
            echo 'Third time is the charm!';
            break;
    }
}

public function setup_fields() {
    $fields = array(
        array(
            'uid' => 'our_first_field',
            'label' => 'Awesome Date',
            'section' => 'our_first_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => 'DD/MM/YYYY',
            'helper' => 'Does this help?',
            'supplemental' => 'I am underneath!',
            'default' => '01/01/2015'
        )
    );
    foreach( $fields as $field ){
        add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'smashing_fields', $field['section'], $field );
        register_setting( 'smashing_fields', $field['uid'] );
    }

    $fields = array(
    array(
        'uid' => 'our_first_field',
        'label' => 'Awesome Date',
        'section' => 'our_first_section',
        'type' => 'text',
        'options' => false,
        'placeholder' => 'DD/MM/YYYY',
        'helper' => 'Does this help?',
        'supplemental' => 'I am underneath!',
        'default' => '01/01/2015'
    ),
    array(
        'uid' => 'our_second_field',
        'label' => 'Awesome Date',
        'section' => 'our_first_section',
        'type' => 'textarea',
        'options' => false,
        'placeholder' => 'DD/MM/YYYY',
        'helper' => 'Does this help?',
        'supplemental' => 'I am underneath!',
        'default' => '01/01/2015'
    ),
    array(
        'uid' => 'our_third_field',
        'label' => 'Awesome Select',
        'section' => 'our_first_section',
        'type' => 'select',
        'options' => array(
            'yes' => 'Yeppers',
            'no' => 'No way dude!',
            'maybe' => 'Meh, whatever.'
        ),
        'placeholder' => 'Text goes here',
        'helper' => 'Does this help?',
        'supplemental' => 'I am underneath!',
        'default' => 'maybe'
    )
);
}


public function field_callback( $arguments ) {
    $value = get_option( $arguments['uid'] ); // Get the current value, if there is one
    if( ! $value ) { // If no value exists
        $value = $arguments['default']; // Set to our default
    }

    // Check which type of field we want
    switch( $arguments['type'] ){
        case 'text': // If it is a text field
            printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
            break;
    }

    switch( $arguments['type'] ){
    case 'text': // If it is a text field
        printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
        break;
    case 'textarea': // If it is a textarea
        printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
        break;
    case 'select': // If it is a select dropdown
        if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
            $options_markup = ’;
            foreach( $arguments['options'] as $key => $label ){
                $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
            }
            printf( '<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup );
        }
        break;
}


    // If there is help text
    if( $helper = $arguments['helper'] ){
        printf( '<span class="helper"> %s</span>', $helper ); // Show it
    }

    // If there is supplemental text
    if( $supplimental = $arguments['supplemental'] ){
        printf( '<p class="description">%s</p>', $supplimental ); // Show it
    }
}





}
new Smashing_Fields_Plugin();


 ?>
 <script>
 function deleteRecord(id)
 {
	 var x=confirm("Do You want to Delete");
	 if(x==true)
	 {
		 window.location="delet.php?did="+id;
	 }
 }
</script>

