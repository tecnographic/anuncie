@extends('layouts.main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			 

			<div class="clearfix"></div>
			<legend style="text-align:center;margin:2em 0px;">Listado de publicaciones para @if(isset($tipoBusq))
				este departamento 
				@else 
				esta categoría
				@endif
			</legend>
			<div class="contAnaranjado" style="margin-bottom:8em;">
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
			<nav role="navigation">
		          <?php  $presenter = new Illuminate\Pagination\BootstrapPresenter($publicaciones); ?>
		          @if ($publicaciones->getLastPage() > 1)
		          <ul class="cd-pagination no-space">
		            <?php
		              $beforeAndAfter = 2;
		           
		              //Página actual
		              $currentPage = $publicaciones->getCurrentPage();
		           
		              //Última página
		              $lastPage = $publicaciones->getLastPage();
		           
		              //Comprobamos si las páginas anteriores y siguientes de la actual existen
		              $start = $currentPage - $beforeAndAfter;
		           
		                  //Comprueba si la primera página en la paginación está por debajo de 1
		                  //para saber como colocar los enlaces
		              if($start < 1)
		              {
		                $pos = $start - 1;
		                $start = $currentPage - ($beforeAndAfter + $pos);
		              }
		           
		              //Último enlace a mostrar
		              $end = $currentPage + $beforeAndAfter;
		           
		              if($end > $lastPage)
		              {
		                $pos = $end - $lastPage;
		                $end = $end - $pos;
		              }
		           
		              //Si es la primera página mostramos el enlace desactivado
		              if ($currentPage <= 1)
		              {
		                echo '<li class="disabled"><span class="textoMedio">Primera</span></li>';
		              }
		              //en otro caso obtenemos la url y mostramos en forma de link
		              else
		              {
		                $url = $publicaciones->getUrl(1);
		           
		                echo '<li><a class="textoMedio" href="'.$url.'">&lt;&lt; Primera</a></li>';
		              }
		           
		              //Para ir a la anterior
		              echo $presenter->getPrevious('&lt; Atras');
		           
		              //Rango de enlaces desde el principio al final, 3 delante y 3 detrás
		              echo $presenter->getPageRange($start, $end);
		           
		              //Para ir a la siguiente
		              echo $presenter->getNext('Adelante &gt;');
		           
		              ////Si es la última página mostramos desactivado
		              if ($currentPage >= $lastPage)
		              {
		                echo '<li class="disabled"><span class="textoMedio">Última</span></li>';
		              }
		              //en otro caso obtenemos la url y mostramos en forma de link
		              else
		              {
		                $url = $publicaciones->getUrl($lastPage);
		           
		                echo '<li><a class="textoMedio" href="'.$url.'">Última &gt;&gt;</a></li>';
		              }
		              ?>
		            @endif
		          </ul>
		        </nav> <!-- cd-pagination-wrapper -->
			@else
				<p class="textoPromedio bg-primary" style="padding:1em;border-radius:4px;text-align:center;margin-top:1em;.">No existen publicaciones para 
					@if(isset($tipoBusq))
					este departamento 
					@else 
					esta categoría.
					@endif</p>
			@endif
			
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