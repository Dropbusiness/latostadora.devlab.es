
function setattributes(elem, event, product_id, combination_id) {
  // Obtener los valores actualizados de los campos de entrada
  const precio = $('#precio').val();
  const stock = $('#cantidad').val();
  const referencia = $('#referencia').val();
  const ean = $('#ean2').val();
  const default_on = $('#default_on').val();
  const models_code = $('#models_cod').val();
  // Realizar una solicitud AJAX POST para guardar los cambios
  $.ajax({
    url: '/admin/products/jsontools',
    method: 'post',
    data: {
      act: 'setattributes',
      product_id: product_id,
      combination_id: combination_id,
      precio: precio,
      stock: stock,
      referencia: referencia,
      ean: ean,
      default_on: default_on,
      models_code: models_code,
    },
    dataType: 'json',
  })
    .done(function (response) {
      // Verificar la respuesta del servidor y realizar acciones según sea necesario
      if (response.success) {
        // Los cambios se guardaron correctamente, puedes cerrar el modal u realizar otras acciones
        $('#optpromptai').modal('hide');
        // Realiza otras acciones si es necesario
        location.reload();
      } else {
        // Mostrar un mensaje de error si la operación no se realizó con éxito
        alert('No se pudieron guardar los cambios. Inténtalo de nuevo.');
      }
    });
}

function getattributes(elem,event,product_id,combination_id) {
  $.get('/admin/products/jsontools', {act:'getattributes',product_id,combination_id}, function(data){
        if(data.error==false){
          let attribute = data.data;
            $('body').append(`
              <div class="modal fade" id="optpromptai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Asistente</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      ID:${attribute.combination_id}<br/>
                      ${attribute.combination_details}<br/>
                      <div class="form-group mt-3">
                        <label for="precio">Precio</label>
                        <input type="number" name="precio" value="${attribute.price}" class="form-control" id="precio">
                      </div>
                      <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" value="${attribute.stock}" class="form-control" id="cantidad">
                      </div>
                       <div class="form-group">
                        <label for="models_cod">Código del modelo (H_A2)</label>
                        <input type="text" name="models_cod" value="${attribute.models_code??''}" class="form-control" id="models_cod">
                      </div>
                      <div class="form-group">
                        <label for="referencia">Referencia</label>
                        <input type="text" name="referencia" value="${attribute.reference}" class="form-control" id="referencia">
                      </div>
                      <div class="form-group">
                        <label for="ean">EAN</label>
                        <input type="text" name="ean" value="${attribute.ean}" class="form-control" id="ean2">
                      </div>
                     
                      <div class="form-group">
                          <label for="default_on">Default on</label>
                          <select name="default_on" class="form-control" id="default_on">
                              <option value="0" ${attribute.default_on == 0 ? 'selected' : ''}>No</option>
                              <option value="1" ${attribute.default_on == 1 ? 'selected' : ''}>Sí</option>
                          </select>
                      </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="setattributes(this,event,${product_id},${combination_id})">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
            `);
            $('#optpromptai').modal({
              show: false,
              backdrop: "static",
              keyboard: false
            }).on('hidden.bs.modal', function (e) {
              $(this).remove();
            }).modal('show');

        }else{
          message('warning',data.msg);
        } 
  });
}

