<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\UsuarioToken;
use App\Software;
use App\Programa;
use App\AsignarEtapaProyecto;
use App\UsuarioPrograma;
use App\Etapa;
use DB;
use App\UsuarioEtapa;
use App\BotonAccion;
use App\UsuarioAccion;
use App\UsuarioProyecto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsuarioAccionController extends Controller {

    use \App\Traits\ApiResponser;

    public function __construct() {
        //
    }

    //obtener los programas mediante el id del usuario
    // https://mimcoapps.mimco.com.pe/GestionUsuarios/public/index.php/gsu_obte_prog_medi_idusu
    // intIdUsua = 1

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/gsu_obte_prog_medi_idusu",
     *     tags={"Usuarios Accion"},
     *     summary="Obtener programa mediante el id usuario",
     *     @OA\Parameter(
     *         description="codigo del usuario",
     *         in="path",
     *         name="varCodiUsua",
     *          example="usuarios_usuario",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
     *           *     @OA\Parameter(
     *         description="codigo del programa",
     *         in="path",
     *         name="varCodiProg",
     *            example="ASIG_LIST_OT",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varCodiProg",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiUsua": "usuarios_usuario","varCodiProg": "ASIG_LIST_OT"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="obtenemos los programas que tiene ese usuario"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function gsu_obte_prog_medi_idusu(Request $request) {
        $regla = [
            'varCodiUsua' => 'required|max:255',
            'varCodiProg' => 'required|max:255'
        ];
        $this->validate($request, $regla);


        $varCodiUsua = $request->input('varCodiUsua');
        $varCodiProg = $request->input('varCodiProg');

        $obten_progrma = Programa::where('varCodiProg', '=', $varCodiProg)->first(['intIdProg']);
        $obtener_idusuario = Usuario::where('varCodiUsua', '=', $varCodiUsua)->first(['intIdUsua']);

        $idPrograma = $obten_progrma['intIdProg'];
        // dd($obten_progrma['intIdProg']);
        $obten_botones = UsuarioAccion::join('programas', 'usuario_accion.intIdProg', '=', 'programas.intIdProg')
                ->join('boton_accion', 'usuario_accion.intIdBoton', '=', 'boton_accion.intIdBoton')
                ->where('usuario_accion.intIdUsua', '=', $obtener_idusuario['intIdUsua'])
                ->where('usuario_accion.intIdProg', '=', $idPrograma)
                ->select('usuario_accion.intIdUsua', 'programas.intIdProg', 'programas.varCodiProg', 'boton_accion.intIdBoton', 'boton_accion.varDescBoto')
                ->get();
        return $this->successResponse($obten_botones);
    }

    /**
     * @OA\Get(
     *     path="/GestionUsuarios/public/index.php/list_boto",
     *    tags={"Usuarios Accion"},
     *     summary="lista los usuarios",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista los botones"
     *     )
     * )
     */
    public function list_boto() {
        $list_boto = BotonAccion::join('programas', 'boton_accion.intIdProg', '=', 'programas.intIdProg')
                        ->join('software', 'boton_accion.intIdSoft', '=', 'software.intIdSoft')
                        ->select('boton_accion.intIdBoton', 'boton_accion.varDescBoto', 'programas.intIdProg', 'software.intIdSoft', 'software.varNombSoft', 'programas.varCodiProg', 'boton_accion.acti_usua', 'boton_accion.acti_hora')->get();



        return $this->successResponse($list_boto);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/regi_boto",
     *      tags={"Usuarios Accion"},
     *     summary="registrar los botones ",
     *     @OA\Parameter(
     *         description="Ingrese el id del programa",
     *         in="path",
     *         name="intIdProg",
     *        example="21",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      *     @OA\Parameter(
     *         description="Descripcion del boto que va realizar",
     *         in="path",
     *         name="varDescBoto",
     *        
     *     example="ANULAR AVANCE_PRUEBA",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *        @OA\Parameter(
     *         description="Ingrese el usuario ",
     *         in="path",
     *         name="acti_usua",
     *        example="21",
     *     example="jose_casillo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                 example={"varNumeDni": "25214121"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El Documento de identidad ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_boto(Request $request) {

        $regla = [
            'intIdSoft' => 'required|max:255',
            'intIdProg' => 'required|max:255',
            'varDescBoto' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdSoft = $request->input('intIdSoft');
        $intIdProg = $request->input('intIdProg');
        $varDescBoto = $request->input('varDescBoto');
        $acti_usua = $request->input('acti_usua');


        date_default_timezone_set('America/Lima'); // CDT


        $vali_boton = BotonAccion::where('varDescBoto', '=', $varDescBoto)
                ->first(['varDescBoto', 'intIdBoton']);
        //dd($vali_boton['varDescBoto']);

        if ($vali_boton['varDescBoto'] == null) {
            $regi_boto = BotonAccion::create([
                        'intIdSoft' => $intIdSoft,
                        'intIdProg' => $intIdProg,
                        'varDescBoto' => $varDescBoto,
                        'acti_usua' => $acti_usua,
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);


            $mensaje = [
                'mensaje' => 'Registro Satisfactorio.'
            ];
        } else {
            $mensaje = [
                'mensaje' => 'La descripcion ya existe.'
            ];
        }

        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/vali_boto",
     *      tags={"Usuarios Accion"},
     *     summary="obtiene datos del usuario a través del dni",
     *     @OA\Parameter(
     *         description="Ingrese el id del boton ",
     *         in="path",
     *         name="intIdBoton",
     *     example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdBoton",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdBoton": "2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="muestra la informacion deacuerdo ala idea
     * "
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_boto(Request $request) {
        $regla = [
            'intIdBoton' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $intIdBoton = $request->input('intIdBoton');
        $vali_boton = BotonAccion::where('intIdBoton', '=', $intIdBoton)
                ->select('intIdBoton', 'intIdSoft', 'intIdProg', 'varDescBoto')
                ->get();

        return $this->successResponse($vali_boton);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/actua_boto",
     *     tags={"Usuarios Accion"},
     *     summary="actualizar boton",
     *     @OA\Parameter(
     *         description="ingrese el id del boton",
     *         in="path",
     *         name="intIdBoton",
     *        example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      *     @OA\Parameter(
     *         description="ingrese el id del programa",
     *         in="path",
     *         name="intIdProg",
     *        example="120",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     * *     @OA\Parameter(
     *         description="ingrese la descripcion del boton",
     *         in="path",
     *         name="varDescBoto",
     *        example="PRUEBA_DE BOTON",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdBoton",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdProg",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varDescBoto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdBoton": "2","intIdProg":"120","varDescBoto":"PRUEBA_DE BOTON"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizacion Satisfactoria"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="ya existe la descripcion"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function actua_boto(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdBoton' => 'required|max:255',
            'intIdSoft' => 'required|max:255',
            'intIdProg' => 'required|max:255',
            'varDescBoto' => 'required|max:255',
        ];
        $this->validate($request, $regla);

        $intIdBoton = $request->input('intIdBoton');
        $intIdProg = $request->input('intIdProg');
        $varDescBoto = $request->input('varDescBoto');
        $vali_boton = BotonAccion::where('varDescBoto', '=', $varDescBoto)
                ->first(['varDescBoto', 'intIdBoton']);
        // dd($vali_boton['varDescBoto']);

        if (($vali_boton['varDescBoto'] == $varDescBoto) && ($vali_boton['intIdBoton'] == $intIdBoton)) {
            dd("son iguales");
            $actu_boton = BotonAccion::where('intIdBoton', '=', $intIdBoton)
                    ->update([
                'intIdSoft' => $intIdBoton,
                'intIdProg' => $intIdProg,
                'varDescBoto' => $varDescBoto,
            ]);
            $validar['mensaje'] = "Actualizacion Satisfactoria";
        } else {

            $vali_desc = BotonAccion::where('varDescBoto', '=', $varDescBoto)
                    ->first(['varDescBoto']);
            if ($vali_desc['varDescBoto'] == null) {
                $actu_boton = BotonAccion::where('intIdBoton', '=', $intIdBoton)
                        ->update([
                    'intIdProg' => $intIdProg,
                    'varDescBoto' => $varDescBoto,
                ]);
                $validar['mensaje'] = "Actualizacion Satisfactoria";
            } else {
                $validar['mensaje'] = "ya existe la descripcion";
            }
        }

        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/comb_asig_etapa_actu_proy",
     *     tags={"Usuarios Accion"},
     *     summary="combo asignar etapa actual proyecto",
     *     @OA\Parameter(
     *         description="ingrse el codigo del usuario",
     *         in="path",
     *         name="varCodiUsua",
     *        example="usuario_usuario",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      *     @OA\Parameter(
     *         description="ingrese el id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *        example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     * *     @OA\Parameter(
     *         description="ingrese el id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiUsua": "usuario_usuario","intIdProy":"126","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El Documento de identidad ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function comb_asig_etapa_actu_proy(Request $request) {
        $regla = [
            'varCodiUsua' => 'required|ma   x:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdUsua = $request->input('varCodiUsua');
        $intIdProy = $request->input('intIdProy');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $cod_usuario = Usuario::where('varCodiUsua', '=', $intIdUsua)->first(['intIdUsua']);

        if ($request->intIdProy === "-1") {
            $Asignar_proy_asign_asua = DB::select("select distinct agrupador.varCodiAgru,usuario_etapa.intIdEtapa, etapa.varDescEtap from usuario_etapa 
                                                inner join etapa on usuario_etapa.intIdEtapa=etapa.intIdEtapa
                                                inner join asig_etap_proy on asig_etap_proy.intIdEtapa=etapa.intIdEtapa
                                                inner join tipoetapa on tipoetapa.intIdTipoEtap=etapa.intIdTipoEtap
                                                inner join agrupador on agrupador.intIdAgru=tipoetapa.intIdAgru
                                                where usuario_etapa.varCodiUsua='$request->varCodiUsua' and asig_etap_proy.intOrden<>'' and asig_etap_proy.intIdTipoProducto=$intIdTipoProducto");
            return $this->successResponse($Asignar_proy_asign_asua);
        } else {
            $Asignar_proy_asign_asua = Etapa::join('usuario_etapa', 'usuario_etapa.intIdEtapa', '=', 'etapa.intIdEtapa')
                            ->join('asig_etap_proy', 'asig_etap_proy.intIdEtapa', '=', 'etapa.intIdEtapa')
                            ->join('tipoetapa', 'tipoetapa.intIdTipoEtap', '=', 'etapa.intIdTipoEtap')
                            ->join('agrupador', 'agrupador.intIdAgru', '=', 'tipoetapa.intIdAgru')
                            ->where('usuario_etapa.intIdUsua', '=', $cod_usuario['intIdUsua'])
                            ->where('asig_etap_proy.intIdProy', '=', $intIdProy)
                            ->where('asig_etap_proy.intIdTipoProducto', '=', $intIdTipoProducto)
                            ->where('asig_etap_proy.intOrden', '<>', '')
                            ->select('usuario_etapa.intIdUsua', 'tipoetapa.varCodiTipoEtap', 'usuario_etapa.intIdEtapa', 'etapa.varDescEtap', 'asig_etap_proy.intIdAsigEtapProy', 'etapa.boolMostMaqu', 'etapa.boolMostSupe', 'etapa.boolMostCont', 'etapa.boolDesp', 'asig_etap_proy.intOrden', 'agrupador.varCodiAgru')
                            ->orderBy('asig_etap_proy.intOrden', 'ASC')->get();
            return $this->successResponse($Asignar_proy_asign_asua);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/elim_asig_boto_todo",
     *      tags={"Usuarios Accion"},
     *     summary="Eliminar todos las asignaciones de los botones de un programa",
     *     @OA\Parameter(
     *         description="Ingrese el codigo del usuario",
     *         in="path",
     *         name="varCodiUsua",
     *      example="usuario_usuario",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el idPrograma",
     *         in="path",
     *         name="intIdProg",
     *      example="1000",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     * 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ) ,
     * @OA\Property(
     *                     property="intIdProg",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiUsua": "usuario_usuario","intIdProg":"1000"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha eliminado"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function elim_asig_boto_todo(Request $request) {

        $validar = array('mensaje' => '');
        $regla = [
            'varCodiUsua' => 'required|max:255',
            'intIdProg' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $varCodiUsua = $request->input('varCodiUsua');
        $intIdProg = $request->input('intIdProg');

        $obtener = Usuario::where('varCodiUsua', '=', $varCodiUsua)->first(['intIdUsua']);
        $idusuario = $obtener['intIdUsua'];

        $elimi_todo_asig_boto = UsuarioAccion::where('intIdUsua', '=', $idusuario)
                        ->where('intIdProg', '=', $intIdProg)->delete();

        $mensaje = [
            'mensaje' => 'Se ha eliminado'
        ];

        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/obte_boto_medi_modu_prog",
     *      tags={"Usuarios Accion"},
     *     summary="Obtener los botones media el modulo de programa",
     *     @OA\Parameter(
     *         description="Ingrese el id del software",
     *         in="path",
     *         name="intIdSoft",
     *      example="20",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el idPrograma",
     *         in="path",
     *         name="intIdProg",
     *      example="1000",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     * 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdSoft",
     *                     type="string"
     *                 ) ,
     * @OA\Property(
     *                     property="intIdProg",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdSoft": "20","intIdProg":"1000"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="recbe una lista del los botones dependiendo al modulo y programa"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function obte_boto_medi_modu_prog(Request $request) {
        $regla = [
            'intIdSoft' => 'required|max:255',
            'intIdProg' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $intIdSoft = $request->input('intIdSoft');
        $intIdProg = $request->input('intIdProg');

        $obtn_botones = BotonAccion::where('intIdSoft', '=', $intIdSoft)
                        ->where('intIdProg', '=', $intIdProg)
                        ->select('intIdBoton', 'varDescBoto')->get();

        return $this->successResponse($obtn_botones);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/check_permiso_boto",
     *      tags={"Usuarios Accion"},
     *     summary="Obtener los botones media el modulo de programa",
     *     @OA\Parameter(
     *         description="Ingrese el id del software",
     *         in="path",
     *         name="varCodiUsua",
     *      example="usuarios_usuario",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el idPrograma",
     *         in="path",
     *         name="intIdProg",
     *      example="1000",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     * 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ) ,
     * @OA\Property(
     *                     property="intIdProg",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiUsua": "usuario_usuarios","intIdProg":"1000"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="recbe una lista del los botones dependiendo al modulo y programa"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function check_permiso_boto(Request $request) {
        $regla = [
            'varCodiUsua' => 'required|max:255',
            'intIdProg' => 'required|max:255',
        ];
        $this->validate($request, $regla);

        $varCodiUsua = $request->input('varCodiUsua');
        $intIdProg = (int) $request->input('intIdProg');


        $obtener = Usuario::where('varCodiUsua', '=', $varCodiUsua)->first(['intIdUsua']);
        $idusuario = $obtener['intIdUsua'];

        ///dd($obtener['intIdUsua']);

        $list_botones = UsuarioAccion::join('boton_accion', 'usuario_accion.intIdBoton', '=', 'boton_accion.intIdBoton')
                        ->where('usuario_accion.intIdUsua', '=', $idusuario)
                        ->where('boton_accion.intIdProg', '=', $intIdProg)
                        ->select('usuario_accion.intIdUsua', 'usuario_accion.intIdBoton', 'boton_accion.varDescBoto')->get();

        return $this->successResponse($list_botones);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/regi_check_usua_boto",
     *         tags={"Usuarios Accion"},
     *     summary="registra los check del usua botono",
     *     @OA\Parameter(
     *         description="Ingrese el codigo del usuario.",
     *         in="path",
     *         name="varCodiUsua",
     * example="codigo_usuario",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="documento de intIdProg",
     *         in="path",
     *         name="intIdProg",
     *       example="1000",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),       
     *  @OA\Parameter(
     *         description="documento de intIdBoton",
     *         in="path",
     *         name="intIdBoton",
      example="1000",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
      @OA\Parameter(
     *         description="documento de acti_usua",
     *         in="path",
     *         name="acti_usua",
     *      example="usuario_logueado",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ) ,
     *            @OA\Property(
     *                     property="intIdProg",
     *                     type="string"
     *                 ) ,
     *            @OA\Property(
     *                     property="intIdBoton",
     *                     type="string"
     *                 ) ,
     *            @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiUsua":"codigo_usuario","intIdProg":"1000",
     *                       "intIdBoton":"1000","acti_usua":"usuario_logueado"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_check_usua_boto(Request $request) {

        $regla = [
            'varCodiUsua' => 'required|max:255',
            'intIdProg' => 'required|max:255',
            'intIdBoton' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $varCodiUsua = $request->input('varCodiUsua');
        $intIdProg = $request->input('intIdProg');
        $intIdBoton = $request->input('intIdBoton');
        $acti_usua = $request->input('acti_usua');

        $Obtener_id = Usuario::where('varCodiUsua', '=', $varCodiUsua)->first(['intIdUsua']);
        $idusuario = $Obtener_id['intIdUsua'];

        $usua_dele = UsuarioAccion::where('IntIdUsua', '=', $idusuario)
                        ->where('intIdProg', '=', $intIdProg)->delete();

        date_default_timezone_set('America/Lima'); // CDT


        for ($i = 0; $i < count($intIdBoton); $i++) {
            $registrar_usuario_accion = UsuarioAccion::create([
                        'intIdUsua' => $idusuario,
                        'varCodiUsua' => $varCodiUsua,
                        'intIdProg' => $intIdProg,
                        'intIdBoton' => $intIdBoton[$i],
                        'acti_usua' => $acti_usua,
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
        }

        $mensaje = [
            'mensaje' => 'Registro Satisfactorio.'
        ];

        return $this->successResponse($mensaje);
    }

}
