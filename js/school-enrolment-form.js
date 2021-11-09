(function($){
  /*
  Place your custom JavaScript code in this file.
  It will be loaded on the pages where your form is displayed.
  The file is saved in your theme folder: /wp-content/themes/wpg-2019/js/school-enrolment-form.js  */
  /* event fired once the tabs has been initialised, click to insert helper code into your js file. */
  $('#cf7sg-form-school-enrolment-form form.wpcf7-form').on( 'sgTabsReady', function(e){
    let $form = $(this), $tabs = $(e.target);
  });

})(jQuery)
      