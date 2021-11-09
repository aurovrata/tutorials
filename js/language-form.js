(function($){
  /*
  Place your custom JavaScript code in this file.
  It will be loaded on the pages where your form is displayed.
  The file is saved in your theme folder: /wp-content/themes/wpg-2019/js/language-form.js  */
  let $table;
  $('#cf7sg-form-language-form form.wpcf7-form').on( 'sgTableReady', '.container.cf7-sg-table', function(e){
    /* event fired once a table has been initialised, click to insert helper code into your js file. */
    let $form = $(e.delegateTarget), $t = $(e.target);
    if('language-table' == $t.attr('id')) {
      $table = $t;
      $table.toggleCF7sgTableRowAddition(false); /* hide/show row addition button. */
      $('#cf7sg-form-language-form form.wpcf7-form').on( 'sgRowAdded', $table, function(e){
        $table.toggleCF7sgTableRowDeletion(false); /* hide/show row deletion button. */
      });

    }
  });
  /* event fired when a field changes value, click to insert helper code into your js file. */
  $('#cf7sg-form-language-form form.wpcf7-form').on( 'change',':input', function(e){
    let $form = $(e.delegateTarget), $field=$(this), fieldName = $field.attr('name');
    switch(fieldName){
      case 'languages-known': //languages-known updated.
        let langs = $field.val() - $table.cf7sgCountRows();
        for(let row=0; row < langs; row++){
          $table.cf7sgCloneRow(); /* function to programmatically add a row to a table if fields. */
        }
        for(let row=0; row>langs ; row--){
          $table.cf7sgRemoveRow(); /* remove last row. */

        }
        break;
      case 'language': //language updated, rIdx is row index.
        break;
      case 'language-experience': //language-experience updated, rIdx is row index.
        break;
    }
  });

})(jQuery)
                                    