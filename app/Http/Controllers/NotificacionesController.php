<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\UsuarioToken;
use App\Software;
use App\Notificacion;
use App\DetalleNotificacion;
use App\Programa;
use App\UsuarioPrograma;
use App\UsuarioEtapa;
use App\Tags;
use App\DetalleTags;
use DB;
use App\UsuarioProyecto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificacionesController extends Controller {

    use \App\Traits\ApiResponser;

    public function __construct() {
        //
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/noti_usua",
     *     tags={"Notificaciones"},
     *     summary="Muestra las notificaciones solamente los 5 dias ultimos",
     *     @OA\Parameter(
     *         description="Codigo del Usuario",
     *         in="path",
     *         name="codi_usua",
     *      example="antony_rodriguez",
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
     *                 example={"codi_usua": "antony_rodriguez"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra las notificaciones solamente los 5 dias ultimos"
     *     ),  
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function noti_usua(Request $request) {
        $regla = [
            'codi_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $codi_usua = $request->input('codi_usua');

        date_default_timezone_set('America/Lima');
        $fechaactual = "";
        $fechaactual = date("Y-m-d");
        $fecha_ante_3_dia = "";


        $fecha_ante_3_dia = date("Y-m-d", strtotime($fechaactual . "-1 month"));

      //  dd($fecha_ante_3_dia);
        $notificacion_usuario = DetalleNotificacion::leftJoin('noti_usua', 'deta_noti.intIdNoti', '=', 'noti_usua.intIdNoti')
                        ->where('deta_noti.dateNoti', '>', $fecha_ante_3_dia)
                        ->where('deta_noti.varUsuaNoti', '=', $codi_usua)
                        ->select('noti_usua.intIdNoti', 'noti_usua.modu_prog', 'noti_usua.asun_noti', 'noti_usua.varDescNoti', 'noti_usua.ruta_prog', 'noti_usua.varNombarch', 'deta_noti.intIdEsta', 'noti_usua.acti_usua', 'deta_noti.acti_hora','noti_usua.varDetaArch')
                        ->orderBy('deta_noti.intIdEsta', 'ASC')
                        ->orderBy('noti_usua.intIdNoti', 'DESC')->get();

        return $this->successResponse($notificacion_usuario);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/most_info_noti",
     *     tags={"Notificaciones"},
     *     summary="Muestra la informacion de la notificacion",
     *     @OA\Parameter(
     *         description="Ingrese el id de la notificacion",
     *         in="path",
     *         name="intIdNoti",
     *      example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el codigo del usuario",
     *         in="path",
     *         name="codi_usua",
     *      example="antony_rodriguez",
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
     *                     property="intIdNoti",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="codi_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdNoti": "1","codi_usua":"antony_rodriguez"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra la informacion de la notificacion y cambia el estado a leido"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function most_info_noti(Request $request) {
        $regla = [
            'intIdNoti' => 'required|max:255',
            'codi_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $intIdNoti = $request->input('intIdNoti');
        $codi_usua = $request->input('codi_usua');
        date_default_timezone_set('America/Lima'); // CDT


        $detalle_notificacion = DetalleNotificacion::where('intIdNoti', '=', $intIdNoti)
                        ->where('varUsuaNoti', '=', $codi_usua)
                        ->select('intIdNoti', 'varUsuaNoti', 'intIdEsta')->get();

        if ($detalle_notificacion[0]['intIdEsta'] === 3) {

            DetalleNotificacion::where('intIdNoti', '=', $intIdNoti)
                    ->where('varUsuaNoti', '=', $codi_usua)
                    ->update([
                        'intIdEsta' => 14,
                        'hora_leid' => $current_date = date('Y/m/d H:i:s')
            ]);
        }

        $infor_notificacion = Notificacion::join('usuario', 'noti_usua.acti_usua', '=', 'usuario.varCodiUsua')
                        ->leftJoin('deta_noti', 'deta_noti.intIdNoti', '=', 'noti_usua.intIdNoti')
                        ->where('deta_noti.intIdNoti', '=', $intIdNoti)
                        ->where('deta_noti.varUsuaNoti', '=', $codi_usua)
                        ->select('noti_usua.intIdNoti', DB::raw("CONCAT(usuario.varNombUsua, ' ',usuario.varApelUsua) as nombre"), 'noti_usua.modu_prog', 'noti_usua.asun_noti', 'noti_usua.ruta_prog', 'noti_usua.varDescNoti', 'deta_noti.intIdEsta',
                                // 'noti_usua.intIdEsta', 
                                'noti_usua.acti_usua', 'noti_usua.acti_hora', 'noti_usua.varNombarch','noti_usua.varDetaArch')->get();



        return $this->successResponse($infor_notificacion);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/traer_tags_usua_publi",
     *     tags={"Notificaciones"},
     *     summary="Traer el tags usua publico",
     *     @OA\Parameter(
     *         description="Ingrese el usuario",
     *         in="path",
     *         name="varPropTags",
     *      example="jose_castillo",
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
     *                     property="varPropTags",
     *                     type="string"
     *                 ) ,
     *                 example={"varPropTags": "jose_castillo"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra los tags que tiene ese usuario tanto privado como publico"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function traer_tags_usua_publi(Request $request) {
        $validar = [];
        $regla = [
            'varPropTags' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $varPropTags = $request->input('varPropTags');


        $desc_tag_publico = Tags::where('varPublTags', '=', 'SI')
                ->select('intIdTags', 'varDescTags')
                ->get();
        // dd(json_decode($desc_tag_publico));

        $obtener_tag_usua = Tags::where('varPublTags', '!=', 'SI')
                        ->where('varPropTags', '=', $varPropTags)
                        ->select('intIdTags', 'varDescTags')->get();


        $resultado = array_merge(json_decode($desc_tag_publico), json_decode($obtener_tag_usua));

        sort($resultado);



        return $this->successResponse($resultado);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/muest_integrante_tags",
     *     tags={"Notificaciones"},
     *     summary="Muestrar los integrante del taps",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="intIdTags",
     *      example="2",
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
     *                     property="intIdTags",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdTags": "2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestrar el nombre completo y usuario mediante el indItTag"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function muest_integrante_tags(Request $request) {
        $regla = [
            'intIdTags' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTags = $request->input('intIdTags');


        $traer_integrantes_con_tag = DetalleTags::join('usuario', 'deta_tags.codi_usua', '=', 'usuario.varCodiUsua')
                        ->where('deta_tags.intIdTags', '=', $intIdTags)
                        ->select('usuario.varCodiUsua', DB::raw("CONCAT(usuario.varNombUsua, ' ',usuario.varApelUsua) as nombre"))->get();

        return $this->successResponse($traer_integrantes_con_tag);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/regis_noti_deta_noti",
     *     tags={"Notificaciones"},
     *     summary="registrar la notificacion",
     *     @OA\Parameter(
     *         description="Ingrese el Asunto de la notificacion",
     *         in="path",
     *         name="asun_noti",
     *         example="Modificar_andy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese ingrese le modulo de la intefaz",
     *         in="path",
     *         name="modu_prog",
     *         example="PRUEBA",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *         @OA\Parameter(
     *         description="Ingrese la descripcion de la notificacion",
     *         in="path",
     *         name="varDescNoti",
     *         example="Prueba_Andy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *       @OA\Parameter(
     *         description="Ingrese el codigo del usuario que va realizar la notificacion",
     *         in="path",
     *         name="acti_usua",
     *         example="usuario_usuario",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   
     *     @OA\Parameter(
     *         description="Ingrese el id del tags",
     *         in="path",
     *         name="intIdTags",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese los usuarios",
     *         in="path",
     *         name="Personal",
     *         example="usuario_ancajima,usuario_timana,jose_castillo,antony_rodriguez,usuario_edimir",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),        
     * @OA\Parameter(
     *         description="Ingrese los usuarios",
     *         in="path",
     *         name="ruta_prog",
     *         example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
     * @OA\Parameter(
     *         description="Ingrese el nombre del archivo",
     *         in="path",
     *         name="varNombarch",
     *         example="",
     *         required=false,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
     *
     * 
     * 
     * 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="asun_noti",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="modu_prog",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varDescNoti",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdTags",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="Personal",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="ruta_prog",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varNombarch",
     *                     type="string"
     *                 ) ,
     *                 example={"asun_noti": "Modificar_andy","modu_prog":"PRUEBA","varDescNoti":"Prueba_Andy",
     *                          "acti_usua":"andy_ancajima","ruta_prog":"prueba_snud","varNombarch":"jisus","intIdTags":"1",
     *                          "Personal":"usuario_ancajima,usuario_timana,jose_castillo,antony_rodriguez,usuario_edimir"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regis_noti_deta_noti() {
        $TIPO_CARGA = $_POST['tag_lista_noti'];
        dd($TIPO_CARGA);
        $validar = array('mensaje' => '');
        $regla = [
            'asun_noti' => 'required|max:255',
            'modu_prog' => 'required|max:255',
            'varDescNoti' => 'required|max:255',
            //dateNoti 
            //'intIdEsta'
            'acti_usua' => 'required|max:255',
                //'ruta_prog'=>'required|max:255' no es obligatorio
                //'varNombarch'=>'required|max:255' no es obligatorio
                //'intIdTags' => 'required|max:255', // PARA LOS GRUPOS
                //'Personal' => 'required', // lista de usuarios
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        //$current_date = date('Y/m/d H:i:s')
        $asun_noti = $request->input('asun_noti');
        $modu_prog = $request->input('modu_prog');
        $varDescNoti = $request->input('varDescNoti');
        $acti_usua = $request->input('acti_usua');
        $ruta_prog = $request->input('ruta_prog');
        $varNombarch = $request->input('varNombarch');
        $intIdTags = trim($request->input('intIdTags'), ',');
        $Personal = trim($request->input('Personal'), ',');
        $varDetaArch= $request->input('varDetaArch');
        $regis_notificacion = Notificacion::create([
                    'asun_noti' => $asun_noti,
                    'modu_prog' => $modu_prog,
                    'varDescNoti' => $varDescNoti,
                    'dateNoti' => $current_date = date('Y/m/d H:i:s'),
                    'intIdEsta' => 3,
                    'acti_usua' => $acti_usua,
                    'acti_hora' => $current_date = date('Y/m/d H:i:s'),
                    'ruta_prog' => $ruta_prog,
                    'varNombarch' => $varNombarch,
                    'varDetaArch'=>$varDetaArch
        ]);
        //OBTENEMOS DATOS DE TAGS
        $obtener_publi = Tags::where('varDescTags', '=', $intIdTags)
                        ->select('intIdTags', 'varDescTags', 'varPublTags')->first();
        if ($obtener_publi['varPublTags'] == "NO") {
             $obtener = Tags::where('varDescTags', '=', $intIdTags)
                        ->where('acti_usua', '=', $acti_usua)
                        ->select('intIdTags', 'varDescTags', 'varPublTags')->first();
            $traer_tags = DetalleTags::where('intIdTags', '=', $obtener['intIdTags'])
                            ->where('acti_usua', '=', $acti_usua)
                            ->select('codi_usua')->get();
        } else {
            $traer_tags = DetalleTags::where('intIdTags', '=', $obtener_publi['intIdTags'])
                            ->select('codi_usua')->get();
        }
        //TRAER EL TAGS 
        $codi = []; //PARA EL PRIMER FOREACH PARA ACUMULAR CODIGO_USUARIOS DE DETALLE DEL TAGS
        foreach ($traer_tags as $valor) {
            array_push($codi, $valor['codi_usua']);
        }
        if ($Personal === "") {
            $VALORES_UNICOS = array_unique($codi); // VALORES UNICOS DENTRO DEL ARRAY
            //REGISTRAMOS EL DETALLE DE NOTIFICACION 
            //COLOCAMOS EL ID DE LA NUEVA NOTIFICACION + LOS USUARIO QUE SE HAN ENCONTRADO EN EL DETALLE DEL TAGS
        } else {
            $porciones = explode(",", $Personal);
            $resultado = array_merge($codi, $porciones); // UNIR LOS DOS ARRAYS
            $VALORES_UNICOS = array_unique($resultado); // VALORES UNICOS DENTRO DEL ARRAY
        }
        foreach ($VALORES_UNICOS as $index) {
            DetalleNotificacion::create([
                'intIdNoti' => $regis_notificacion['intIdNoti'],
                'varUsuaNoti' => $index,
                'dateNoti' => $current_date = date('Y/m/d H:i:s'),
                'intIdEsta' => 3,
                'acti_usua' => $acti_usua,
                'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
        }
        return $this->successResponse($validar);
    }

    
    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/modi_tags",
     *     tags={"Notificaciones"},
     *     summary="Modificar el tags",
     *     @OA\Parameter(
     *         description="Ingrese el id del tags",
     *         in="path",
     *         name="intIdTags",
     *      example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese los usuarios ",
     *         in="path",
     *         name="codi_usua",
     *      example="edimir_ancajima",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 

      @OA\Parameter(
     *         description="Ingrese el usuario que ha modificado",
     *         in="path",
     *         name="acti_usua",
     *      example="usuario_usuario",
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
     *         description="se cuando se modifica los campos se le envia la respuesta: '' "
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function modi_tags(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdTags' => 'required|max:255',
            'codi_usua' => 'required', //JSON
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $intIdTags = $request->input('intIdTags');
        $codi_usua = json_decode($request->input('codi_usua'));
        $acti_usua = $request->input('acti_usua');


        //obtenemos el intIdTags
        $Obtener_descrip = Tags::where('intIdTags', '=', $intIdTags)
                                ->where('varPublTags','=','SI')
                                ->select('varDescTags')->first();
        
        
       
        if (isset($Obtener_descrip)) {
           
                $validar['mensaje']="NO SE PUEDE EDITAR YA QUE ES PUBLICO";
        } else {
            //ELIMINAR EL DETALLE DEL TAGS MEDIANTE EL ID DE LA NOTIFICACION (intIdTags)
               
            DetalleTags::where('intIdTags', '=', $intIdTags)->delete();
            
          

            foreach ($codi_usua as $index) {
                DetalleTags::create([
                    'intIdTags' => $intIdTags,
                    'codi_usua' => $index->Id,
                    'acti_usua' => $acti_usua,
                    'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
            }
        }


        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/regis_tags",
     *     tags={"Notificaciones"},
     *     summary="Permite registrar el tags",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="varDescTags",
     *      example="PRUEBA",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingresamos todo los usuarios que se ha  asignar en el grupó",
     *         in="path",
     *         name="codi_usua",
     *      example="usuario_usuario",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    
      @OA\Parameter(
     *         description="Ingresamos al usuario que va registrar el nuevo grupo",
     *         in="path",
     *         name="acti_usua",
     *      example="usuario_prueba",
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
     *                     property="varDescTags",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="codi_usua",
     *                     type="string"
     *                 ) ,
     *             @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"varDescTags": "PRUEBA","codi_usua":"usuario_usuario","acti_usua":"usuario_prueba"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Sactifactorio"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regis_tags(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'varDescTags' => 'required|max:255',
            // 'varPropTags'=>'required|max:255', EL MISMO VALOR ES PARA acti_usua
            'codi_usua' => 'required', //JSON
            'acti_usua' => 'required|max:255'
        ];

        $this->validate($request, $regla);

        $varDescTags = strtoupper($request->input('varDescTags'));
        //$varPropTags=$request->input('varPropTags');
        $codi_usua = json_decode($request->input('codi_usua'));
        $acti_usua = $request->input('acti_usua');

        date_default_timezone_set('America/Lima'); // CDT

        
      
            $vali_descrip_tags = Tags::where('varDescTags', '=', $varDescTags)
                            ->where('varPropTags', '=', $acti_usua)
                            ->select('intIdTags')->first();
                        
            //VALIDAR SI LA DESCRIPCION EXISTE 
            if ($vali_descrip_tags['intIdTags'] == null || $vali_descrip_tags['intIdTags'] == "") {
                //REGISTRO DEL TAGS EN LA TABLA tags_noti

                $create_tags = Tags::create([
                            'varDescTags' => $varDescTags,
                            'varPropTags' => $acti_usua,
                            'acti_usua' => $acti_usua,
                            'acti_hora' => $current_date = date('Y/m/d H:i:s'),
                            'varPublTags' => 'NO'
                ]);


                foreach ($codi_usua as $index) {
                    DetalleTags::create([
                        'intIdTags' => $create_tags['intIdTags'],
                        'codi_usua' => $index->Id,
                        'acti_usua' => $acti_usua,
                        'acti_hora' => $current_date = date('Y/m/d H:i:s'),
                    ]);
                }
            } else {
                $validar['mensaje'] = "YA EXISTE LA DESCRIPCION: " . $varDescTags;
            }
        
        return $this->successResponse($validar);
    }

    /**
     * @OA\Get(
     *     path="/GestionUsuarios/public/index.php/list_usua_noti",
     *       tags={"Notificaciones"},
     *     summary="lista los usuarios para las notificaciones",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista los usuarios para las notificaciones."
     *     )
     * )
     */
    public function list_usua_noti(){
        $list_usuario = Usuario::select('varCodiUsua', DB::raw("CONCAT(usuario.varNombUsua, ' ',usuario.varApelUsua) as nombre"))->get();
        return $this->successResponse($list_usuario);
    }

}
