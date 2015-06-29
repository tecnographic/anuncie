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
	public function showIndex()
	{
		$title ="Inicio | anuncie24.com";

		$categories = Categorias::where('deleted','=',0)->where('tipo','=',1)->get();
		$servicios  = Categorias::where('deleted','=',0)->where('tipo','=',2)->get();
		return View::make('home.index')
		->with('title',$title)
		->with('categories',$categories)
		->with('servicios',$servicios);
	}
	public function getContact()
	{
		$title ="Contáctenos";
		return View::make('home.contactUs')->with('title',$title);
	}
	public function getTermsAndConditions()
	{
		$title = "Términos y condiciones | anuncie24.com";
		return View::make('home.termsAndCond')->with('title',$title);
	}
	public function getMision()
	{
		$title = "Misión y visión | anuncie24.com";
		return View::make('home.mision')
		->with('title',$title);
	}
	public function getPolitics()
	{
		$title = "Política de privacidad | anuncie24.com";
		return View::make('home.politics')
		->with('title',$title);
	}
	public function getSearch()
	{

		$input = Input::all();
		$title = "Búsqueda | anuncie24.com";


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
