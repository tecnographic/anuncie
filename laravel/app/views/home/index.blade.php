@extends('layouts.main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			<div class="col-xs-2 contCategorias contAnaranjado">

				<h3 style="text-align:left;">Categorías</h3>
				<ul class="categorias">
				@foreach($categories as $category)
					<li><a href="{{ URL::to('publicaciones/categorias/'.$category->id) }}">{{$category->nombre }}</a></li>
				@endforeach
				</ul>
				<h3 style="text-align:left;">Servicios</h3>
				<ul class="categorias">
				@foreach($servicios as $servicio)
					<li><a href="{{ URL::to('publicaciones/categorias/'.$servicio->id) }}">{{$servicio->nombre }}</a></li>
				@endforeach
				</ul>
			</div>
			<div class="col-xs-10 contBanner">
				<div class="col-xs-12">
					@if(Session::has('error'))
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}.</p>
					</div>

					@endif
				</div>
				<div class="col-xs-12 ">
					<img src="{{ asset('images/portada.png') }}">
				</div>
				<div class="col-xs-12 banderas">
					<div class="bolivia">
						<a href="{{ URL::to('publicaciones/departamentos/todos') }}">
							<img src="{{ asset('images/bolivia.png') }}" style="display:block;margin:0 auto;">
						</a>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="row">

		<div class="col-xs-12 catInv" style="margin-bottom: 2em;">
			<div class="dropdown">
			  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" style="color:black;margin: 0 auto;
display: block;">
			    Categorías/Servicios
			    <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			  	<li><strong>Categorías</strong></li>
			  	@foreach($categories as $category)
			  		<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::to('publicaciones/categorias/'.$category->id) }}">{{$category->nombre }}</a></li>
				@endforeach
				<li><strong>Servicios</strong></li>
			   	@foreach($servicios as $servicio)
					<li><a href="{{ URL::to('publicaciones/categorias/'.$servicio->id) }}">{{$servicio->nombre }}</a></li>
				@endforeach
			  </ul>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div clas="col-xs-12">
					<div class="container">
						<div clas="col-xs-12">
							<img src="{{ asset('images/pubgrande.png') }}" style="width:100%;">
						</div>
						<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
							<div class="col-xs-6" style="padding-left:0px;">
								<img src="{{ asset('images/pubpeq.png') }}" style="width:100%;">
							</div>
							<div class="col-xs-6" style="padding-right: 0px;">
								<img src="{{ asset('images/pubpeq.png') }}" style="width:100%;">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				

			</div>
		</div>
	</div>
</div>
@stop

@section('postscript')
<script type="text/javascript">
      $(document).ready(function(){
      	$('.owl-carousel1').owlCarousel({
		    loop:true,
		    margin:30,
		    nav:true,
		    navText: [ 'Anterior', 'Siguiente' ],
		    itemsScaleUp:true,
		    stagePadding: 0,
		    autoplay:true,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			items : 3,
      		itemsDesktop : [1200,3],
      		itemsDesktopSmall : [979,2],
		    responsive:{
		        0:{
		            items:1
		        },
		        800:{
		            items:2
		        },
		        1200:{
		            items:4
		        }
		    }
		})
		  $('.owl-carousel2').owlCarousel({
		    loop:true,
		    margin:30,
		    nav:true,
		    navText: [ 'Anterior', 'Siguiente' ],
		    itemsScaleUp:true,
		    stagePadding: 0,
		    autoplay:true,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			items : 3,
      		itemsDesktop : [1200,3],
      		itemsDesktopSmall : [979,2],
		    responsive:{
		        0:{
		            items:1
		        },
		        800:{
		            items:2
		        },
		        1200:{
		            items:4
		        }
		    }
		})
		  $('.owl-carousel3').owlCarousel({
		    loop:true,
		    margin:30,
		    nav:true,
		    navText: [ 'Anterior', 'Siguiente' ],
		    itemsScaleUp:true,
		    stagePadding: 0,
		    autoplay:true,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			items : 3,
      		itemsDesktop : [1200,3],
      		itemsDesktopSmall : [979,2],
		    responsive:{
		        0:{
		            items:1
		        },
		        800:{
		            items:2
		        },
		        1200:{
		            items:4
		        }
		    }
		})
	});
   </script>
@stop
