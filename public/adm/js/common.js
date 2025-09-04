// home chart
var salesChart = {};
var getchart = function getchart(dlabels,datasets,idCanvas) {
    var salesChartCanvas = document.getElementById(idCanvas).getContext('2d');
    var salesChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: true
      }
    }
    var salesChartData = {
      labels  : dlabels,
      datasets: datasets
    }
    if (salesChart[idCanvas]) {
      salesChart[idCanvas].destroy();
    }
    salesChart[idCanvas] = new Chart(salesChartCanvas, {
      type: 'line',
      data: salesChartData,
      options: salesChartOptions
      }
    )
};

var pieChart = {};
var getpiechart = function getpiechart(dlabels,datasets,idCanvas) {
  var pieChartCanvas = document.getElementById(idCanvas).getContext('2d');
  var pieChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: true
    }
  }
  var pieChartData = {
    labels  : dlabels,
    datasets: datasets
  }
  if (pieChart[idCanvas]) {
    pieChart[idCanvas].destroy();
  }
  pieChart[idCanvas] = new Chart(pieChartCanvas, {
    type: 'pie',
    data: pieChartData,
    options: pieChartOptions
    }
  )
};
/****************alert**********/
function message(type='success',message='Lorem'){
    if(type=='success'){
        toastr.success(message);
    }else if(type=='info'){
        toastr.info(message);
    }else if(type=='error'){
        toastr.error(message)
    }else if(type=='warning'){
        toastr.warning(message);
    }
  }
  /*******************gráfico total home**************/
  function getalltotals(){
    let ajax   = $.ajax({
                    url: '/admin/dashboard/toolsjson',
                    method:'post',
                    data: {act:'getalltotals'},
                    dataType: 'json',
                });
    ajax.done(function(response){
        var data = response.data;
        $('#total_orders').html(data.orders);
        $('#total_products').html(data.products);
        $('#total_customers').html(data.customers);
        $('#total_contacts').html(data.contacts);
    });
  }
  /*******************gráfico carritos home**************/
function getallcart(){
  let ajax   = $.ajax({
                  url: '/admin/dashboard/toolsjson',
                  method:'post',
                  data: {act:'getallcart'},
                  dataType: 'json',
              });
  ajax.done(function(response){
      var data = response.data;
      var datasets=[]; var dlabels=[]; var ddata=[];
      $.each(data, function(i, item) {
        ddata.push(item.total);
        var someDate = moment.unix(i).format("DD/MM/YYYY");
        dlabels.push(someDate);
      });
      datasets=[
      {
        label               : 'Total carritos',
        borderColor         : 'rgba(60,141,188,0.8)',
        data                :  ddata
      }
  ]
      var idCanvas= 'cart-chart-canvas';
      getchart(dlabels,datasets,idCanvas);
  });
}

