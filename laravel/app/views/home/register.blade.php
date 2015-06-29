@extends('layouts.main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">

			<div class="col-xs-8 col-sm-offset-2 contForm contAnaranjado" style="margin-top:2em;">
				@if (Session::has('error'))
				<div class="col-xs-6">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				<div class="clearfix"></div>
				@endif
				<div class="col-xs-12">
					<div class="col-xs-12">
						<legend>Formulario de registro</legend>
						<p class="textoPromedio">Llene el siguiente formulario para registrarse en anuncie24.com.</p>
						<p class="textoPromedio">(*) Campos obligatorios.</p>
						<hr>
					</div>
				</div>
				<form action="{{ URL::to('inicio/registro/enviar') }}" method="POST" class="form-register">
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Nombre de usuario:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::text('username', Input::old('username'),
								array(
									'class' 				=> 'form-control username',
									'placeholder' 	=> 'Nombre de Usuario',
									'required' 			=> 'required',
									'data-toggle'		=> "popover",
									'data-placement'=> "right",
									'title'					=> "Atención",
									'data-content'  => "Minimo 4 caracteres")) }}
							@if ($errors->has('username'))
								 @foreach($errors->get('username') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Contraseña:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::password('pass',
								array(
									'class' => 'form-control pass',
									'placeholder' => 'Contraseña',
									'required' => 'required',
									'data-toggle'		=> "popover",
									'data-placement'=> "right",
									'title'					=> "Atención",
									'data-content'  => "Minimo 6 caracteres")) }}
							@if ($errors->has('pass'))
								 @foreach($errors->get('pass') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Repita la contraseña:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::password('pass_confirmation',
								array(
									'class' 			=> 'form-control pass2',
									'placeholder' => 'Contraseña',
									'required' 		=> 'required')) }}
							@if ($errors->has('pass_confirmation'))
								 @foreach($errors->get('pass_confirmation') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Email:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::text('email', Input::old('email'),
								array(
									'class' 				=> 'form-control email',
									'placeholder' 	=> 'Email',
									'required' 			=> 'required',
									'data-toggle'		=> "popover",
									'data-placement'=> "right",
									'title'					=> "Atención",
									'data-content'  => "ejemplo@dominio.com")) }}
							@if ($errors->has('email'))
								 @foreach($errors->get('email') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>

					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Captcha:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							<div class="g-recaptcha" id="ejemplo"></div>
							@if ($errors->has('g-recaptcha-response'))

								 @foreach($errors->get('g-recaptcha-response') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>


				</form>
				<div class="col-xs-12 formulario">
					<div class="col-xs-6 imgLiderUp">
						<input type="submit" id="enviar" name="enviar" value="Enviar" class="disabled btn btn-success btnAlCien">
						<input type="reset" value="Borrar" class="disabled btn btn-warning btnWarningRegister btnAlCien" >
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('postscript')
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
var verifyCallback = function(response) {
	$('.btn').removeClass('disabled');
}
grecaptcha.render('ejemplo', {
				'sitekey' : '6LegAQkTAAAAALWgG9zZIY1UQGcS4eh0Ki4r-Nq3',
				'callback' : verifyCallback,
				'theme'    : 'light'
			});
$(document).ready(function() {
		$('.form-control').popover()
});
</script>
@stop