function generatecombinationsbymodel(elem,event,product_id) {

            $('body').append(`
              <div class="modal fade" id="generatecombinationsbymodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Generar combinaciones por modelo</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">

                      <form role="form" method="post" action="">
                        <div class="row">
                        
                          <div class="col-12">
                            <label for="modelo">Modelo*</label>
                            <select name="product_models" class="form-control" id="product_models">
                              <option value="">Seleccionar modelo</option>
                            </select>
                          </div>
                          <div class="col-6">
                            <label for="precio">Precio*</label>
                            <input type="number" name="product_price" class="form-control" id="product_price" value="${product_price}">
                          </div>
                          <div class="col-6">
                            <label for="stock">Stock*</label>
                            <input type="number" name="product_stock" class="form-control" id="product_stock" value="${product_stock}">
                          </div>
                          <div class="col-6">
                            <label for="sku">SKU*</label>
                            <input type="text" name="product_sku" class="form-control" id="product_sku" value="${product_sku}">
                          </div>
    
                        </div>
                      </form>

                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                         <button type="button" class="btn btn-primary" onclick="generatecombinationsbymodel_update(this,event,${product_id})">Generar</button>
                    </div>
                  </div>
                </div>
              </div>
            `);
            $('#generatecombinationsbymodel').modal({
              show: false,
              backdrop: "static",
              keyboard: false
            }).on('hidden.bs.modal', function (e) {
              $(this).remove();
            }).modal('show');
            //cargar los modelos en el select
            let select = $('#product_models');
            product_models.forEach(model => {
              select.append(`<option value="${model.id}">${model.code}  ${model.name} - ${model.color} - ${model.talla}</option>`);
            });

}
function generatecombinationsbymodel_update(elem,event,product_id) {
    let models = $('#product_models').val();
    let precio = $('#product_price').val();
    let stock = $('#product_stock').val();
    let sku = $('#product_sku').val();
    //todo los campos son requeridos
    if(models == '' || precio == '' || stock == '' || sku == ''){
      message('error','Todos los campos son requeridos');
      return;
    }
    $.post('/admin/products/jsontools', {act:'generatecombinationsbymodel_update',product_id,models,precio,stock,sku}, function(data){
      if(data.error == false){
        message('success','Combinaciones generadas correctamente');
        $('#generatecombinationsbymodel').modal('hide');
        location.reload();
      }else{
        message('error',data.msg);
      }
    });
}
$(document).ready(function() {

  $("#btnnewcombination").click(function(){
      $("#newcombination").toggleClass('d-none');
  });

  $('#attributesAccordion').on('show.bs.collapse', function(e) {
      $(e.target).prev('.card-header').find('.fas').removeClass('fa-chevron-down').addClass('fa-chevron-up');
  });
  $('#attributesAccordion').on('hide.bs.collapse', function(e) {
      $(e.target).prev('.card-header').find('.fas').removeClass('fa-chevron-up').addClass('fa-chevron-down');
  });

  let selectedCombinations = [];
  const updateCombinationInput = () => {
      $("#selectedCombinations").val(JSON.stringify(selectedCombinations));
  };
  const addCombinationTag = (attribute, value, attributeId, valueId) => {
      const tag = `<span class="badge badge-success mr-3 mb-1" style="font-size: 15px;" data-attribute-id="${attributeId}" data-value-id="${valueId}">
                    ${attribute}: ${value}
                    <a href="#" class="ml-3 remove-combination text-white">&times;</a>
                  </span>`;
      $("#combinationsTags").append(tag);
  };

  $(".select-attribute").on("click", function() {
      const attributeId = $(this).data('attributeid');
      const valueId = $(this).data('valueid');
      const attribute = $(this).data('attribute');
      const value = $(this).data('value');
      // Verificamos si la combinación ya existe
      const combinationExists = selectedCombinations.some(comb => comb.attributeId === attributeId && comb.valueId === valueId);

      if (!combinationExists) {
          // Agregamos un nuevo objeto con más detalles
          selectedCombinations.push({ attributeId, valueId, attribute, value });
          addCombinationTag(attribute, value, attributeId, valueId);
          updateCombinationInput(); 
      }
  });

  $(document).on("click", ".remove-combination", function(e) {
      e.preventDefault();
      const parent = $(this).parent();
      const attributeId = parent.data('attribute-id');
      const valueId = parent.data('value-id');
      selectedCombinations = selectedCombinations.filter(item => item.attributeId !== attributeId || item.valueId !== valueId);
      parent.remove();
      updateCombinationInput();
  });

});