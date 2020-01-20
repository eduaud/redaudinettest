$(document).ready(function() {		
	var pestannaAbierta = "mes";
	
	cargarAgenda();
	limpiarFormulario();
	
	/*SELECCIONAR SOSPECHOSO N1*/
	$('#CitaSospechoso').change(function(){
		$.get('todo_parametros.php?opcionT=5&opcionForm=4&prosp_n1=' + $('#CitaSospechoso').val() + '&id_sel=0' , function(data) {
			$('#CitaSospechosoL2').html(data);
		});
		$.get('todo_parametros.php?opcionT=5&opcionForm=5&id_sel=0' , function(data) {
			$('#CitaSospechosoL3').html(data);
		});
	});
	
	/*SELECCIONAR SOSPECHOSO N2*/
	$('#CitaSospechosoL2').change(function(){
		$.get('todo_parametros.php?opcionT=5&opcionForm=5&prosp_n1=' + $('#CitaSospechoso').val() + '&prosp_n2=' + $('#CitaSospechosoL2').val() + '&id_sel=0' , function(data) {
			$('#CitaSospechosoL3').html(data);
		});
	});
	
	/*CARGA TOOLTIP DESDE LA LISTA TODO DE OBJETO TACHADO*/
	$(".realizada1").live("click", function(){ 
		llenarFormulario($(this).attr('name'));
		$('#calendar').fullCalendar('updateEvent', event);
		//alert($(this).attr('name'));
	});
	
	/*CARGA TOOLTIP DESDE LA LISTA TODO DE OBJETO NO TACHADO*/
	$(".realizada0").live("click", function(){ 
		llenarFormulario($(this).attr('name'));
		$('#calendar').fullCalendar('updateEvent', event);
		//alert($(this).attr('name'));
	});
	
	
	$('#popup').resize(function(){		 
		  $('.caja').css({
			   position:'absolute',
			   left: ($(window).width() - $('.caja').outerWidth())/2,
			   top: ($(window).height() - $('.caja').outerHeight())/2 - 70
		  });				
	});
	
	$('#popup').resize();
	
	$(".check_todo").live("click", function(){ 
		//alert(" id: " + $(".check_todo").prop("checked"));		
		//alert(" id: " + $(this).prop("name"));	
		$.get('todo_parametros.php?opcionT=4&id_todo=' + $(this).prop("name") , function(data) {
			if(data == 'error'){
				alert("Por favor valide sus datos");
			}else{
				refrescar_todos();
			}
			
		});
	}); 
	
	$("#check_todoElDia").click(function(){
		if($(this).is(':checked')) {  
			$(".ocultar_todoeldia").hide();
		}else{
			$(".ocultar_todoeldia").show();
		}
	});
	
	/*en tool tip con id, elimina de la bd el id actual*/
	$("#elimRegAgenda").click(function(){//.live("click", function(){ 
		if (confirm('¿Est\u00e1s seguro que deseas borrar la tarea?')){ 
			$.get('todo_parametros.php?opcionT=7&id_todo=' + $('#idAgenda').attr('value') , function(data) {
				if(data == 'error'){
					alert("Por favor valide sus datos");
				}else{
					$('#calendar').text("");//attr();
					limpiarFormulario();
					$('#popup').hide("slow");
					$('#nvoRegAgenda').show("slow");
					cargarAgenda();	
				}			
			});
		}
	});
			
	/*INGRESAR UN NUEVO TO DO A BD*/
	$('#actAgenda').click(function() {
		var vartodoDia = "0";
		
		if($("#check_todoElDia").is(':checked')){
			vartodoDia = "1";
		}
		
		var parametros = 	"idAgenda=" + $('#idAgenda').attr('value') + "&" +
							"titulo=" + $('#CitaTitulo').attr('value') + "&" +
							"categ=" + $('#CitaCategoria').attr('value') + "&" +
							"sosp=" + $('#CitaSospechoso').attr('value') + "&" +
							"sospL2=" + $('#CitaSospechosoL2').attr('value') + "&" +
							"sospL3=" + $('#CitaSospechosoL3').attr('value') + "&" +
							"enagen=" + $('#CitaEnAgenda').attr('value') + "&" +
							"desc=" + $('#CitaDesc').attr('value') + "&" +
							"fecini=" + $('#CitaFecInicio').attr('value') + "&" +
							"hrini=" + $('#CitaHoraInicio').attr('value') + "&" +
							"fecfin=" + $('#CitaFecFin').attr('value') + "&" +
							"hrfin=" + $('#CitaHoraFin').attr('value') + "&" +
							"rep=" + $('#CitaRegRep').attr('value') + "&" +
							//"inirep=" + $('#CitaFecRepInicio').attr('value') + "&" +
							"finrep=" + $('#CitaFecRepFin').attr('value') + "&" +
							"todoDia=" + vartodoDia
		
		$.get('todo_parametros.php?opcionT=2&' + parametros , function(data) {
			if(data == 'error'){
				alert("Por favor valide sus datos");
			}else{
				alert(data);
				$('#calendar').text("");//attr();
				
				limpiarFormulario();
				$('#popup').hide("slow");
				$('#nvoRegAgenda').show("slow");
				cargarAgenda();	
			}
			
		});				
		
	});
	
	/*MOSTRAR FORMULARIO PARA NUEVO TO DO*/
	$('#nvoRegAgenda').click(function() {
		$('#popup').show("slow");
		$('#nvoRegAgenda').hide("slow");	
	});
	
		
	
	/*MOSTRAR FORMULARIO PARA NUEVO TO DO*/
	$('#cancRegAgenda').click(function() {
		limpiarFormulario()
		$('#popup').hide("slow");
		$('#nvoRegAgenda').show("slow");
	});	
	
	/*HABILITAR ELEMENTOS SI ES QUE VAN A AGENDA*/		
	$('#CitaEnAgenda').click(function() {
		if($('#CitaEnAgenda').attr('value') != "1" ){	
			$('#CitaRegRep').attr('value','0');
			//$('#CitaFecRepInicio').val('');
			$('#CitaFecRepFin').val('');

			$('.ocultar1').hide();
			$('.ocultar2').hide();
		}else{					
			$('.ocultar1').show();
		}
	});	
	
	/*HABILITAR ELEMENTOS SI SERA UNA TAREA QUE SE REPITA VARIAS VECES*/		
	$('#CitaRegRep').click(function() {
		if($('#CitaRegRep').attr('value') == "0" ){
			$('#CitaRegRep').attr('value','0');
			//$('#CitaFecRepInicio').val('');
			$('#CitaFecRepFin').val('');
			
			$('.ocultar2').hide();
		}else{
			var fecha=new Date();
			var nuevaFecha = ('0' + fecha.getDate()).slice(-2).toString() 
							 + '/' + ('0' + (fecha.getMonth() + 1)).slice(-2).toString() 
							 + '/' + fecha.getFullYear().toString();
			//$('#CitaFecRepInicio').val(nuevaFecha);
			$('#CitaFecRepFin').val(nuevaFecha);
			$('.ocultar2').show();
		}
	});	
	
	/*$('#CitaTitulo').bind('input',function () {
		alert('Changed!')
	});*/
	
	$("#CitaFecInicio").bind('input',function () {
		alert('Changed!');
	});

	/*LLENA EL FORMULARIO*/
	function llenarFormulario(id_todo){
		$.getJSON("todo_parametros.php?opcionT=6&id_todo=" + id_todo,function(data){ 
			//alert(data.to_do[0].titulo);
			$("#elimRegAgenda").show();
			
			$('#popup').show("slow");
			$('#nvoRegAgenda').hide("slow");
			
			$('#idAgenda').val(data.to_do[0].var_id);
			$('#CitaTitulo').val(data.to_do[0].var_tit);
			$.get('todo_parametros.php?opcionT=5&opcionForm=1&id_sel=' + data.to_do[0].var_cat , function(data) {
				$('#CitaCategoria').html(data);
			});		
			$.get('todo_parametros.php?opcionT=5&opcionForm=2&id_sel=' + data.to_do[0].var_sosp , function(data) {
				$('#CitaSospechoso').html(data);
			});		
			$.get('todo_parametros.php?opcionT=5&opcionForm=4&prosp_n1=' + data.to_do[0].var_sosp + '&id_sel=' + data.to_do[0].var_sospL2 , function(data) {
				$('#CitaSospechosoL2').html(data);
			});		
			$.get('todo_parametros.php?opcionT=5&opcionForm=5&prosp_n1=' + data.to_do[0].var_sosp + '&prosp_n2=' + data.to_do[0].var_sospL2 + '&id_sel=' + data.to_do[0].var_sospL3 , function(data) {
				$('#CitaSospechosoL3').html(data);
			});		
			if(data.to_do[0].var_age == '1'){
				$('#CitaEnAgenda').attr('value','1');
			}
			$('#CitaDesc').val(data.to_do[0].var_desc);
			
			if(data.to_do[0].var_fecini == null){
				$('#CitaFecInicio').val(data.to_do[0].var_fecfin);
				$.get('todo_parametros.php?opcionT=5&opcionForm=3&id_sel=' + data.to_do[0].var_hrini , function(data) {
					$('#CitaHoraInicio').html(data);
				});		
				$.get('todo_parametros.php?opcionT=5&opcionForm=3&id_sel=' + data.to_do[0].var_hrfin , function(data) {
					$('#CitaHoraFin').html(data);
				});
				$("#check_todoElDia").prop("checked", true);
				$(".ocultar_todoeldia").hide();
			}else{
				$('#CitaFecInicio').val(data.to_do[0].var_fecini);
				$('#CitaFecFin').val(data.to_do[0].var_fecfin);
				$.get('todo_parametros.php?opcionT=5&opcionForm=3&id_sel=' + data.to_do[0].var_hrini , function(data) {
					$('#CitaHoraInicio').html(data);
				});		
				$.get('todo_parametros.php?opcionT=5&opcionForm=3&id_sel=' + data.to_do[0].var_hrfin , function(data) {
					$('#CitaHoraFin').html(data);
				});
			}
			
			if(data.to_do[0].var_rep != '0'){
				$('#CitaRegRep').val(data.to_do[0].var_rep);
				$(".ocultar2").show();				
				//$('#CitaFecRepInicio').val(data.to_do[0].var_repIni);
				$('#CitaFecRepFin').val(data.to_do[0].var_repFin);
			}
			
		}); 
	}
	
	/*LIMPIA EL FORMULARIO*/
	function limpiarFormulario(){
		$("input").val("");
		$("select").val("0");
		//$("#CitaEnAgenda").prop("checked", false);
		$('#CitaDesc').val("");
		//$(".ocultar1").prop("disabled", "disabled");
		//$('[name="ocultar1"]').prop("disabled", true);
		$('.ocultar2').hide();
		$("#check_todoElDia").prop("checked", false);
		$(".ocultar_todoeldia").show();
		
		var fecha=new Date();
		var nuevaFecha = ('0' + fecha.getDate()).slice(-2).toString() 
						 + '/' + ('0' + (fecha.getMonth() + 1)).slice(-2).toString() 
						 + '/' + fecha.getFullYear().toString();
		
		$("#CitaFecInicio").val(nuevaFecha);
		$("#CitaFecFin").val(nuevaFecha);
		$('#CitaRegRep').val(0);
		//$('#CitaFecRepInicio').val(nuevaFecha);
		$('#CitaFecRepFin').val(nuevaFecha);
		
		$.get('todo_parametros.php?opcionT=5&opcionForm=1&id_sel=0' , function(data) {
			$('#CitaCategoria').html(data);
		});		
		$.get('todo_parametros.php?opcionT=5&opcionForm=2&id_sel=0' , function(data) {
			$('#CitaSospechoso').html(data);
		});		
		$.get('todo_parametros.php?opcionT=5&opcionForm=4&id_sel=0' , function(data) {
			$('#CitaSospechosoL2').html(data);
		});		
		$.get('todo_parametros.php?opcionT=5&opcionForm=5&id_sel=0' , function(data) {
			$('#CitaSospechosoL3').html(data);
		});		
		$.get('todo_parametros.php?opcionT=5&opcionForm=3&id_sel=08:00' , function(data) {
			$('#CitaHoraInicio').html(data);
		});		
		$.get('todo_parametros.php?opcionT=5&opcionForm=3&id_sel=09:00' , function(data) {
			$('#CitaHoraFin').html(data);
		});	
				
		$("#elimRegAgenda").hide();
	}
	
	function refrescar_todos(){
		var view = $('#calendar').fullCalendar('getView');
		var d = $('#calendar').fullCalendar('getDate');
		//alert("The view's title is " + view.name + "" + $.fullCalendar.formatDate( d, 'yyyy-MM-dd' ));
		
		$.get('todo_parametros.php?opcionT=3&tipo=' + view.name + '&fecha_a=' + $.fullCalendar.formatDate( d, 'yyyy' )+ '&fecha_m=' + $.fullCalendar.formatDate( d, 'MM' )+ '&fecha_d=' + $.fullCalendar.formatDate( d, 'dd' )+ '' , function(data) {
			$('#todo_list').html(data);				
		});								
	}
		
	/*REFRESCA EL DIV DE LA AGENDA PARA MOSTRAR SOLO AQUELLOS TO DOs QUE ESTAN BD*/
	function cargarAgenda(){
		var date = new Date();
		 
		var calendar = $('#calendar').fullCalendar({
			firstDay: 1,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			//selectable: true,
			selectHelper: true,
			select: function(start, end, allDay) {
				var title = prompt('Event Title:');
				var titleExtra = prompt('Event TitleExtra:');
				
				if (title) {
					calendar.fullCalendar('renderEvent',
						{
							title: title,
							start: start,
							end: end,
							allDay: allDay,
							titleExtra: titleExtra,
							//url: titleExtra,
							type: 'GET',
							data: {
								custom_param1: 'something',
								custom_param2: 'somethingelse'
							}
						},
						true // make the event "stick"
					);
				}
				calendar.fullCalendar('unselect');
			},
			
			//editable: true,
			
						
			eventDrop: function(event, delta) {
			alert(event.title + ' was moved ' + delta + ' days\n' +
				'(should probably update your database)');
			},
			
			eventClick: function(event, element) {
				//event.title = "CLICKED!";
				//alert(event.title + "\n" + "\nId:" +event.id  );
				llenarFormulario(event.id);
				$('#calendar').fullCalendar('updateEvent', event);		
			},
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			},
		
			events: "todo_parametros.php?opcionT=1&var=1"			
			
		});		
		
		
	}
});