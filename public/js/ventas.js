//para esconder la alerta despues de 2seg (2000ms)
$('#success-alert').fadeTo(2000, 500).slideUp(500, function() {
	$('#success-alert').slideUp(500);
});

//inicializacion de la tabla 1 con dataTable
var table = $('#example1').DataTable({
	language: {
		emptyTable: 'No hay datos disponibles en la tabla.',
		info: 'Del _START_ al _END_ de _TOTAL_ ',
		infoEmpty: 'Mostrando 0 registros de un total de 0.',
		infoFiltered: '',
		infoPostFix: '',
		lengthMenu: 'Mostrar _MENU_ registros',
		loadingRecords: 'Cargando...',
		processing: 'Procesando...',
		search: '',
		searchPlaceholder: 'Buscar',
		zeroRecords: 'No se han encontrado coincidencias.',
		paginate: {
			first: 'Primera',
			last: 'Última',
			next: 'Siguiente',
			previous: 'Anterior'
		},
		aria: {
			sortAscending: 'Ordenación ascendente',
			sortDescending: 'Ordenación descendente'
		}
	},
	lengthMenu: [ [ 5, 10, 20, 25, 50, -1 ], [ 5, 10, 20, 25, 50, 'Todos' ] ],
	iDisplayLength: 10,
	columns: [ { data: 0 }, { data: 1 }, { data: 2 }, { data: 3 }, { data: 4 } ],
	pageLength: 10
	// "initComplete": function () {
	//     var api = this.api();
	//     api.$('td').click( function () {
	//         api.search( this.innerHTML ).draw();
	//     } );
	// }
});

//inicializacion de la tabla 2 con dataTable
var table2 = $('#example2').DataTable({
	language: {
		emptyTable: 'No hay productos en la tabla.',
		info: '_TOTAL_ producto.',
		infoEmpty: '0 productos',
		infoFiltered: '(filtrados de un total de _MAX_ registros)',
		infoPostFix: '',
		lengthMenu: 'Mostrar _MENU_ registros',
		loadingRecords: 'Cargando...',
		processing: 'Procesando...',
		search: '',
		searchPlaceholder: 'Buscar',
		zeroRecords: 'No se han encontrado coincidencias.',
		paginate: {
			first: 'Primera',
			last: 'Última',
			next: 'Siguiente',
			previous: 'Anterior'
		},
		aria: {
			sortAscending: 'Ordenación ascendente',
			sortDescending: 'Ordenación descendente'
		}
	},
	lengthMenu: [ [ 5, 10, 20, 25, 50, -1 ], [ 5, 10, 20, 25, 50, 'Todos' ] ],
	iDisplayLength: 50,
	columns: [ { data: 0 }, { data: 1 }, { data: 2 }, { data: 3 }, { data: 4 }, { data: 5 } ],
	pageLength: 50,
	columnDefs: [
		{ className: 'text-center', targets: [ 5 ] },
		{ className: 'text-right', targets: [ 3 ] },
		{ targets: [ 0 ], visible: false }
	]
});

var table3 = $('#example3').DataTable({
	language: {
		emptyTable: 'No hay productos en la tabla.',
		info: '_TOTAL_ producto.',
		infoEmpty: '0 productos',
		infoFiltered: '(filtrados de un total de _MAX_ registros)',
		infoPostFix: '',
		lengthMenu: 'Mostrar _MENU_ registros',
		loadingRecords: 'Cargando...',
		processing: 'Procesando...',
		search: '',
		searchPlaceholder: 'Buscar',
		zeroRecords: 'No se han encontrado coincidencias.',
		paginate: {
			first: 'Primera',
			last: 'Última',
			next: 'Siguiente',
			previous: 'Anterior'
		},
		aria: {
			sortAscending: 'Ordenación ascendente',
			sortDescending: 'Ordenación descendente'
		}
	},
	lengthMenu: [ [ 5, 10, 20, 25, 50, -1 ], [ 5, 10, 20, 25, 50, 'Todos' ] ],
	iDisplayLength: 50,
	columns: [ { data: 0 }, { data: 1 }, { data: 2 }, { data: 3 }, { data: 4 } ],
	pageLength: 50,
	columnDefs: [ { className: 'text-right', targets: [ 3 ] }, { targets: [ 0 ], visible: false } ]
});

