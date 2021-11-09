<?php // add the following lines of code to your functions.php file.

add_action('cf7_2_post_form_submitted_to_post', 'new_post_mapped',10,4);
/**
* Function to take further action once form has been submitted and saved as a post.  Note this action is only fired for submission which has been submitted as opposed to saved as drafts.
* @param string $post_id new post ID to which submission was saved.
* @param array $cf7_form_data complete set of data submitted in the form as an array of field-name=>value pairs.
* @param string $cf7form_key unique key to identify your form.
* @param array $submitted_files array of files submitted in the form, if any file fields are present.
*/
function new_post_mapped($post_id, $cf7_form_data, $cf7form_key, $submitted_files){
  //do something.
  if($cf7form_key == 'form-to-post'){
    //check if we have a valid $email.
    if(isset($_COOKIE['_opt-by-email'])){
      $otp = $_COOKIE['_opt-by-email'];
      $email = false;
      if(function_exists('get_email_by_otp')) $email = get_email_by_otp($otp);
      if( $email ) {
    		update_post_meta($post_id, 'author-email', $email);
    	}
    }
  }
}
add_filter('cf7_2_post_filter_user_draft_form_query', 'filter_user_post_for_prefill', 10, 3);
/**
* Function to filter the post query for current user in order to prefill form.
* For draft forms (using the save button), this query is configured to load the latest draft submission.
* However, this filter can be used to modify the query for  submitted form that need editing.
* @param Array $query_args array query args for function get_posts().
* @param String $post_type post type being mapped to.
* @param String $form_key unique key identifying the current form.
* @return Array array of query parameters (for more info see the WordPress codex page on get_posts).
*/
function filter_user_post_for_prefill($query_args, $post_type, $form_key){
  unset($query_args['meta_query']);
  return $query_args;
}
add_filter( 'cf7_2_post_filter_taxonomy_query', 'filter_taxonomy_terms',10, 6);
/**
* Function to filter the list of terms shown in a mapped taxonomy field.  For example if you have a select field you can restrict the options listed to a select set of terms.
* @param array $query an array of query attributes for the taxonomy.
* @param string $cf7_id  the form id being loaded.
* @param string $taxonomy the taxonomy slug being queried.
* @param string $field the field name in the form being loaded.
* @param string $cf7_key unique key identifying your form.
* @param mixed $branch an array of parent IDs for hierarchical taxonomies, else 0.
* @return array an array taxonomy query attributes (see codex page:https://developer.wordpress.org/reference/functions/get_terms/).
*/
function filter_taxonomy_terms($query, $cf7_id, $taxonomy, $field, $cf7_key, $branch){
  if($cf7_key == 'form-to-post' && 'post-categories'==$field){//verify this is the correct form.
    switch($query['parent']){
      case 0:
        $query['parent'] = 52;
        break;
      default: //this is an iteration through a child-term.
        $query = array(); //an emtpy array will stop loading their children.
        break;
    }
  }
  return $query;
}

add_filter('wpcf7_mail_tag_replaced', 'format_chackbox',10,4);
function format_chackbox($replaced, $submitted, $is_html, $mail_tag){
  //you can check if this is the right field if need be.
  if('my-checkbox-field' != $mail_tag->field_name()) return $replaced;
  //$submitted contains the raw submitted data.
  //$replaced is the formatted data, you can use either.
  $a = explode(',', $replaced);
  //check if you have multiple values and the email accepts html (set with the use html checkbox in the mail notification settings).
  if(is_array($a) && $is_html){
    $replaced = '<ul>'.PHP_EOL;
    foreach($a as $v) $replaced .= '<li>'.trim($v).'</li>'.PHP_EOL;
    $replaced .= '</ul>'.PHP_EOL;
  }
  return $replaced;
}
add_filter('cf7_2_post_filter-post_tag','filter_post_tag',10,3);
function filter_post_tag($value, $post_id, $form_data){

  return 26;
}

