@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-6 contAnaranjado contenedorCentrado" style="float:none;margin-top:2em;">
			<form method="POST" 
			@if(isset($banco)) 
				action="{{ URL::to('administrador/editar-banco/enviar')}}"  
			@else
				action="{{ URL::to('administrador/nuevo-banco/enviar')}}"  
			@endif

			enctype="multipart/form-data">
				<legend>Nuevo banco</legend>
				<div class="col-xs-12">
					<label class="textoPromedio">Nombre del banco</label>
					<input type="text" class="form-control" name="banco" 
					@if(isset($banco)) 
						value="{{ $banco->nombre }}"
					@endif>
					@if ($errors->has('banco'))
						 @foreach($errors->get('banco') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<label class="textoPromedio">Imagen del banco</label>
					<input type="file" name="img" class="textoPromedio">
					@if(isset($banco))
						<label class="textoPromedio" style="margin-top:2em;">Imagen actual</label>
						<img src="{{ asset('images/bancos/'.$banco->image) }}" style="margin-top:2em;width:100%;">
					@endif
					@if ($errors->has('img'))
						 @foreach($errors->get('img') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<button class="btn btn-success" name="id"
					@if(isset($banco))
						value="{{ $banco->id }}"
					@endif
					>Enviar</button>
				</div>
			</form>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

@stop