//Funcion que obtiene los datos de un producto y los coloca en el Modal para ser mostrado
function buscarInfo($product) {
	var url = '/sale/getInfoProduct/' + $product;
	$.get(url, (data) => {
		// console.log(data.category.name);
		$('#codigoLabel').html(data.code);
		$('#nombreLabel').html(data.name);
		$('#categoriaLabel').html(data.category.name);
		$('#stockLabel').html(data.stockCurrent);
		$('#precioLabel').html(data.priceSale.toLocaleString('de-DE'));
		$('#bodegaLabel').html(data.warehouse.name);
		$('#show_product').modal('show');
	});
}

//Opciones generales de las notificaciones
$.notifyDefaults({
	type: 'warning',
	delay: 2000,
	timer: 200,
	animate: {
		enter: 'animated fadeInDown',
		exit: 'animated fadeOutUp'
	},
	newest_on_top: true,
	mouse_over: 'pause'
});

//funcion que recibe la info de un producto desde la tabla 1 y la envia a la tabla 2
function enviarInfo($product) {
	var url = '/sale/getInfoProduct/' + $product;
	$.get(url, (data) => {
		if ($('#cantidad' + data.id).length) {
			if ($('#cantidad' + data.id).val() < data.stockCurrent) {
				$('#cantidad' + data.id).val(parseInt($('#cantidad' + data.id).val()) + 1);
				suma();
			} else {
				$.notify({
					icon: 'fa fa-warning',
					title: '<strong>Alerta!</strong>',
					message: 'Stock insuficiente para esta operación'
				});
			}
		} else {
			if (data.stockCurrent > 0) {
				table2.row
					.add([
						data.id,
						data.code,
						data.name,
						'$ ' + data.priceSale,
						`<div class=""><input type="number" id="cantidad${data.id}" name="cantidad${data.id}" class="form-control input-sm sumar" value="1" onchange="suma()" onkeyup="suma()">`,
						`<button type="button" class="btn btn-danger btn-sm icon-delete" data-toggle="modal"><i class="fa fa-trash"></i>`
					])
					.draw()
					.node();

				$('#cantidad' + data.id).attr({
					max: data.stockCurrent,
					min: 1,
					style: 'width: 100%'
				});
				suma();
			} else {
				$.notify({
					icon: 'fa fa-warning',
					title: '<strong>Alerta!</strong>',
					message: 'Stock insuficiente para esta operación'
				});
			}
		}
	});
}

//Borrado de un elemento en la tabla 2
$('#example2').on('click', 'button', function() {
	table2.row($(this).parents('tr')).remove().draw(false);
	suma();
});

//Borrado de todos los elementos de la tabla 2
$('#btnBorrarTodo').on('click', function() {
	table2.clear().draw();
	suma();
});

$('#prueba').on('click', function() {
	$('#show_sale').modal('show');
});

//Funcion que se llama para la suma de los
//precios de los productos por su cantidad en la tabla 2
function suma() {
	var data = table2.rows().data();
	let sum = 0;
	let cantidad = 0;
	data.each(function(value, index) {
		cantidad = $('#cantidad' + value[0]).val();
		sum += parseInt(value[3].replace('$ ', '')) * cantidad;
	});
	$('#precioFinal').html('$ ' + sum.toLocaleString('de-DE'));
}

//Función que hace el descuento de los productos dependiendo de la cantidad de la tabla 2
$('#btnDescontar').on('click', function() {
	var data = table2.rows().data();
	var flag = true;
	var errores = [];
	data.each(function(value, index) {
		//verifica si hay suficientes items antes de descontar
		let porDescontar = parseInt($('#cantidad' + value[0]).val());
		let max = $('#cantidad' + value[0]).attr('max');
		if (porDescontar > max) {
			flag = false;
			errores.push(value[2]);
		}
	});
	if (flag) {
		data.each(function(value, index) {
			let cantidad = parseInt($('#cantidad' + value[0]).val());
			var url = '/sale/discountProduct/' + value[0] + '/' + cantidad;
			$.get(url, (data) => {
				location.reload(); //recarga de la pagina
			});
		});
	} else {
		var mostrarErrores = '<ol>';
		// console.log(errores);
		errores.forEach(function(value, index) {
			mostrarErrores += '<li>' + value + '</li>';
		});
		mostrarErrores += '</ol>';
		$.notify({
			icon: 'fa fa-warning',
			title: '<strong>Ups!</strong>',
			message: `Hay un item en la lista de ventas que no tiene stock insuficiente
                      o se coloco un dato incorrecto<br>${mostrarErrores}`
		});
	}
});
