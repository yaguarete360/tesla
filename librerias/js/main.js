// HEADER SEARCH
$(document).ready(function() {
    $("header button").attr("data-toggle", "collapse").click(function() {
        $("header div.collapse").collapse("hide")
    });
});

$(document).ready(function () {
    $('.datetimepicker').datetimepicker({
        format: 'DD/MM/YYYY'
    });
});

$(document).ready(function () {
    $('.timepicker').datetimepicker({
        format: 'HH:mm'
    });
});

// Contact form
function resetContactForm(){
	$('#hora1, #hora2, #name, #email, #tel, #cel, #address, #message, #captcha').val('');
	$('#asesor').val('no');
	$('#day1, #day2').val(1);
	$('#motivo, #servicio').val(0);
}
String.prototype.trim= function(){ return this.replace(/(^\s*)|(\s*$)/g,""); }
$(function() {
	
	$("#tel, #cel").keydown(function (e) {
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			(e.keyCode == 65 && e.ctrlKey === true) || 
			(e.keyCode >= 35 && e.keyCode <= 40)) {
				 return;
		}
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});

	$('#send').click(function(){
		$('#send').val('Enviando');
		$('#motivo, #servicio, #hora1, #hora2, #name, #email, #tel, #cel, #address, #message, #captcha').removeClass('alert-border-red');
		var error = false;
		$('#send-message').fadeOut(250);

		if($('#motivo').val() == 0){
			$('#motivo').addClass('alert-border-red'); error = true;
			$('#motivo').focus();
		}

		if($('#servicio').val() == 0){
			if(!error) $('#servicio').focus();
			$('#servicio').addClass('alert-border-red'); error = true;
		}

		if($('#name').val().trim() == ''){
			if(!error) $('#name').focus();
			$('#name').addClass('alert-border-red'); error = true;
		}

		if($('#email').val().trim() == '' || $('#email').val().indexOf('@') == -1 || $('#email').val().indexOf('.') == -1){
			if(!error) $('#email').focus();
			$('#email').addClass('alert-border-red'); error = true;
		}

		if($('#tel').val().trim() == '' || $('#tel').val().length < 6){
			if(!error) $('#tel').focus();
			$('#tel').addClass('alert-border-red'); error = true;
		}
		
		if($('#cel').val().trim() == '' || $('#cel').val().length < 6){
			if(!error) $('#cel').focus();
			$('#cel').addClass('alert-border-red'); error = true;
		}

		/*if($('#address').val().trim() == ''){
			if(!error) $('#address').focus();
			$('#address').addClass('alert-border-red'); error = true;
		}*/

		if($('#message').val().trim() == ''){
			if(!error) $('#message').focus();
			$('#message').addClass('alert-border-red'); error = true;
		}

		if($('#captcha').val().trim() == ''  || $('#captcha').val().length < 5){
			if(!error) $('#captcha').focus();
			$('#captcha').addClass('alert-border-red'); error = true;
		}

		if(!error){
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: URL_MAIN + '/js/check-captcha.php',
				data: {
					'captcha': $('#captcha').val()
				},
				success: function(res) {
					if (res.status == 1) {
						$.ajax({
							type: 'post',
							dataType: 'json',
							url: URL_MAIN + '/js/send.php',
							data: {
								'motivo': $('#motivo').val(),
								'servicio': $('#servicio').val(),
								'hora1': $('#hora1').val(),
								'hora2': $('#hora2').val(),
								'day1': $('#day1').val(),
								'day2': $('#day2').val(),
								'asesor': $("input[name=asesor]:checked").val(),
								'name': $('#name').val(),
								'email': $('#email').val(),
								'tel': $('#tel').val(),
								'cel': $('#cel').val(),
								'address': $('#address').val(),
								'message': $('#message').val()
							},
							success: function(res) {
								$('#motivo, #servicio, #hora1, #hora2, #name, #email, #tel, #cel, #address, #message, #captcha').removeClass('alert-border-red');
								$('#send-message').removeClass('success').removeClass('error');
								if (res.status == 1) {
									resetContactForm();
									$('#send-message').addClass('success');
									$('#send-message').html('¡Gracias por contactarnos!');
								}else{
									$('#send-message').addClass('error');
									$('#send-message').html('Lo sentimos, sus datos no fueron enviados.');
								}
								$('#send-message').css('display', 'inline-block');
								$('#send').val('Enviar');
							}
						});
					}else if(res.status == 2){
						$('#captcha').addClass('alert-border-red'); error = true;
						$('#captcha').focus();
					}
				}
			});
		}else{
			$('#send').val('Enviar');
		}
		return false;

	});
	resetContactForm();
});

