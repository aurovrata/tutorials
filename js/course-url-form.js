(function($){
  /*
  Place your custom JavaScript code in this file.
  It will be loaded on the pages where your form is displayed.
  The file is saved in your theme folder: /wp-content/themes/wpg-2019/js/course-url-form.js  */
  $('#cf7sg-form-course-url-form form.wpcf7-form').on( 'sgTableReady', '.container.cf7-sg-table', function(e){
    /* event fired once a table has been initialised, click to insert helper code into your js file. */
    let $form = $(e.delegateTarget),$table = $(e.target);
    let b = $table.siblings('.cf7-sg-table-button').find('a'), nb=b.clone();
    nb.text('Add Link');
    b.after(nb.addClass('add-link'));
    nb = b.clone().addClass('add-page');
    nb.text('Add Page');
    b.after(nb);
    $('#cf7sg-form-course-url-form form.wpcf7-form').on( 'sgRowAdded', $table, function(e){
      /* event fired when a table row has been added, click to insert helper code into your js file. */
      //$form current form jquery object.
      //$table table jquery object to which the new row was added.
      //$row newly added row jquery object.
      //rIdx row index (zero based).
      let $form = $(e.delegateTarget), $table = $(e.target), rIdx = e['row'], $row= $table.find('.row[data-row='+rIdx+']');
      if(e['button']){
        let $prevRow = $table.find('.row[data-row='+(rIdx-1)+']'),
            t = $prevRow.find('input.cf7sg-chapter').val();
        if(e.button.classList.contains('add-page')){
          $row.find('input.cf7sg-chapter').val(t);
        }else if(e.button.classList.contains('add-link')){
          $row.find('input.cf7sg-chapter').val(t);
          t = $prevRow.find('input.cf7sg-page').val();
          $row.find('input.cf7sg-page').val(t);
        }
      }
    });


  });

})(jQuery)
                                          