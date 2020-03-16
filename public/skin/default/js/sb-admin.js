(function($) {
  "use strict"; // Start of use strict

  $.ajaxSetup({
    beforeSend: function(){
      $('#loading').show();
    },
    complete: function(){
      $('#loading').hide();
    }
  });

  // Toggle the side navigation
  $("#sidebarToggle").on('click', function(e) {
    e.preventDefault();
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
  });

  // Select All & Unselect All
  $(document).on('click', '.dataTables_wrapper .select-all', function(e) {
    e.preventDefault();
    $('input:checkbox[name="ids"]').prop('checked', true);
  });

  $(document).on('click', '.dataTables_wrapper .unselect-all', function(e) {
    e.preventDefault();

    $('input:checkbox[name="ids"]').prop('checked', false);
  });

  $(document).on('click', '.dataTables_wrapper .select-invert', function(e) {
    e.preventDefault();

    $('input:checkbox[name="ids"]').each(function(){
      var _checked = $(this).prop('checked');
      $(this).prop('checked', !_checked);
    });
  });

  // Do filter
  $(document).on('click', '#doFilter', function(e) {
    e.preventDefault();

    var data = $('[name^="filters"]').serialize();
    var actionUrl = $(this).parent().attr('action');

    $.ajax({
      url: actionUrl,
      method: 'POST',
      data: data,
      success: function(response) {
        $("[data-filter-response]").replaceWith(response);
      }
    });
  });

  $(document).keyup(function(event){
    if(event.keyCode == 13){

      if ($(event.target).parents('tr.filter').length) {
        if ($("#doFilter").length) {
          $("#doFilter").trigger("click");
        }

        return true;        
      }

      if ($(event.target).attr('data-role') == 'pages') {
        var $_this = $(event.target);

        var pages = Math.abs($_this.val());
        var url = $_this.attr('data-url');
        var min = Math.abs($_this.attr('min'));
        var max = Math.abs($_this.attr('max'));
        
        if (pages > max) {
          pages = max;
        }

        if (pages < min) {
          pages = min;
        }        

        $_this.val(pages);

        url = url.replace(/^http(s?):\/\/(.+)/, "http$1://$2/"+pages);

        $('#loading').show();
        window.location.href = url;
        return true;
      }

    }
  });  

  // Do reset filter
  $(document).on('click', '#resetFilter', function(e) {
    e.preventDefault();

    var actionUrl = $(this).parent().attr('action');

    $.ajax({
      url: actionUrl,
      method: 'POST',
      data: {action:'resetFilter'},
      success: function(response) {
        $("[data-filter-response]").replaceWith(response);
      }
    });
  });

  // Do select limit
  $(document).on('change', '#select-limit', function(e) {
    e.preventDefault();

    var actionUrl = $(this).attr('action-xhr');
    var limit = $(this).val();

    $.ajax({
      url: actionUrl,
      method: 'POST',
      data: {action:'changeLimit', limit:limit},
      success: function(response) {
        $("[data-filter-response]").replaceWith(response);
      }
    });    
  });

  // Do select limit
  $(document).on('change', '[role="select-item-sku"]', function(e) {
    e.preventDefault();

    var actionUrl = $(this).attr('action-xhr');
    var sku = $(this).val();
    var currencyCode = $(this).data('currency-code');
    if (!currencyCode) {
      currencyCode = 'USD';
    }

    $.ajax({
      url: actionUrl + '/' + sku + '/' + currencyCode,
      success: function(response) {
        $('[role="select-item-size-list"]').empty();

        $.each(response.data, function(name, value){
          $('[role="select-item-size-list"]').append('<option value="'+value+'">'+name+'</option>');
        });
      }
    });    
  });

  function addTagForm($collectionHolder, $addButtonDiv) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = $(newForm.replace(/__name__/g, index));

    addTagFormDeleteLink(newForm);

    $collectionHolder.data('index', index + 1);

    $collectionHolder.data('prototyp-level')
    if ($collectionHolder.data('prototyp-level') == 0) {
      $addButtonDiv.parent('legend').next().prepend(newForm);
    } else {
      $addButtonDiv.before(newForm);
    }
  }

  function addTagFormDeleteLink($optionItem, level) {
    var $removeButton = $('<button type="button" class="btn btn-danger btn-sm">Revmoe</button>');
    var $removeButtonDiv =  $('<div class="form-group"></div>').append($removeButton);

    if (level == 0) {
      $optionItem.children(0).children('fieldset').eq(0).before($removeButtonDiv);
    } else {
      $optionItem.children(0).append($removeButtonDiv);
    }      

    $removeButton.on('click', function(e) {
        $optionItem.remove();
    });
  }

  function addMediaPreview($collectionHolder) {
    $collectionHolder.find('fieldset').each(function(){
      var $url = $(this).find('input[id$="_url"]').eq(0);

      $(this).children(0).prepend('<div class="form-group"><img width="40" src="'+$url.val()+'" /></div>');
    });
  }

  $(document).ready(function() {
    $('[data-prototype]').each(function (){
      var $addButton = $('<button type="button" class="btn btn-danger btn-sm">Add New Option</button>');
      var $addButtonDiv = $('<div class="mt-3 text-right"></div>').append($addButton);

      var $collectionHolder = $(this);
      var $collectionLevel = $collectionHolder.data('prototyp-level');
      var $collectionType = $collectionHolder.data('prototyp-type');

      if ($collectionType == 'media') {
        addMediaPreview($collectionHolder);
      } else {
        if ($collectionLevel == 0) {
          $collectionHolder.prev().append($addButtonDiv);
        } else {
          $collectionHolder.append($addButtonDiv);
        }          
      }

      $collectionHolder.data('index', $collectionHolder.find('fieldset').length);

      $collectionHolder.children('fieldset').each(function() {
          addTagFormDeleteLink($(this), $collectionLevel);
      });      

      $addButton.on('click', function(e) {
          addTagForm($collectionHolder, $addButtonDiv);
      });
    });
  });  

})(jQuery); // End of use strict