/*******************contactos home************* */
function getcontacts(page){
  let ajax   = $.ajax({
                  url: '/admin/dashboard/toolsjson',
                  method:'post',
                  data: { page: page,act:'getcontacts' },
                  dataType: 'json',
              });
  ajax.done(function(response){
        let html = '',url='';
        var data = response.data_list;
        $('#table_contacts tbody').empty();
        $.each(data, function(i, item) {
            url='/admin/contact/show/'+item.id;
            html += '<tr><td><b>' + item.id + ' </b><br/>' + item.created_at + '</td><td>' + item.first_name + ' ' + item.last_name + ' <br/> '+item.email+' <br/> <small>'+item.msg+'...</small></td><td><a class="btn btn-info" href="'+url+'"><i class="fas fa-search-plus"></i></a></td></tr>';
        });
        $('#table_contacts tbody').append(html);
        $('#pagination_contacts').html(response.data_pager);
  });
}
function getincidencias(page){
let ajax   = $.ajax({
                url: '/admin/dashboard/toolsjson',
                method:'post',
                data: { page: page,act:'getincidencias' },
                dataType: 'json',
            });
ajax.done(function(response){
      let html = '',url='';
      var data = response.data_list;
      $('#table_incidencias tbody').empty();
      $.each(data, function(i, item) {
          url='/admin/contact/show/'+item.id;
          html += '<tr><td><b>' + item.id + ' </b><br/>' + item.created_at + '</td><td>' + item.first_name + ' ' + item.last_name + ' <br/> '+item.email+' <br/> <small>'+item.msg+'...</small></td><td><a class="btn btn-info" href="'+url+'"><i class="fas fa-search-plus"></i></a></td></tr>';
      });
      $('#table_incidencias tbody').append(html);
      $('#pagination_incidencias').html(response.data_pager);
});
}
function getorders(page){
let ajax   = $.ajax({
                url: '/admin/dashboard/toolsjson',
                method:'post',
                data: { page: page,act:'getorders' },
                dataType: 'json',
            });
ajax.done(function(response){
      let html = '',url='';
      var data = response.data_list;
      $('#table_orders tbody').empty();
      $.each(data, function(i, item) {
          url='/admin/order/show/'+item.id;
          html += '<tr><td><b>' + item.order_erp_id + ' - ' + item.id + ' </b><br/> '+item.updated_at+'</td><td><a  href="'+url+'">' + item.order_usercode + ' ' + item.order_company + ' <br/> '+item.order_email+'</a></td><td><a class="btn btn-info" href="'+url+'"><i class="fas fa-search-plus"></i></a></td></tr>';
      });
      $('#table_orders tbody').append(html);
      $('#pagination_orders').html(response.data_pager);
});
}
function gettopproducts(page){
let ajax   = $.ajax({
                url: '/admin/dashboard/toolsjson',
                method:'post',
                data: { page: page,act:'gettopproducts' },
                dataType: 'json',
            });
ajax.done(function(response){
      let html = '',url='';
      var data = response.data_list;
      $('#table_topproducts tbody').empty();
      $.each(data, function(i, item) {
          html += '<tr><td>' + item.product_sku + '</td><td>' + item.product_name + '</td><td>' + item.total + '</td></tr>';
      });
      $('#table_topproducts tbody').append(html);
      $('#pagination_topproducts').html(response.data_pager);
});
}
function removeitemcat($this){
  let id=$($this).data('id');
  $('#radio-'+id).prop("checked", false);
  $($this).parent().remove();
}
/******************ELFINDER**************** */


