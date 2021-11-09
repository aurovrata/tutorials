(function($){
  /*
  Place your custom JavaScript code in this file.
  It will be loaded on the pages where your form is displayed.
  The file is saved in your theme folder: /wp-content/themes/wpg-2019/js/untitled.js  */
  let $table;
  $('#cf7sg-form-untitled form.wpcf7-form').on( 'sgTableReady', '.container.cf7-sg-table', function(e){
    /* event fired once a table has been initialised, click to insert helper code into your js file. */
    let $form = $(e.delegateTarget), $t = $(e.target);
    if('language-table' == $t.attr('id') ){ 
      $table = $t;
      //call this function once the table is ready.
      //$table is the table sectoin jquery object.
      //pass false to hide the button, true to enable the button.
      $table.toggleCF7sgTableRowAddition(false); /* hide/show row addition button. */

      $('#cf7sg-form-untitled form.wpcf7-form').on( 'sgRowAdded', $table, function(e){
        $table.toggleCF7sgTableRowDeletion(false); /* hide/show row deletion button. */
      });

    }
  });

  $('#cf7sg-form-untitled form.wpcf7-form').on( 'cf7SmartGridReady', function(e){
    /* event fired once the form has been initialised, click to insert helper code into your js file. */
    let $form = $(this); //$jquery form object.
    /* event fired when a field changes value, click to insert helper code into your js file. */
    $('#cf7sg-form-untitled form.wpcf7-form').on( 'change',':input', function(e){
      let $form = $(e.delegateTarget), $field=$(this), fieldName = $field.attr('name');
      //-----code to extract field name and tab/row index -----------
      let search='', tIdx=0, rIdx=0;
      if( $field.is('.cf7sgtab-field') || $field.is('.cf7sgrow-field') ){
        $.each($field.attr('class').split(/\s+/), function(idx, clss){
          if(0==clss.indexOf('cf7sg-')){
            clss = clss.replace('cf7sg-','');
            search = new RegExp( '(?<='+clss+')(_tab-(\\d))?(_row-(\\d))?','gi' ).exec(fieldName);
            switch(true){
              case /\d+/.test(search[2]*search[4]): //table within a tab.
                tIdx = parseInt(search[2]);
                rIdx = parseInt(search[4]);
                break;
              case /\d+/.test(search[2]): //tab.
                tIdx = parseInt(search[2]);
                break;
              case /\d+/.test(search[4]): //row.
                rIdx = parseInt(search[4]);
                break;
            }
            fieldName = clss;
            return false; //break out of each loop.
          }
        });
      }
      //------ end of code for field extraction ---------

      // $form is the form jquery object.
      // $field is the input field jquery object.
      //
      switch(fieldName){
        case 'languages-known': //languages-known updated.
          let langs = $field.val() - $table.cf7sgCountRows(); /* count table rows. */

          for(let row =0;row<langs;row++){
            $table.cf7sgCloneRow(); /* function to programmatically add a row to a table if fields. */
          }

          for(let row =0; row>langs;row--){
            $table.cf7sgRemoveRow(); /* remove last row. */
          }

          break;
        case 'language': //language updated, rIdx is row index.
          break;
        case 'language-experience': //language-experience updated, rIdx is row index.
          break;
      }
    });

  });

})(jQuery)
                                                      