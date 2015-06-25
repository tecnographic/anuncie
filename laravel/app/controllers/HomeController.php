<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	public function showFront()
	{
		$title = "ffasil.com el portal de comercio hecho por bolivianos para bolivianos";
		return View::make('portada')->with('title',$title);
	}
	public function showIndex()
	{
		$title ="Inicio | ffasil.com";
		$lider = Publicaciones::where('status','=','Aprobado')
		->where('ubicacion','=','Principal')
		->where('tipo','=','Lider')
		->where('pag_web','!=',"")
		->where('fechFin','>=',date('Y-m-d',time()))
		->where('deleted','=',0)
		->orderBy('fechFin','desc')->get();

		$habitual = Publicaciones::where(function($query){
			/*Busco las habituales*/
			$query->where('tipo','=','Habitual')
			->where(function($query){
				/*Que vayan en la principal*/
				$query->where('ubicacion','=','Principal')
				->orWhere('ubicacion','=','Ambos')
				->where('status','=','Aprobado');

			})
			->where(function($query){
				/*y que sigan activas*/
				$query->where('fechFin','>=',date('Y-m-d',time()))
				->orWhere('fechFinNormal','>=',date('Y-m-d',time()))
				->where('status','=','Aprobado');

			});
		})
		->orWhere(function($query){
			$query->where('tipo','=','Lider')
			->where('ubicacion','=','Principal')
			->where('pag_web','=',"")
			->where('status','=','Aprobado');

		})
		->orderBy('fechFin','desc')->get();
		/*->where('tipo','=','Habitual')
		->where(function($query){
			$query->where('ubicacion','=','Principal')
			->orWhere('ubicacion','=','Ambos');
		})
		->where(function($query){
			$query->where('fechFin','>=',date('Y-m-d',time()))
			->orWhere('fechFinNormal','>=',date('Y-m-d',time()));
		})
		*/
		
		$casual = Publicaciones::where('tipo','=','Casual')
		->where('fechFin','>=',date('Y-m-d',time()))
		->where('status','=','Aprobado')
		->where('deleted','=',0)
		->get();
		$categories = Categorias::where('deleted','=',0)->where('tipo','=',1)->get();
		$servicios  = Categorias::where('deleted','=',0)->where('tipo','=',2)->get();
		$departamentos = Department::get();
		return View::make('index')
		->with('title',$title)
		->with('lider',$lider)
		->with('categories',$categories)
		->with('departamentos',$departamentos)
		->with('habitual',$habitual)
		->with('casual',$casual)
		->with('servicios',$servicios);
	}
	public function getContact()
	{
		$title ="Contáctenos";
		return View::make('contactUs')->with('title',$title);
	}
	public function getTermsAndConditions()
	{
		$title = "Términos y condiciones | ffasil.com";
		return View::make('termsAndCond')->with('title',$title);
	}
	public function getMision()
	{
		$title = "Misión y visión | ffasil.com";
		return View::make('mision')
		->with('title',$title);
	}
	public function getPolitics()
	{
		$title = "Política de privacidad | ffasil.com";
		return View::make('politics')
		->with('title',$title);
	}
	public function getSearch()
	{

		$input = Input::all();
		$title = "Búsqueda | ffasil.com";
		
		
		$lider = DB::select("SELECT `publicaciones`.`id`,`publicaciones`.`img_1` 
			FROM  `publicaciones` 
			LEFT JOIN  `categoria` ON  `categoria`.`id` =  `publicaciones`.`categoria` 
			WHERE (
			LOWER(  `publicaciones`.`titulo` ) LIKE  '%".strtolower($input['busq'])."%'
			OR LOWER( `publicaciones`.`pag_web` ) LIKE  '%".strtolower($input['busq'])."%'
			OR LOWER( `publicaciones`.`descripcion` ) LIKE  '%".strtolower($input['busq'])."%'
			OR LOWER( `categoria`.`desc` ) LIKE  '%".strtolower($input['busq'])."%'
			)
			AND  `publicaciones`.`tipo` =  'Lider'
			AND  `publicaciones`.`status` =  'Aprobado'
			AND  `publicaciones`.`deleted` =0");

		$res =  DB::select("SELECT `publicaciones`.`id`,`publicaciones`.`img_1`,`publicaciones`.`titulo`,`publicaciones`.`precio`,`publicaciones`.`moneda` 
			FROM  `publicaciones` 
			LEFT JOIN  `categoria` ON `publicaciones`.`categoria` = `categoria`.`id`
			LEFT JOIN  `departamento` ON  `publicaciones`.`departamento` =  `departamento`.`id`  
			
			WHERE (
			LOWER(  `publicaciones`.`titulo` ) LIKE  '%".strtolower($input['busq'])."%'
			OR LOWER( `departamento`.`nombre` ) LIKE  '%".strtolower($input['busq'])."%'
			OR LOWER( `publicaciones`.`descripcion` ) LIKE  '%".strtolower($input['busq'])."%'
			OR LOWER( `categoria`.`desc` ) LIKE  '%".strtolower($input['busq'])."%'
			)
			AND  `publicaciones`.`tipo` !=  'Lider'
			AND  `publicaciones`.`status` =  'Aprobado'
			AND  `publicaciones`.`deleted` =0");
		return View::make('publications.busq')
		->with('publicaciones',$res)
		->with('title',$title)
		->with('busq',$input['busq'])
		->with('lider',$lider);
	}
	public function getVerifyComment()
	{
		if (Request::ajax()) {
			$comment = Comentarios::join('publicaciones','publicaciones.id','=','comentario.pub_id')
			->where('publicaciones.user_id','=',Auth::id())
			->where('comentario.respondido','=',0)
			->count();
			return $comment;
		}
	}
}
