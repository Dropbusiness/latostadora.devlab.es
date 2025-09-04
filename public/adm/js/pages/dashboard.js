$(function () {
   'use strict'
 
   $(document).on('click', '#pagination_contacts ul.pagination li a', function(e){
     e.preventDefault();
     let page = $(this).data('page');
     getcontacts(page);
  });
  $(document).on('click', '#pagination_incidencias ul.pagination li a', function(e){
     e.preventDefault();
     let page = $(this).data('page');
     getincidencias(page);
  });
  $(document).on('click', '#pagination_orders ul.pagination li a', function(e){
     e.preventDefault();
     let page = $(this).data('page');
     getorders(page);
  });
  $(document).on('click', '#pagination_topproducts ul.pagination li a', function(e){
     e.preventDefault();
     let page = $(this).data('page');
     gettopproducts(page);
  });
  /****************filtor header******************/
  getalltotals();
  getcontacts(1);
  getincidencias(1);
  getorders(1);
  gettopproducts(1);
 
 
 function setsessionsearch(date_start,date_end,idSite){
    let ajax   = $.ajax({
                    url: '/admin/dashboard/toolsjson',
                    method:'post',
                    data: {act:'setconfigsearch',date_start,date_end,idSite},
                    dataType: 'json',
                });
    ajax.done(function(response){
       getallcart();
    });
 }
 
  var start =  moment($('#reportrange').data('start'));
  var end =  moment($('#reportrange').data('end'));
  function cb(start, end) {
      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      $('#reportrange').data('start',start.format('YYYY-MM-DD'));
      $('#reportrange').data('end',end.format('YYYY-MM-DD'));
      var siteid=$('#reportidsite').data('idsite');
      setsessionsearch(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),siteid);
  }
  $('#reportrange').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
         'Today': [moment(), moment()],
         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
         'This Month': [moment().startOf('month'), moment().endOf('month')],
         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
  }, cb);
  cb(start, end);
 
 })
 