// Contact condolencias form
function resetContactFormC(){
	$('#difunto, #name, #email, #family, #message, #captcha').val('');
	$('#select-difundo').val(0);
}
$(function() {

	$('#select-difundo').change(function(){
		if($(this).val() == 0){
			$('#difunto').prop('disabled', false);
		}else{
			$('#difunto').prop('disabled', true);
		}
	});

	$('#btn-send-condolencias').click(function(){
		$('#btn-send-condolencias').val('Enviando');
		$('#difunto, #name, #email, #family, #message, #captcha').removeClass('alert-border-red');
		var error = false;
		$('#send-message').fadeOut(250);

		if($('#select-difundo').val() == 0 && $('#difunto').val().trim() == ''){
			$('#difunto').addClass('alert-border-red'); error = true;
			$('#difunto').focus();
		}

		if($('#name').val().trim() == ''){
			if(!error) $('#name').focus();
			$('#name').addClass('alert-border-red'); error = true;
		}

		if($('#email').val().trim() == '' || $('#email').val().indexOf('@') == -1 || $('#email').val().indexOf('.') == -1){
			if(!error) $('#email').focus();
			$('#email').addClass('alert-border-red'); error = true;
		}

		if($('#family').val().trim() == ''){
			if(!error) $('#family').focus();
			$('#family').addClass('alert-border-red'); error = true;
		}

		if($('#message').val().trim() == ''){
			if(!error) $('#message').focus();
			$('#message').addClass('alert-border-red'); error = true;
		}

		if($('#captcha').val().trim() == ''  || $('#captcha').val().length < 5){
			if(!error) $('#captcha').focus();
			$('#captcha').addClass('alert-border-red'); error = true;
		}

		if(!error){
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: URL_MAIN + '/js/check-captcha.php',
				data: {
					'captcha': $('#captcha').val()
				},
				success: function(res) {

					if (res.status == 1) {
						$.ajax({
							type: 'post',
							dataType: 'json',
							url: URL_MAIN + '/js/send-condolencias.php',
							data: {
								'difunto': ($('#select-difundo').val() != 0 ? $('#select-difundo').val() : $('#difunto').val().trim()),
								'name': $('#name').val(),
								'email': $('#email').val(),
								'family': $('#family').val(),
								'message': $('#message').val()
							},
							success: function(res) {
								$('#difunto, #name, #email, #family, #message, #captcha').removeClass('alert-border-red');
								$('#send-message').removeClass('success').removeClass('error');
								if (res.status == 1) {
									resetContactFormC();
									$('#send-message').addClass('success');
									$('#send-message').html('¡Gracias por contactarnos!');
								}else{
									$('#send-message').addClass('error');
									$('#send-message').html('Lo sentimos, sus datos no fueron enviados.');
								}
								$('#send-message').css('display', 'inline-block');
								$('#btn-send-condolencias').val('Enviar');
							}
						});
					}else if(res.status == 2){
						$('#captcha').addClass('alert-border-red'); error = true;
						$('#captcha').focus();
					}
				}
			});
		}else{
			$('#btn-send-condolencias').val('Enviar');
		}
		return false;

	});
	//resetContactFormC();
});

$(function() {
	$('#btn-main-search').click(function(){
		$('#q').removeClass('alert-border-red');
		var error = false;
		
		if($('#q').val().trim() == ''){
			if(!error) $('#q').focus();
			$('#q').addClass('alert-border-red'); error = true;
		}

		if(!error){
			$('#form-search').submit();
			return true;
		}
		return false;

	});
});

var all_entries_ban = true;
$(function() {

	$("#qm").keyup(function (e) {
		if(all_entries_ban && $(this).val().trim() == ''){
			$('.e-search-entries').css('display', 'none');
			$('.e-all-entries').css('display', 'block');
			all_entries_ban = false;
		}
	});

	$("#qs, #qe1, #qe2, #qe3").keyup(function (e) {
		if(($('#e-' + $(this).attr('id')).length > 0) && all_entries_ban && $(this).val().trim() == ''){
			$('.e-search-entries').css('display', 'none');
			$('.e-all-entries').css('display', 'block');
			if($(this).attr('id') != 'qs'){
				var aux_id = $(this).attr('id').replace('qe', '');
				$('#fecha1' + aux_id + ', #fecha2' + aux_id + '').val('');
			}
			all_entries_ban = false;
		}
	});

	$('#btn-search-sepelios').click(function(){
		$('#qs').removeClass('alert-border-red');
		var error = false;
		
		if($('#qs').val().trim() == ''){
			if(!error) $('#qs').focus();
			$('#qs').addClass('alert-border-red'); error = true;
		}

		if(!error){
			$('#form-search-sepelios').submit();
			return true;
		}
		return false;

	});
});

$(function() {
	$('.btn_buscar_exequias').click(function(){

		var num = $(this).attr('data-num');

		$('#qe' + num + ', #fecha1' + num + ', #fecha2' + num + '').removeClass('alert-border-red');
		var error = false;
		
		if($('#qe' + num).val().trim() == ''){
			if(!error) $('#qe' + num).focus();
			$('#qe' + num).addClass('alert-border-red'); error = true;
		}

		if($('#fecha1' + num).val().trim() == ''){
			if(!error) $('#fecha1' + num).focus();
			$('#fecha1' + num).addClass('alert-border-red'); error = true;
		}

		if($('#fecha2' + num).val().trim() == ''){
			if(!error) $('#fecha2' + num).focus();
			$('#fecha2' + num).addClass('alert-border-red'); error = true;
		}

		if(!error){
			return true;
		}
		return false;

	});
});

$(function() {
	$('#btn-search-misas').click(function(){
		$('#qm').removeClass('alert-border-red');
		var error = false;
		
		if($('#qm').val().trim() == ''){
			if(!error) $('#qm').focus();
			$('#qm').addClass('alert-border-red'); error = true;
		}

		if(!error){
			return true;
		}
		return false;

	});
});