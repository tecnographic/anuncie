<?php

class AdministratorController extends BaseController {

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
	public function getPagos()
	{
		$title = "Pagos | Administrador";
		$publicaciones =Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
		->join('categoria','categoria.id','=','publicaciones.categoria')
		->join('pagos','publicaciones.id','=','pagos.pub_id')
		->where('status','=','Procesando')
		->orderBy('publicaciones.created_at','DESC')
		->get(array('usuario.username',
			'pagos.num_trans',
			'publicaciones.titulo',
			'categoria.desc as categoria',
			'publicaciones.ubicacion',
			'publicaciones.id',
			'publicaciones.fechIni',
			'publicaciones.fechFin'));
		return View::make('admin.pagos')
		->with('title',$title)
		->with('publicaciones',$publicaciones);
	}
	public function postPagos()
	{
		$id = Input::get('id');
		$publicacion = Publicaciones::find($id);
		$titulo = $publicacion->titulo;
		if($publicacion->tipo == 'Lider')
		{
			$url = "lider";
		}elseif ($publicacion->tipo == 'Habitual') {
			$url = "habitual";
		}elseif ($publicacion->tipo == 'Casual') {
			$url = "casual";
		}
		$publicacion->status = 'Aprobado';
		if ($publicacion->tipo == 'Habitual') {
			if ($publicacion->ubicacion == "Principal") {
				$publicacion->fechIni = date('Y-m-d');
				$publicacion->fechFin = date('Y-m-d',time()+$publicacion->duracion);
			}elseif($publicacion->ubicacion == "Categoria")
			{
				$publicacion->fechIniNormal = date('Y-m-d');
				$publicacion->fechFinNormal = date('Y-m-d',time()+$publicacion->duracionNormal);
			}elseif($publicacion->ubicacion == "Ambos")
			{
				$publicacion->fechIni = date('Y-m-d');
				$publicacion->fechFin = date('Y-m-d',time()+$publicacion->duracion);
				$publicacion->fechIniNormal = date('Y-m-d');
				$publicacion->fechFinNormal = date('Y-m-d',time()+$publicacion->duracionNormal);
			}
		}
		$subject = "Correo de administrador";
		$admin = Auth::user()['username'];
		$data = array(
			'subject' => $subject,
			'publicacion' => $titulo,
			'creadoPor'=> $admin
		);
		$to_Email = 'ffasilsrl@gmail.com';
		Mail::send('emails.aprvPub', $data, function($message) use ($titulo,$admin,$to_Email,$subject)
		{
			$message->to($to_Email)->from('sistema@ffasil.com')->subject($subject);
		});
		$publicacion->motivo = "";
		if ($publicacion->save()) {
			Session::flash('success', 'Publicación aprobada sactisfactoriamente');
			return Redirect::to('administrador/pagos/'.$url);
		}else
		{
			Session::flash('error', 'No se pudo aprobar la publicación');
			return Redirect::to('administrador/pagos');
		}
	}
	public function postPagosCancel()
	{
		$id = Input::get('id');
		$publicacion = Publicaciones::find($id);
		$publicacion->status = 'Rechazado';
		$publicacion->motivo = Input::get('motivo');
		if ($publicacion->save()) {
			Session::flash('success', 'Publicación rechazada sactisfactoriamente');
			return Redirect::to('administrador/pagos/lider');
		}else
		{
			Session::flash('error', 'No se pudo aprobar la publicacion');
			return Redirect::to('administrador/pagos/lider');
		}
	}

