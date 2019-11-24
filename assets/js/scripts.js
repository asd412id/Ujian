$.fn.select2.amd.define('select2/selectAllAdapter', [
  'select2/utils',
  'select2/dropdown',
  'select2/dropdown/attachBody'
], function (Utils, Dropdown, AttachBody) {
  function SelectAll() { }
  SelectAll.prototype.render = function (decorated) {
    var self = this,
    $rendered = decorated.call(this),
    $selectAll = $(
      '<button class="btn btn-xs btn-primary" type="button" style="margin-left:6px;">Pilih Semua</button>'
    ),
    $unselectAll = $(
      '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;">Batalkan Semua</button>'
    ),
    $btnContainer = $('<div style="margin-top:3px;">').append($selectAll).append($unselectAll);
    if (!this.$element.prop("multiple")) {
      // this isn't a multi-select -> don't add the buttons!
      return $rendered;
    }
    $rendered.find('.select2-dropdown').prepend($btnContainer);
    $selectAll.on('click', function (e) {
      var $results = $rendered.find('.select2-results__option[aria-selected=false]');
      $results.each(function () {
        self.trigger('select', {
          data: $(this).data('data')
        });
      });
      self.trigger('close');
    });
    $unselectAll.on('click', function (e) {
      var $results = $rendered.find('.select2-results__option[aria-selected=true]');
      $results.each(function () {
        self.trigger('unselect', {
          data: $(this).data('data')
        });
      });
      self.trigger('close');
    });
    return $rendered;
  };
  return Utils.Decorate(
    Utils.Decorate(
      Dropdown,
      AttachBody
    ),
    SelectAll
  );
});
$(document).ready(function(){
  $.fn.modal.Constructor.prototype._enforceFocus = function() {};
  $(".modal").on('show.bs.modal',function(){
    $(".content").css({'position':'fixed','width':$(".content").width()+30});
    $(".copyright").hide();
  })
  $(".modal").on('hide.bs.modal',function(){
    $(".content").css({'position':'','width':''});
    $(".copyright").show();
  })
  $(".no-data").each(function(){
    $(this).attr('colspan',$(this).closest('table').find('th').length)
  })
  $(".delete").click(function(e){
    e.preventDefault();
    var confirm = `
    <h3 class="text-center">Yakin ingin menghapus `+$(this).data('text')+`?</h3>
    <div class="text-center" style="margin-bottom: 15px">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
    <a href="`+$(this).data('url')+`" class="btn btn-danger">Ya</a>
    </div>
    `;
    $("#modalDelete").find('.modal-content').html(confirm);
    $("#modalDelete").modal({
      show: true,
      // keyboard: false,
      backdrop: 'static'
    });
  })
  $(".confirm").on('click',function(e){
    e.preventDefault();
    var confirm = `
    <h3 class="text-center">`+$(this).data('text')+`</h3>
    <div class="text-center" style="margin-bottom: 15px">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
    <a href="`+$(this).data('url')+`" class="btn btn-danger">Ya</a>
    </div>
    `;
    $(".modal-confirm").find('.modal-content').html(confirm);
    $(".modal-confirm").modal({
      show: true,
      // keyboard: false,
      backdrop: 'static'
    });
  })
})
$(".notif").click(function(e){
  e.preventDefault();
  var confirm = `
  <h3 class="text-center">`+$(this).data('text')+`</h3>
  <div class="text-center" style="margin-bottom: 15px">
  <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Tutup</button>
  </div>
  `;
  $(".modal-confirm").find('.modal-content').html(confirm);
  $(".modal-confirm").modal({
    show: true,
    // keyboard: false,
    backdrop: 'static'
  });
})
$(document).ajaxSuccess(function() {
  setTimeout(()=>{
    $("select").select2();
    $("select[multiple]").select2({
      placeholder: 'Pilih data',
      dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter')
    });
    $('#dend').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY - HH:mm',minDate: $("meta[name='time-now']").attr('content')}).change(function(e,date){
      if ($('#dstart').val()=='') {
        $('#dstart').bootstrapMaterialDatePicker('setDate',date);
      }
    })
    $('#dstart').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY - HH:mm'}).change(function(e,date){
      $('#dend').bootstrapMaterialDatePicker('setDate',date);
      $('#dend').bootstrapMaterialDatePicker({minDate:date});
    });
  },175)
})
