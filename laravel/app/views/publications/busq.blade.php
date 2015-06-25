@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			<legend style="margin-bottom:2em;margin-top:2em;text-align:center;">Publicaciones LÍDER encontradas para: "{{ $busq }}"</legend>
			<div class="owl-carousel1">
				@foreach($lider as $pubLider)
				<div class="item">
					<a href="{{ URL::to('publicacion/lider/'.base64_encode($pubLider->id)) }}">
						<img src="{{ asset('images/pubImages/'.$pubLider->img_1) }}" class="imgPubCarousel">
					</a>
				</div>
				@endforeach
				@if(count($lider)<1)
				<div class="item">
						<img src="{{ asset('images/anuncios-01.png') }}">
				</div>
				<div class="item">
						<img src="{{ asset('images/anuncios-02.png') }}">
				</div>
				<div class="item">
						<img src="{{ asset('images/anuncios-03.png') }}">
				</div>
				<div class="item">
						<img src="{{ asset('images/anuncios-04.png') }}">
				</div>
				@elseif(count($lider)>=1 && count($lider)<2)
				<div class="item">
						<img src="{{ asset('images/anuncios-02.png') }}">
				</div>
				<div class="item">
						<img src="{{ asset('images/anuncios-03.png') }}">
				</div>
				<div class="item">
						<img src="{{ asset('images/anuncios-04.png') }}">
				</div>
				@elseif(count($lider)>=2 && count($lider)<3)
				<div class="item">
						<img src="{{ asset('images/anuncios-03.png') }}">
				</div>
				<div class="item">
						<img src="{{ asset('images/anuncios-04.png') }}">
				</div>
				@else
				
				<div class="item">
						<img src="{{ asset('images/anuncios-04.png') }}">
				</div>
				@endif
			</div>
			<div class="col-xs-12">
			
			<div class="clearfix"></div>
				<legend style="text-align:center;margin:2em 0px;">Listado de publicaciones encontradas para: "{{ $busq }}"</legend>
				<div class="contAnaranjado">
				@if(!empty($publicaciones) && count($publicaciones)>0)
				@foreach($publicaciones as $pub)
				<div class="contCat">
					<a href="{{ URL::to('publicacion/habitual/'.base64_encode($pub->id)) }}">
						<div class="col-xs-4 catPub">
								<img src="{{ asset('images/pubImages/'.$pub->img_1) }}" style="width:100%;">
						</div>
					</a>
						<div class="col-xs-4">
							<h3>{{ $pub->titulo }}</h3>
								<label class="textoPromedio" style="display:inline-block;">
									Precio: 
								</label>
								<p class="textoPromedio" style="display:inline-block;">{{ $pub->precio.' '.ucfirst(strtolower($pub->moneda)).'.' }}</p>
						</div>
						<div class="col-xs-4">
							<a href="{{ URL::to('publicacion/habitual/'.base64_encode($pub->id)) }}" class="btn btn-primary">
								<i class="fa fa-hand-o-right">
								</i> Click para entrar en la publicación
							</a>
						</div>
				</div>	

				@endforeach
				@else
					<p class="textoPromedio bg-primary" style="padding:1em;border-radius:4px;text-align:center;margin-top:1em;.">No se encontraron resultados para: "{{ $busq }}".</p>
				@endif
				</div>
			</div>
		</div>
		
	</div>
</div>
@stop

@section('postscript')
<script type="text/javascript">
	jQuery(document).ready(function($) {
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
		            items:3
		        }
		    }
		})
	});
</script>
@stop