	public function getPagosType($type)
	{
		$title ="Verificar pagos";
		if ($type == "lider") {
			$publicaciones = Publicaciones::where('publicaciones.tipo','=','Lider')
			->join('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('categoria','categoria.id','=','publicaciones.categoria')
			->join('pagos','pagos.pub_id','=','publicaciones.id')
			->leftJoin('bancos','bancos.id','=','pagos.banco_id')
			->where('publicaciones.deleted','=',0)
			->where('publicaciones.status','=','Procesando')
			->orderBy('publicaciones.created_at','desc')
			->distinct()
			->get(array(
				'usuario.username',
				'usuario.name',
				'usuario.lastname',
				'usuario.email',
				'usuario.phone',
				'usuario.pag_web',
				'usuario.id_carnet',
				'usuario.nit',
				'publicaciones.titulo',
				'publicaciones.ubicacion',
				'categoria.desc',
				'publicaciones.fechIni',
				'publicaciones.fechFin',
				'pagos.num_trans',
				'pagos.fech_trans',
				'pagos.banco_ext',
				'bancos.nombre as banco',
				'publicaciones.id',
				'publicaciones.monto'

			));
		}elseif($type == "habitual")
		{
			$publicaciones = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->join('pagos','publicaciones.id','=','pagos.pub_id')
			->leftJoin('bancos','bancos.id','=','pagos.banco_id')
			->join('categoria','categoria.id','=','publicaciones.categoria')
			->where('publicaciones.status','=','Procesando')
			->where('publicaciones.tipo','=','Habitual')
			->where('publicaciones.deleted','=',0)
			->orderBy('publicaciones.created_at','desc')
			->distinct()
			->get(array(
				'usuario.username',
				'usuario.name',
				'usuario.lastname',
				'usuario.email',
				'usuario.phone',
				'usuario.pag_web',
				'usuario.id_carnet',
				'usuario.nit',
				'pagos.num_trans',
				'pagos.fech_trans',
				'pagos.banco_ext',
				'bancos.nombre as banco',
				'publicaciones.titulo',
				'categoria.desc as categoria',
				'publicaciones.ubicacion',
				'publicaciones.id',
				'publicaciones.fechIni',
				'publicaciones.fechFin',
				'publicaciones.monto'
				));
		}elseif($type == "casual")
		{
			$publicaciones = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->join('categoria','categoria.id','=','publicaciones.categoria')
			->where('publicaciones.status','=','Procesando')
			->where('publicaciones.tipo','=','Casual')
			->where('publicaciones.deleted','=',0)
			->orderBy('publicaciones.created_at','desc')
			->distinct()
			->get(array(
				'usuario.username',
				'usuario.name',
				'usuario.lastname',
				'usuario.email',
				'usuario.phone',
				'usuario.pag_web',
				'usuario.id_carnet',
				'usuario.nit',
				'categoria.desc as categoria',
				'publicaciones.titulo',
				'publicaciones.categoria',
				'publicaciones.ubicacion',
				'publicaciones.id',
				'publicaciones.fechIni',
				'publicaciones.fechFin'));
		}
		return View::make('admin.pagos')->with('title',$title)->with('publicaciones',$publicaciones)->with('type',$type);
	}
	public function getPublication()
	{
		$title = "Administración de publicacione | ffasil.com";
		return View::make('admin.publications')->with('title',$title);	
	}
	public function getPublicationType($type)
	{
		$title = "Administración de publicaciones LÍDER | ffasil.com";
		$publicaciones = "";
		if ($type == 'lider') {
			$pub = Publicaciones::leftJoin('categoria','categoria.id','=','publicaciones.categoria')
			->join('usuario','usuario.id','=','publicaciones.user_id')
			->where('publicaciones.tipo','=','Lider')
			->where(function($query){
				$query->where('publicaciones.status','=','Procesando')
				->orWhere('publicaciones.status','=','Aprobado');
			})
			->where('publicaciones.deleted','=',0)
			->orderBy('fechIni','desc')
			->get(array(
				'usuario.username',
				'usuario.name',
				'usuario.lastname',
				'usuario.email',
				'usuario.phone',
				'usuario.pag_web',
				'usuario.id_carnet',
				'usuario.nit',
				'publicaciones.id',
				'publicaciones.titulo',
				'publicaciones.ubicacion',
				'publicaciones.pag_web',
				'categoria.nombre',
				'publicaciones.fechIni',
				'publicaciones.fechFin'
			));
			$publicaciones = $pub;
		}elseif($type == 'habitual')
		{
			$pub = Publicaciones::join('categoria','categoria.id','=','publicaciones.categoria')
			->join('usuario','usuario.id','=','publicaciones.user_id')
			->where('publicaciones.tipo','=','Habitual')
			->where('publicaciones.deleted','=',0)
			->where(function($query){
				$query->where('publicaciones.status','=','Procesando')
				->orWhere('publicaciones.status','=','Aprobado');
			})
			->orderBy('fechIniNormal','desc')
			->get(array(
				'usuario.username',
				'usuario.name',
				'usuario.lastname',
				'usuario.email',
				'usuario.phone',
				'usuario.pag_web',
				'usuario.id_carnet',
				'usuario.nit',
				'publicaciones.id',
				'publicaciones.titulo',
				'publicaciones.ubicacion',
				'categoria.desc as categoria',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.fechIni',
				'publicaciones.fechFin',
				'publicaciones.fechIniNormal',
				'publicaciones.fechFinNormal'
			));
			$publicaciones = $pub;
		}elseif($type == 'casual')
		{
			$pub = Publicaciones::join('categoria','categoria.id','=','publicaciones.categoria')
			->join('usuario','usuario.id','=','publicaciones.user_id')
			->where('publicaciones.tipo','=','Casual')
			->where('publicaciones.deleted','=',0)
			->get(array(
				'usuario.username',
				'usuario.name',
				'usuario.lastname',
				'usuario.email',
				'usuario.phone',
				'usuario.pag_web',
				'usuario.id_carnet',
				'usuario.nit',
				'publicaciones.id',
				'publicaciones.titulo',
				'publicaciones.ubicacion',
				'categoria.nombre',
				'publicaciones.fechIni',
				'publicaciones.fechFin'
			));
			$publicaciones = $pub;
		}
		return View::make('admin.publications')
		->with('title',$title)
		->with('type',$type)
		->with('publications',$publicaciones);
	}
	public function getNewAdmin()
	{
		$title = "Crear nuevo administrador | ffasil.com";
		return View::make('admin.createUser')->with('title',$title);
	}
	public function postNewAdmin()
	{
		$input = Input::all();
		$user = User::where('username','=',$input['adminUser'])->get();
		if (count($user)>0) {
			Session::flash('error', 'El nombre de usuario ya existe');
			return Redirect::to('administrador/crear-nuevo');
		}else
		{
			$rules = array(
				'adminUser' => 'required',
				'pass' => 'required|min:8',
				'pass2' => 'required|same:pass'
			);		
			$messages = array(
				'required' => ':attribute es obligatorio',
				'min'	   => ':attribute debe tener al menos 8 caracteres',
				'same'	   => ':attribute no coincide'
			);
			$attributes = array(
				'adminUser'  => 'El campo nombre de administrador',
				'pass'  => 'El campo contraseña nueva',
				'pass2'  => 'El campo repetir contraseña'
			);
			$validator = Validator::make($input, $rules, $messages, $attributes);
			if ($validator->fails()) {
				return Redirect::to('administrador/crear-nuevo')->withErrors($validator)->withInput();
			}
			$user = new User;
			$user->username = $input['adminUser'];
			$user->password = Hash::make($input['pass']);
			$user->email    = $input['adminUser'].'@ffasil.com';
			$user->role     = 'Administrador';

			if ($user->save()) {
				$data = array(
					'username' => $input['adminUser'],
					'createBy' => Auth::user()['username']
				);
				Mail::send('emails.newAdmin', $data, function ($message) use ($input){
					    $message->subject('Correo creacion de usuario ffasil.com');
					    $message->to('someemail@ffasil.com');
					});
				Session::flash('success', 'El usuario fue creado satisfactoriamente');
				return Redirect::to('administrador/crear-nuevo');
			}else
			{
				Session::flash('error', 'la contraseña no se pudo cambiar.');
				return Redirect::to('administrador/crear-nuevo');
			}
		}
	}
	public function getUserElim()
	{
		$title ="Eliminar usuarios | ffasil.com";
		$user = User::leftJoin('departamento','usuario.state','=','departamento.id')
		->where('usuario.user_deleted','=',0)
		->where('usuario.role','=','Usuario')
		->get(array(
			'usuario.username',
			'usuario.name',
			'usuario.lastname',
			'usuario.email',
			'usuario.id_carnet',
			'usuario.id',
			'usuario.nit',
			'usuario.role',
			'departamento.nombre'
		));
		
		return View::make('admin.elimUsers')->with('title',$title)->with('users',$user);
	}
	public function  postUserElim()
	{
		if (Request::ajax()) {
			$id = Input::get('id');
			$pub = Publicaciones::where('user_id','=',$id)->get();
			$comment = Comentarios::where('user_id','=',$id)->get();
			$resp    = Respuestas::where('user_id','=',$id)->get();
			$user 	 = User::find($id);
			if (count($pub)>0) {
				foreach($pub as $p)
				{
					$p->deleted = 1;
					$p->save();
				}
			}
			if (count($comment)>0) {
				foreach ($comment as $c) 
				{
					$c->deleted = 1;
					$c->save();
				}
			}
			
			if (count($resp)>0) {
				foreach ($resp as $r) {
					$r->deleted = 1;
					$r->save();
				}
			}
			$user->user_deleted = 1;
			$username = $user->username;

			$subject = "Correo de administrador";
			$admin = Auth::user()['username'];

			$data = array(
				'subject' => $subject,
				'usuario' => $username,
				'creadoPor'=> $admin
			);

			$to_Email = 'ffasilsrl@gmail.com';

			Mail::send('emails.elimUser', $data, function($message) use ($admin,$to_Email,$subject,$username)
			{
				$message->to($to_Email)->from('sistema@ffasil.com')->subject($subject);
			});
			$user->save();

			return Response::json(array('type' => 'success','msg' => 'Usuario eliminado satisfactoriamente.'));
		}
	}
	public function postElimPub()
	{
		$id = Input::get('id');
		$pub = Publicaciones::find($id);
		$titulo = $pub->titulo;
		$comment = Comentarios::where('pub_id','=',$id)->get();
		$resp    = Respuestas::where('pub_id','=',$id)->get();
		if (count($comment)>0) {
			foreach ($comment as $c) 
			{
				$c->deleted = 1;
				$c->save();
			}
		}
		
		if (count($resp)>0) {
			foreach ($resp as $r) {
				$r->deleted = 1;
				$r->save();
			}
		}
		$userid = $pub->user_id;
		$user = User::find($userid);
		$subject = "Correo de Aviso";

		$data = array(
			'subject' => $subject,
			'publicacion' => $titulo
		);
		$to_Email = $user->email;
		Mail::send('emails.elimPubUser', $data, function($message) use ($titulo,$to_Email,$subject)
		{
			$message->to($to_Email)->from('sistema@ffasil.com')->subject($subject);
		});

		$pub->deleted = 1;
		$pub->save();

		$subject = "Correo de administrador";
		$admin = Auth::user()['username'];

		$data = array(
			'subject' => $subject,
			'publicacion' => $titulo,
			'creadoPor'=> $admin
		);
		$to_Email = 'ffasilsrl@gmail.com';
		Mail::send('emails.elmPub', $data, function($message) use ($titulo,$admin,$to_Email,$subject)
		{
			$message->to($to_Email)->from('sistema@ffasil.com')->subject($subject);
		});
		return Response::json(array('type' => 'success','msg' => 'Publicación eliminada satisfactoriamente. Hemos enviado un email al correo.'));
	}
	public function getModifyPub()
	{
		$textos = Textos::all();
		$title = "Cambiar texto | ffasil.com";
		return View::make('admin.modifyText')
		->with('title',$title)
		->with('textos',$textos);
	}
	public function postModifyPub()
	{
		$input = Input::all();
		$rules = array(
			'desc1' => 'required',
			'desc2' => 'required',
			'desc3' => 'required',
		);  

		$messages = array(
			'required' => ':attribute es obligatorio'
		);
		$attributes = array(
			'desc1' => 'El texto Lider',
			'desc2' => 'El texto Habitual',
			'desc3' => 'El texto Casual'
		);
		$validator = Validator::make($input, $rules, $messages, $attributes);
		if ($validator->fails()) {
			return Redirect::to('administrador/modificar-publicaciones')->withErrors($validator);
		}
		$text1 = Textos::find(1);
		$text1->desc = $input['desc1'];
		$text1->save();
		$text2 = Textos::find(2);
		$text2->desc = $input['desc2'];
		$text2->save();
		$text3 = Textos::find(3);
		$text3->desc = $input['desc3'];
		$text3->save();

		$subject = "Correo de administrador";
		$admin = Auth::user()['username'];

		$data = array(
			'subject' => $subject,
			'creadoPor'=> $admin
		);
		$to_Email = 'ffasilsrl@gmail.com';
		Mail::send('emails.mdfText', $data, function($message) use ($admin,$to_Email,$subject)
		{
			$message->to($to_Email)->from('sistema@ffasil.com')->subject($subject);
		});
		Session::flash('success', 'Textos guardados sactisfactoriamente. Se ha enviado un correo al administrador.');
		return Redirect::to('administrador/modificar-publicaciones');
	}
	public function getModifyPrice()
	{
		$title = 'Cambiar precios | ffasil.com';
		$princ = Precios::where('pub_type_id','=',1)->get();
		$cat   = Precios::where('pub_type_id','=',2)->get();
		return View::make('admin.modifyPrice')
		->with('title',$title)
		->with('princ',$princ)
		->with('cat',$cat);
	}
	public function postModifyPrice()
	{
		$input = Input::all();
		if (isset($input['princ1'])) {
			$texto = Precios::find(1);
			$texto->precio = $input['princ1'];
			$texto->save();
		}
		if (isset($input['princ2'])) {
			$texto = Precios::find(2);
			$texto->precio = $input['princ2'];
			$texto->save();
		}
		if (isset($input['princ3'])) {
			$texto = Precios::find(3);
			$texto->precio = $input['princ3'];
			$texto->save();
		}
		if (isset($input['cat4'])) {
			$texto = Precios::find(4);
			$texto->precio = $input['cat4'];
			$texto->save();
		}
		if (isset($input['cat5'])) {
			$texto = Precios::find(5);
			$texto->precio = $input['cat5'];	
			$texto->save();
		}
		if (isset($input['cat6'])) {
			$texto = Precios::find(6);
			$texto->precio = $input['cat6'];
			$texto->save();
		}
		$subject = "Correo de administrador";
		$admin = Auth::user()['username'];
		$data = array(
			'subject' => $subject,
			'creadoPor'=> $admin
		);
		$to_Email = 'ffasilsrl@gmail.com';
		Mail::send('emails.mdfPrice', $data, function($message) use ($admin,$to_Email,$subject)
		{
			$message->to($to_Email)->from('sistema@ffasil.com')->subject($subject);
		});
		Session::flash('success', 'Precios cambiados correctamentes');
		return Redirect::to('administrador/modificar-precios');
	}

	public function getAddAccount()
	{
		$title = "Nueva cuenta | ffasil.com";
		$bancos = Bancos::all();
		return View::make('admin.addBank')
		->with('title',$title)
		->with('bancos',$bancos);
	}
	public function postAddAccount()
	{
		$input = Input::all();
		$rules = array(
			'banco' 	 => 'required',
			'numCuenta'  => 'required',
			'tipoCuenta' => 'required'
		);
		$messages = array(
			'required' => ':attribute es obligatorio'
		);
		$custom = array(
			'banco' => 'El campo banco',
			'numCuenta' => 'El campo numero de cuenta',
			'tipoCuenta'=> 'El campo tipo de cuenta'
		);
		$validator = Validator::make($input, $rules, $messages, $custom);
		if ($validator->fails()) {
			Session::flash('error', 'Error al validar algunos campos.');
			return Redirect::to('administrador/agregar-cuenta')->withErrors($validator)->withInput();
		}
		$numCuenta = new NumCuentas;
		$numCuenta->banco_id   = $input['banco'];
		$numCuenta->num_cuenta = $input['numCuenta'];
		$numCuenta->tipoCuenta = $input['tipoCuenta'];
		$subject = "Correo de administrador";
		$admin = Auth::user()['username'];
		$num = $input['numCuenta'];
		$data = array(
			'subject' => $subject,
			'num' 	  => $num,
			'creadoPor'=> $admin
		);
		$to_Email = 'ffasilsrl@gmail.com';
		Mail::send('emails.newAcc', $data, function($message) use ($admin,$to_Email,$subject,$num)
		{
			$message->to($to_Email)->from('sistema@ffasil.com')->subject($subject);
		});
		if ($numCuenta->save()) {
			Session::flash('success', 'Número de cuenta creado satisfactoriamente.');
			return Redirect::to('administrador/agregar-cuenta');
		}else
		{
			Session::flash('error', 'Error al validar algunos campos.');
			return Redirect::to('administrador/agregar-cuenta')->withInput();
		}
	}
	public function getNewBank()
	{
		$title = "Nuevo banco | ffasil.com";
		return View::make('admin.newBank')
		->with('title',$title);
	}
	public function postNewBank()
	{
		$input = Input::all();
		$rules = array(
			'banco'	=> 'required',
			'img'	=> 'required|image'
		);
		$msg = array(
			'required'  => 'El campo es obligatorio',
			'image'		=> 'El archivo debe ser una imagen'
		);
		$validator = Validator::make($input,$rules,$msg);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$file 	 = Input::file('img');
		$banco  = new Bancos;
		$banco->nombre =  $input['banco'];
		if (file_exists('images/bancos/'.$file->getClientOriginalName())) {
			//guardamos la imagen en public/imgs con el nombre original
            $i = 0;//indice para el while
            //separamos el nombre de la img y la extensión
            $info = explode(".",$file->getClientOriginalName());
            //asignamos de nuevo el nombre de la imagen completo
            $miImg = $file->getClientOriginalName();
            //mientras el archivo exista iteramos y aumentamos i
            while(file_exists('images/bancos/'. $miImg)){
                $i++;
                $miImg = $info[0]."(".$i.")".".".$info[1];              
            }
            //guardamos la imagen con otro nombre ej foto(1).jpg || foto(2).jpg etc
            $file->move("images/bancos/",$miImg);
            $blank = Image::make('images/blank.jpg');

            $img = Image::make('images/bancos/'.$miImg);
            if ($img->width() > $img->height()) {
            	$img->widen(400);
            }else
            {
            	$img->heighten(200);
            }
            if ($img->height()>200) {
            	$img->heighten(200);
            }
	        $blank->insert($img,'center')
	           ->interlace()
	           ->save('images/bancos/'.$miImg);
            if($miImg != $file->getClientOriginalName()){
            	$banco->image = $miImg;
            }
		}else
		{
			$file->move("images/bancos/",$file->getClientOriginalName());
			$blank = Image::make('images/blank.jpg');
			$img = Image::make('images/bancos/'.$file->getClientOriginalName());
            if ($img->width() > $img->height()) {
            	$img->widen(400);
            }else
            {
            	$img->heighten(300);
            }
            if ($img->height()>200) {
            	$img->heighten(200);
            }
            $blank->insert($img,'center')
           ->interlace()
           ->save('images/bancos/'.$file->getClientOriginalName());
           $banco->image = $file->getClientOriginalName();
		}
		if ($banco->save()) {
			Session::flash('success', 'Banco creado correctamente');
			return Redirect::to('administrador/editar-bancos');
		}else
		{
			Session::flash('error','Error al crear el banco');
			return Redirect::back();
		}
	}
	public function getEditBanks()
	{
		$title = 'Editar bancos | ffasil.com';
		$bancos= Bancos::where('deleted','=',0)->get();
		return View::make('admin.editBank')
		->with('title',$title)
		->with('bancos',$bancos);
	}
	public function getEditBankSelf($id)
	{
		$title = 'Modificar banco | ffasil.com';
		$bancos = Bancos::find($id);
		return View::make('admin.newBank')
		->with('title',$title)
		->with('banco',$bancos);
	}
	public function postEditBankSelf()
	{
		$input = Input::all();
		$rules = array('banco' 	  => 'required');
		$msg   = array('required' => 'campo obligatorio');
		$validator = Validator::make($input,$rules,$msg);
		if ($validator->fails()) {
			return Redirect::back()->withInput()->withErrors($validator);
		}
		$banco = Bancos::find(Input::get('id'));
		$banco->nombre = Input::get('banco');
		if (Input::hasFile('img')) {
			$file = Input::file('img');
			if (file_exists('images/bancos/'.$file->getClientOriginalName())) {
				//guardamos la imagen en public/imgs con el nombre original
	            $i = 0;//indice para el while
	            //separamos el nombre de la img y la extensión
	            $info = explode(".",$file->getClientOriginalName());
	            //asignamos de nuevo el nombre de la imagen completo
	            $miImg = $file->getClientOriginalName();
	            //mientras el archivo exista iteramos y aumentamos i
	            while(file_exists('images/bancos/'. $miImg)){
	                $i++;
	                $miImg = $info[0]."(".$i.")".".".$info[1];              
	            }
	            //guardamos la imagen con otro nombre ej foto(1).jpg || foto(2).jpg etc
	            $file->move("images/bancos/",$miImg);
	            $blank = Image::make('images/blank.jpg');

	            $img = Image::make('images/bancos/'.$miImg);
	            if ($img->width() > $img->height()) {
	            	$img->widen(400);
	            }else
	            {
	            	$img->heighten(200);
	            }
	            if ($img->height()>200) {
	            	$img->heighten(200);
	            }
		        $blank->insert($img,'center')
		           ->interlace()
		           ->save('images/bancos/'.$miImg);
	            if($miImg != $file->getClientOriginalName()){
	            	$banco->image = $miImg;
	            }
			}else
			{
				$file->move("images/bancos/",$file->getClientOriginalName());
				$blank = Image::make('images/blank.jpg');
				$img = Image::make('images/bancos/'.$file->getClientOriginalName());
	            if ($img->width() > $img->height()) {
	            	$img->widen(400);
	            }else
	            {
	            	$img->heighten(300);
	            }
	            if ($img->height()>200) {
	            	$img->heighten(200);
	            }
	            $blank->insert($img,'center')
	           ->interlace()
	           ->save('images/bancos/'.$file->getClientOriginalName());
	           $banco->image = $file->getClientOriginalName();
			}
		}
		if ($banco->save()) {
			Session::flash('success', 'Banco creado correctamente');
			return Redirect::to('administrador/editar-bancos');
		}else
		{
			Session::flash('error','Error al crear el banco');
			return Redirect::back();
		}
	}
	public function postElimBank()
	{
		if (Request::ajax()) {
			$id = Input::get('id');
			$banco = Bancos::find($id);
			$banco->deleted = 1;
			if ($banco->save()) {
				return Response::json(array('type' => 'success','msg' => 'Banco eliminado satisfactoriamente'));
			}else
			{
				return Response::json(array('type' => 'danger','msg' => 'Error al eliminar el banco'));
			}
		}
	}
}