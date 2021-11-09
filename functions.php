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

//child theme bookeeping.
add_action( 'wp_enqueue_scripts', 'wpg_frontend_style',20 );
function wpg_frontend_style(){
  wp_enqueue_style( 'twenty-nineteen-css', get_template_directory_uri() . '/style.css' );
  $theme_folder = get_stylesheet_directory_uri();
  // wp_enqueue_style('wpg-frontend-css', $theme_folder.'/css/main.css');
}