add_filter('cf7_2_post_filter-c2p-contact-us-title','filter_c2p_contact_us_title',10,3);
function filter_c2p_contact_us_title($value, $post_id, $form_data){
  $value = "Hello {$form_data['your-name']}";
  return $value;
}
// add_filter('cf7sg_preserve_cf7_data_schema', '__return_true');
// add_filter( 'cf7sg_dynamic_dropdown_default_value','kitchen_facilities_dynamic_default_option',10,3);
/**
* Filter dynamic dropdown default empty label.
* @param string $label the label for the default value, this is null by default and not shown.
* @param string $name the field name being populated.
* @param string $cf7_key  the form unique key.
* @return string the label for the default value, returning a non-null value with display this as the first option.
*/
function kitchen_facilities_dynamic_default_option($default, $name, $cf7_key){
  if('house-tabs'!==$cf7_key || 'kitchen-facilities' !== $name){
    return $default;
  }
  $default = 'Please select an option...';
  return $default;
}
add_filter( 'cf7sg_dynamic_dropdown_custom_options','searchpart_dynamic_options',10,3);
/**
* Filter dropdown options for dynamic drodpwn list of taxonomy terms.
* @param mixed $options the opttion to filter.
* @param string $name the field name being populated.
* @param string $cf7_key  the form unique key.
* @return mixed $options return either an array of <option value>=><option label> pairs or a html string of option elements which can be grouped if required.
*/
function searchpart_dynamic_options($options, $name, $cf7_key){
  debug_msg('here');
  if('test-form'!==$cf7_key || 'searchpart' !== $name){
    return $options;
  }
  //these are the label users will see when the dropdown opens.
  //you can group your options if need be. Let's assume you have an array of arrays of data to display in groups.
  // $data = ... //fetch your data, either from the database or some other source.
  $group_opts = array(
    'Aaa'=>'a', 'Baa'=>'b','Caa'=>'c','Daa'=>'d','Abb'=>'ab'
  );
    foreach($group_opts as $label=>$value){
      $options .= '<option value="'.$value.'">'.$label.'</option>';
    }

  return $options;
}
add_action('cf7sg_enqueue_custom_script-school-enrolment-form', 'load_cached_submissions');
function load_cached_submissions($script_id){
  if(isset($_GET['cf7sg'])){
    $data = get_transient('_cf7sg_'.$_GET['cf7sg']);
    // debug_msg($data, 'previously submittd ');
  }
}
//add_filter('wpcf7_display_message', 'change_submission_msg',10,2);
function change_submission_msg($message, $status){
  if('mail_sent_ok' == $status){
    $message= 'Your message was successfully sent, please search on <a href="http://wwww.google.com">Google</a>.';
    // debug_msg($message);
  }
  return $message;
}


add_filter( 'cf7sg_mailtag_cf7sg-form-house-rental-with-tables', 'filter_cf7_mailtag_cf7sg_form_house_rental_with_tables', 10, 3);
function filter_cf7_mailtag_cf7sg_form_house_rental_with_tables($tag_replace, $submitted, $cf7_key){
  /*the $tag_replace string to change*/
  /*the $submitted an array containing all submitted fields*/
  /*the $cf7_key is a unique string key to identify your form, which you can find in your form table in the dashboard.*/
  if('house-rental-with-tables'==$cf7_key ){
    $style = 'style="padding: 0 3px;border-collapse:collapse;border-bottom:1px solid black"';
    $tag_replace ='
    <table>
      <thead><tr><th '.$style.'>Guest Name</th><th <th '.$style.'>Senior</th></tr></thead>
      <tbody>';
    if(!empty($submitted['guest-name'])){
      $style = 'style="background-color:#e3e3e3"';
      $row=1;
      foreach($submitted['guest-name'] as $idx=>$guest){
        $tag_replace .='  <tr><td '.($row%2==0?$style:'').'>'.$guest.'</td><td '.($row%2==0?$style:'').'>'. (empty($submitted['senior-guest'][$idx]) ? '' : 'yes') . '</td></tr>'.PHP_EOL;
        $row++;
      }
    }
    $tag_replace .='
      </tbody>
    </table>
    ';
  }
  return $tag_replace;
}


// add_filter( 'cf7sg_dynamic_dropdown_taxonomy_query','kitchen_facilities_taxonomy_query',10,3);
/**
* Filter dropdown taxonomy query parameter.
* (see https://developer.wordpress.org/reference/classes/wp_term_query/__construct/)
* @param array $args array of taxonomy query attributes.
* @param string $name the field name being populated.
* @param string $cf7_key  the form unique key.
* @return array of query attributes.
*/
function kitchen_facilities_taxonomy_query($args, $name, $cf7_key){
  //these are the label users will see when the dropdown opens.
  if( 'kitchen-facilities' != $name){
    return $args;
  }
  //use only the child terms of a parent.
  $args['parent']=6;
  return $args;
}
add_action( 'wp_enqueue_scripts', 'wpg_frontend_style',20 );
function wpg_frontend_style(){
  wp_enqueue_style( 'twenty-nineteen-css', get_template_directory_uri() . '/style.css' );
  $theme_folder = get_stylesheet_directory_uri();
  wp_enqueue_style('wpg-frontend-css', $theme_folder.'/css/main.css');
}

/**
  * Enable booking page query variable to determine source.
  * Hooked to 'query_vars'
  *@since 2.4.0
  *@param array $vars array of variables.
  *@return array array of variables
  */
  function booking_query_source($vars){
    $vars[] =  'booking_source';
    return $vars;
  }
  // add_filter('query_vars', 'booking_query_source', 10, 1);
  /**
  * booking source query rewrite rule.
  * hooked to 'init'
  *@since 2.4.0
  */
  // use 	get_query_var('booking_source') to get request var.

  function booking_source_rewrite(){

    $page_id = get_option('booking_page','');
    if(function_exists('pll_current_language')){
      $page_id=pll_get_post($page_id, pll_current_language());
    }
    $page = get_post($page_id);
		if(!empty($page)){
	    add_rewrite_rule(
	      "{$page->post_name}/([^/]+)/?$",
	      'index.php?pagename='.$page->post_name.'&booking_source=$matches[1]',
	      'top'
	    );
      flush_rewrite_rules();
		}
  }
  // add_action('init', 'booking_source_rewrite');