function changelanguage($this){
    let localeItem = $($this);
    let form = localeItem.closest('form');
    let selectedLocale = localeItem.data('locale');
    let localeButton = form.find('.js-locale-btn');
    localeButton.html(`<img alt="image" src="/uploads/languages/${selectedLocale}.svg" class="ml-1" width="18">`);
    form.find('.js-locale-input').addClass('d-none');
    form
      .find(`.js-locale-input.js-locale-${selectedLocale}`)
      .removeClass('d-none');
} 
function setlinkrewrite(inputValue) {
  let urlAmigable = inputValue.toLowerCase(); // Convertir todo a minúsculas
  urlAmigable = urlAmigable.replace(/\s+/g, '-');
  urlAmigable = urlAmigable.replace(/[^a-z0-9-]/g, ''); // Ya que todo es en minúsculas, no es necesario buscar A-Z
  return urlAmigable;
}
/**************ELFINDER****************** */
$(function () {

  $('#elfinderexplorer').elfinder({
    url: '/admin/elfinder/connector',
    commandsOptions: {
        getfile: {
            oncomplete: 'close'  // Aquí se cierra la ventana al seleccionar el archivo
        }
    },
    getFileCallback: function(file, fm ) {
        const origin = window.location.origin || window.location.protocol + '//' + window.location.host;
        parent.postMessage({
            mceAction: 'setImageURL',/*setImageURL/insertContent */
            content: file.url
        }, "*");
    }
});

	tinymce.init({
    selector: "textarea.editorhtml",
	  plugins: [
      'a11ychecker', 'advcode', 'advlist', 'anchor', 'autolink', 'codesample', 'fullscreen', 'help',
      'image', 'editimage', 'tinydrive', 'lists', 'link', 'media', 'powerpaste', 'preview',
      'searchreplace', 'table', 'template', 'tinymcespellchecker', 'visualblocks', 'wordcount','code'
    ],
    toolbar: 'insertfile a11ycheck undo redo | bold italic | forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify | bullist numlist | link image | code',
    content_css: [
      '/themes/default/assets/css/plugins/bootstrap.css',
      '/themes/default/assets/css/style_23022023.css',
      '/themes/default/assets/css/responsive_21122022.css',
      '/themes/default/assets/css/new/custom.css',
      '/themes/default/assets/css/new/theme.css',
      '/themes/default/assets/css/new/style_2023.css',
    ],
    promotion: false,
    
    relative_urls: false,
    file_picker_callback: function(callback, value, meta) {
      let elfinderUrl = '/admin/elfinder/explorer';
      tinymce.activeEditor.windowManager.openUrl({
          url: elfinderUrl,
          title: 'elFinder 2.0',
          width: 900,
          height: 450,
          onMessage: function (api, message) {
              callback(message.content);
              api.close();
          }
      });
  }

  });
	tinymce.init({
    selector: "textarea.editorhtml_short",
	  plugins: '',
    toolbar: 'undo redo',
    content_css: [
      '/themes/default/assets/css/plugins/bootstrap.css',
      '/themes/default/assets/css/style_23022023.css',
      '/themes/default/assets/css/responsive_21122022.css',
      '/themes/default/assets/css/new/custom.css',
      '/themes/default/assets/css/new/theme.css',
      '/themes/default/assets/css/new/style_2023.css',
    ],
    promotion: false
  });

  /*
  $('.editorhtml_short').summernote({
    height: 100,
    fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather'],
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['fontsize', ['fontsize']],
      ['fontname', ['fontname']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'video']],
      ['view', ['fullscreen', 'codeview', 'help']],
      ['insert', ['link', 'image','elfinder']]
    ]
}); 
$('.editorhtml').summernote({
  height: 200,
  fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather'],
  toolbar: [
    ['style', ['style']],
    ['font', ['bold', 'underline', 'clear']],
    ['fontsize', ['fontsize']],
    ['fontname', ['fontname']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['table', ['table']],
    ['insert', ['link','video']],
    ['view', ['fullscreen', 'codeview', 'help']],
    ['insert', ['link', 'image','elfinder']]
  ]
});
*/
    $('.caret-cat').on('click',function(){
      if($(this).hasClass("fa-plus")) {
          $(this).removeClass("fa-plus").addClass("fa-minus");
          $(this).parent().parent().children('ul').removeClass("collapsed");
        }
      else {
        $(this).removeClass("fa-minus").addClass("fa-plus");
        $(this).parent().parent().children('ul').addClass("collapsed");
      }
    });
    $('.dfcategory').on('click',function(){
          $(this).parent().children("input[type='checkbox']").prop('checked',true);
    });
    $('.ckcategories').on('click',function(){
        let id=$(this).data('id');
        let name=$(this).data('name');
        $('#ps_categoryTags').html( '<span class="pstaggerTag">'+name+'</span>');
        //$('#ps_categoryTags').html( '<span class="pstaggerTag"><span data-id="'+id+'" >'+name+'</span><a class="pstaggerClosingCross"  data-id="'+id+'" onclick="removeitemcat(this)"><i class="fas fa-times"></i></a></span>');
    });

    $("#checkAll").change(function () {
      $("input.pck:checkbox").prop('checked', $(this).prop("checked"));
    });

    $('.select2-multiple').select2();
    /*bootstrap_confirm_delete*/
    $('.delete-row').bootstrap_confirm_delete({
      debug: false,
      heading: 'Información',
      message: '<center><i class="far fa-trash-alt text-danger fa-3x"></i> <br/>¿Estás segura de que quieres eliminar?</center>',
      btn_ok_label: 'Eliminar',
      btn_cancel_label: 'Cancelar',
      data_type: 'post',
      callback: function( event ) {
        let button = event.data.originalObject;
        if(button[0].dataset.url){
          window.location.href = button[0].dataset.url; 
        }
      },
     /*delete_callback: () => {
        console.log('delete button clicked');
      },
      cancel_callback: () => {
        console.log('cancel button clicked');
      },*/
    });

    /*traudcción*/
    
})
$(document).on('input', '.link_rewrite', function() {
  $(this).val(setlinkrewrite($(this).val()));
});
var doPrint = document.getElementById("doPrint");
  if(typeof doPrint !== 'undefined' && doPrint !== null) {
	document.getElementById("doPrint").addEventListener("click", function() {
		var printContents = document.getElementById('print').innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	});
  }

  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();