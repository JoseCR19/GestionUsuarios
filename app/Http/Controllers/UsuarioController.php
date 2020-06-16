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

class UsuarioController extends Controller {

    use \App\Traits\ApiResponser;

    public function __construct() {
        //
    }

    /**
     * @OA\Info(title="Gestion Usuario", version="1",
     * @OA\Contact(
     *     email="antony.rodriguez@mimco.com.pe"
     *   )
     * )
     */

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/regi_usua",
     *     tags={"Registrar usuario"},
     *     summary="permite registro de usuarios",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="varNumeDni",
     *         required=true,
     *         example= "11111111",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Nombre",
     *         in="path",
     *         name="varNombUsua",
     *         required=true,
     *         example= "Andy",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Apellido",
     *         in="path",
     *         name="varApelUsua",
     *         required=true,
     *         example= "Ancajima",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Codigo usuario",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "andy_cjam",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Clave",
     *         in="path",
     *         name="varClavUsua",
     *         required=true,
     *         example= "11111",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="correo institucional",
     *         in="path",
     *         name="varCorrUsua",
     *         required=true,
     *         example= "andy@mimco.com.pe",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="telefono personal",
     *         in="path",
     *         name="varTelfUsua",
     *         required=true,
     *         example= "12141214",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="estado",
     *         in="path",
     *         name="varEstaUsua",
     *         required=true,
     *         example= "ACT",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="usuario quien registra",
     *         in="path",
     *         name="codi_usua",
     *         required=true,
     *         example= "usuario",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                  
     *                 example={"varNumeDni": "25214121", "varNombUsua": "Andy", "varApelUsua": "Bladeu", "varCodiUsua": "anca_sca", "varClavUsua": "521412", "varCorrUsua": "andy@mimco.com.pe", "varTelfUsua": "2521412", "varEstaUsua": "ACT", "codi_usua": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Registro Satisfactorio"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="DNI ya se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     ),
     *     @OA\Response(
     *         response=408,
     *         description="Codigo de usuario ya se encuentra registrado."
     *     ),
     * )
     */
    public function regi_usua(Request $request) {



        $regla = [
            'varNumeDni' => 'required|max:255',
            'varNombUsua' => 'required|max:255',
            'varApelUsua' => 'required|max:255',
            'varCodiUsua' => 'required|max:255',
            'varClavUsua' => 'required|max:255',
            'varCorrUsua' => 'required|max:255',
            'varTelfUsua' => 'required|max:255',
            'varEstaUsua' => 'required|max:255',
            'codi_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        //713077008
        $rand_stri = $this->GenerateRandomCaracter();

        $usua_condi = Usuario::where('varNumeDni', '=', $request->input('varNumeDni'))->first(['varNumeDni', 'varNombUsua', 'varApelUsua']);
        if ($usua_condi != null) {
            $mensaje = [
                'mensaje' => 'DNI ya se encuentra registrado.'
            ];
            return $this->successResponse($mensaje);
        } else {
            $usua_codi = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varNumeDni', 'varNombUsua', 'varApelUsua', 'varCodiUsua']);
            if (($usua_codi['varCodiUsua']) == ($request->input('varCodiUsua'))) {
                $mensaje = [
                    'mensaje' => 'Codigo de usuario ya se encuentra registrado.'
                ];
                return $this->successResponse($mensaje);
            } else {

                $usua = UsuarioToken::create([
                            'user_id' => $request->input('varNumeDni'),
                            'name' => $request->input('varCodiUsua'),
                            'secret' => $rand_stri,
                            'redirect' => '',
                            'personal_access_client' => 1,
                            'password_client' => 0,
                            'revoked' => 0
                ]);
                $usua_token = UsuarioToken::where('user_id', $request->input('varNumeDni'))->first(['id']);
                //registramos  al usuario en la base de datos
                $cla_crifr = password_hash($request->input('varClavUsua'), PASSWORD_DEFAULT);
                date_default_timezone_set('America/Lima'); // CDT

                $registrar_usua_db = Usuario::create([
                            'varNumeDni' => $request->input('varNumeDni'),
                            'varNombUsua' => $request->input('varNombUsua'),
                            'varApelUsua' => $request->input('varApelUsua'),
                            'varCodiUsua' => $request->input('varCodiUsua'),
                            //'varClavUsua'=>$request->input('varClavUsua'),
                            'varClavUsua' => $cla_crifr,
                            'varEstaUsua' => $request->input('varEstaUsua'),
                            'varCorrUsua' => $request->input('varCorrUsua'),
                            'varTelfUsua' => $request->input('varTelfUsua'),
                            'varCodiUsua' => $request->input('varCodiUsua'),
                            'acti_usua' => $request->input('codi_usua'),
                            'acti_hora' => $current_date = date('Y/m/d H:i:s'),
                            'intIdUsuaToke' => $usua_token['id'],
                            'varSecrUsua' => $rand_stri
                ]);
                $template = file_get_contents(__DIR__ . '/bienvenida.tpl');

                $url = "http://www.mimco.com.pe/home/";
                // $url= $usuario_codigo['varClavUsua'];

                $btn_cred = "<a href ='$url' style='background: #374960 ;color: #ffffff ;font-size: 20px;border-radius:50px'>Intranet Mimco</a>";
                //  die($btn_cred);
                $nomb_comp = $request->input('varNombUsua') . ' ' . $request->input('varApelUsua');
                $codi_usua = $request->input('varCodiUsua');
                $pass_usua = $request->input('varClavUsua');
                $corr_usua = $request->input('varCorrUsua');

                $template = str_replace(
                        array("<!-- #{Nombre} -->", "<!-- #{boton} -->", "<!-- #{codi} -->", "<!-- #{pass} -->"), array(ucwords(strtolower($nomb_comp)), $btn_cred, $codi_usua, $pass_usua), $template);
                $asun_mens = "Acceso al intranet Mimco";
                $mail = new \PHPMailer\PHPMailer\PHPMailer();
                $mail->CharSet = 'utf-8';
                $mail->SMTPAuth = true; // habilitamos la autenticaci贸n SMTP
                $mail->MsgHTML($template);
                $mail->From = 'noresponder@mimco.com.pe';
                $mail->FromName = 'NO RESPONDER';
                $mail->IsHTML(true);
                $mail->Subject = $asun_mens;
                $mail->AddAddress($corr_usua);
                $mail->Send();
                /* if (!$mail->Send()) { // visualizar en consola

                  echo "error".($mail->ErrorInfo);
                  } else {
                  echo 'ok';
                  }

                 */



                $mensaje = [
                    'mensaje' => 'Registro satisfactorio.'
                ];
                return $this->successResponse($mensaje);
            }
        }
    }

    function GenerateRandomCaracter($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/validar_usuario",
     *     tags={"Obtener usuario"},
     *     summary="obtiene datos del usuario a través del dni",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="varNumeDni",
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
    public function validar_usuario(Request $request) {
        $regla = [
            'varNumeDni' => 'required|max:13',
        ];
        $this->validate($request, $regla);


        $usuario = Usuario::where('varNumeDni', '=', $request->input('varNumeDni'))->first(['varNumeDni', 'varNombUsua', 'varApelUsua', 'varCodiUsua', 'varClavUsua', 'varCorrUsua', 'varTelfUsua', 'varEstaUsua']);

        if ($usuario == null) {
            $mensaje = [
                'mensaje' => 'El Documento de identidad ingresado no se encuentra registrado.'
            ];
            return $this->successResponse($mensaje);
        } else {
            return $this->successResponse($usuario);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/actu_usua",
     *     tags={"Actualizacion usuario"},
     *     summary="permite actulizar usuarios",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="varNumeDni",
     *         required=true,
     *         example= "11111111",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Nombre",
     *         in="path",
     *         name="varNombUsua",
     *         required=true,
     *         example= "Andy",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Apellido",
     *         in="path",
     *         name="varApelUsua",
     *         required=true,
     *         example= "Ancajima",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Codigo usuario",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "andy_cjam",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Clave",
     *         in="path",
     *         name="varClavUsua",
     *         required=true,
     *         example= "11111",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="correo institucional",
     *         in="path",
     *         name="varCorrUsua",
     *         required=true,
     *         example= "andy@mimco.com.pe",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="telefono personal",
     *         in="path",
     *         name="varTelfUsua",
     *         required=true,
     *         example= "12141214",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="estado",
     *         in="path",
     *         name="varEstaUsua",
     *         required=true,
     *         example= "ACT",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="usuario quien actualiza",
     *         in="path",
     *         name="codi_usua",
     *         required=true,
     *         example= "usuario",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                  
     *                 example={"varNumeDni": "25214121", "varNombUsua": "Andy", "varApelUsua": "Bladeu", "varCodiUsua": "anca_sca", "varClavUsua": "521412", "varCorrUsua": "andy@mimco.com.pe", "varTelfUsua": "2521412", "varEstaUsua": "ACT", "codi_usua": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Se ha actualizado Correctamente."
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     *      
     * )
     */
    public function actu_usua(Request $request) {

        $regla = [
            'varNumeDni' => 'required|max:255',
            'varNombUsua' => 'required|max:255',
            'varApelUsua' => 'required|max:255',
            'varCodiUsua' => 'required|max:255',
            'varClavUsua' => 'required|max:255',
            'varCorrUsua' => 'required|max:255',
            'varTelfUsua' => 'required|max:255',
            'varEstaUsua' => 'required|max:255',
            'codi_usua' => 'required|max:255',
        ];



        $this->validate($request, $regla);



        date_default_timezone_set('America/Lima'); // CDT



        $usua_cond = Usuario::where('varNumeDni', $request->input('varNumeDni'))->first(['varClavUsua']);

        if (($usua_cond['varClavUsua']) == ($request->input('varClavUsua'))) {

            $usua = Usuario::where('varNumeDni', $request->input('varNumeDni'))->update([
                'varNombUsua' => $request->input('varNombUsua'),
                'varApelUsua' => $request->input('varApelUsua'),
                // 'varClavUsua'=>$cla_crifr,
                'varCorrUsua' => $request->input('varCorrUsua'),
                'varTelfUsua' => $request->input('varTelfUsua'),
                'varEstaUsua' => $request->input('varEstaUsua'),
                'usua_modi' => $request->input('codi_usua'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);

            $mensaje = [
                'mensaje' => 'Se ha actualizado Correctamente.'
            ];

            return $this->successResponse($mensaje);
        } else {

            $clav_cifr = password_hash($request->input('varClavUsua'), PASSWORD_DEFAULT);

            $usua_upda = Usuario::where('varNumeDni', $request->input('varNumeDni'))->update([
                'varNombUsua' => $request->input('varNombUsua'),
                'varApelUsua' => $request->input('varApelUsua'),
                'varClavUsua' => $clav_cifr,
                'varCorrUsua' => $request->input('varCorrUsua'),
                'varTelfUsua' => $request->input('varTelfUsua'),
                'varEstaUsua' => $request->input('varEstaUsua'),
                'usua_modi' => $request->input('codi_usua'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);

            $mensaje = [
                'mensaje' => 'Se ha actualizado Correctamente.'
            ];

            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/elim_usua",
     *     tags={"Eliminar Usuario"},
     *     summary="inactiva un usuario para que no pueda acceder al sistema",
     *     @OA\Parameter(
     *         description="Numero de dni del usuario",
     *         in="path",
     *         name="varNumeDni",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *     @OA\Parameter(
     *         description="codigo de usuario que se usa para ingresar al sistema",
     *         in="path",
     *         name="codi_usua",
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
     *                     property="varNumeDni",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="codi_usua",
     *                     type="string"
     *                 ),
     *                 example={"varNumeDni": "13122014", "codi_usua": "antony_rodriguez"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El usuario ha sido eliminado."
     *     )
     * )
     */
    public function elim_usua(Request $request) {
        $regla = [
            'varNumeDni' => 'required|max:255',
            'codi_usua' => 'required|max:255'
        ];

        $this->validate($request, $regla);

        $usuario = Usuario::where('varNumeDni', $request->input('varNumeDni'))->update([
            'varEstaUsua' => 'INA',
            'usua_modi' => $request->input('codi_usua'),
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);



        $mensaje = [
            'mensaje' => 'El usuario ha sido eliminado.'
        ];
        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Get(
     *     path="/GestionUsuarios/public/index.php/list_usua",
     *     tags={"Listar Usuarios"},
     *     summary="lista los usuarios",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista los usuarios."
     *     )
     * )
     */
    public function list_usua() {



        $usuario = Usuario::get(['intIdUsua', 'varNumeDni', 'varCodiUsua', 'varNombUsua', 'varApelUsua', 'varEstaUsua']);
        return $this->successResponse($usuario);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/elim_usua_prog",
     *     tags={"Eliminar permisos"},
     *     summary="permite eliminar permisos de un usuario especifico",
     *     @OA\Parameter(
     *         description="Colocar el Id del Modulo para eliminar los permisos que el usuario tenga en ese modulo",
     *         in="path",
     *         name="intIdSoft",
     *         required=true,
     *         example= "2",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *     @OA\Parameter(
     *         description="Colocar el codigo usuario de quien se eliminaran los permisos",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "antony_rodasd",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdSoft",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ),
     *                 example={"intIdSoft": "15", "varCodiUsua": "antony_rodsda"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="se ha eliminado los programas."
     *     )
     * )
     */
    public function elim_usua_prog(Request $request) {

        $regla = [
            'intIdSoft' => 'required|max:255',
            'varCodiUsua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varCodiUsua', 'intIdUsua']);
        $usua_dele = UsuarioPrograma::where('IntIdUsua', '=', $sele['intIdUsua'])->where('intIdSoft', $request->input('intIdSoft'))->delete();


        $mensaje = [
            'mensaje' => 'se ha eliminado los programas. '
        ];

        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/regi_usua_prog",
     *     tags={"Asignar permisos"},
     *     summary="permite asignar permisos al usuario",
     *     @OA\Parameter(
     *         description="Colocar el Id del Modulo para asignar permisos al usuario",
     *         in="path",
     *         name="intIdSoft",
     *         required=true,
     *         example= "2",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *     @OA\Parameter(
     *         description="usuario a quien se le registraran permisos",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "antony_rodasd",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="usuario quien realiza el registro",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "antony_rodasd",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Id del programa que sera asignado a un usuario",
     *         in="path",
     *         name="intIdProg",
     *         required=true,
     *         example= "50",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdSoft",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ),
     *                 example={"varCodiUsua": "antony_rodsda", "intIdProg": "50", "intIdSoft": "12", "acti_usua": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Satisfactorio."
     *     )
     * )
     */
    public function regi_usua_prog(Request $request) {

        $regla = [
            // 'IntIdUsua'=>'required|max:255',
            'varCodiUsua' => 'required|max:255',
            'intIdProg' => 'required|max:255',
            'intIdSoft' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        //dd($request->input('intIdProg'));
        $this->validate($request, $regla);
        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varCodiUsua', 'intIdUsua']);

        $usua_dele = UsuarioPrograma::where('IntIdUsua', '=', $sele['intIdUsua'])->where('intIdSoft', $request->input('intIdSoft'))->delete();

        $input = $request->input('intIdProg');

        date_default_timezone_set('America/Lima'); // CDT
        //   $usua_dele=UsuarioPrograma::where('IntIdUsua','=',$sele['intIdUsua'])->where('intIdSoft',$request->input('intIdSoft'))->delete();

        for ($i = 0; $i < count($input); $i++) {
            $usua_prog = UsuarioPrograma::create([
                        'IntIdUsua' => $sele['intIdUsua'],
                        'varCodiUsua' => $request->input('varCodiUsua'),
                        'intIdProg' => $input[$i]['intIdProg'],
                        'intIdSoft' => $request->input('intIdSoft'),
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
        }

        $mensaje = [
            'mensaje' => 'Registro Satisfactorio. '
        ];

        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/obte_perm",
     *     tags={"Obtener permisos"},
     *     summary="permite obtener permisos de un usuario especifico",
     *     @OA\Parameter(
     *         description="Colocar el Id del Modulo que queremos listar",
     *         in="path",
     *         name="intIdSoft",
     *         required=true,
     *         example= "2",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *     @OA\Parameter(
     *         description="Colocar el codigo usuario para mostrar sus permisos",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "antony_rodasd",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdSoft",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ),
     *                 example={"varCodiUsua": "antony_rodsda", "intIdSoft": "15"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="se listan los permisos."
     *     )
     * )
     */
    public function obte_perm(Request $request) {
        $regla = [
            'varCodiUsua' => 'required|max:255',
            'intIdSoft' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        /*
          $prog_list_prog=DB::table('programas')
          ->join('software','programas.intIdSoft','=','software.intIdSoft')
          ->select('programas.intIdProg','programas.varNombProg','software.varNombSoft','programas.varEstaProg','programas.varRutaProg')
          ->get();

         */
        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['IntIdUsua', 'varNombUsua']);

        $usua_obtn = UsuarioPrograma::where('IntIdUsua', '=', $sele['IntIdUsua'])
                        ->where('usuario_programa.intIdSoft', $request->input('intIdSoft'))
                        ->where('programas.varEstaProg', '!=', 'INA')
                        ->join('programas', 'programas.intIdProg', '=', 'usuario_programa.intIdProg')
                        ->select('usuario_programa.varCodiUsua', 'programas.intIdProg', 'programas.varCodiProg', 'programas.varNombProg', 'programas.varRutaProg', 'programas.varIconProg')->get();



        return $this->successResponse($usua_obtn);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/elim_usua_perf",
     *     tags={"Eliminar perfil"},
     *     summary="permite eliminar el perfil de un usuario(elimina los permisos del usuario)",
     *     
     *       @OA\Parameter(
     *         description="Colocar el codigo usuario para eliminar el perfil",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "antony_rodasd",
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
     *                 ),
     *                  
     *                 example={"varCodiUsua": "antony_rodsda"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="se ha eliminado los programas. "
     *     )
     * )
     */
    public function elim_usua_perf(Request $request) {

        $regla = [
            'varCodiUsua' => 'required|max:255' //
        ];
        $this->validate($request, $regla);

        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varCodiUsua', 'intIdUsua']);
        $usua_dele = UsuarioPrograma::where('IntIdUsua', '=', $sele['intIdUsua'])->delete();




        $mensaje = [
            'mensaje' => 'se ha eliminado los programas. '
        ];

        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/copi_perf",
     *     tags={"Copiar Perfil"},
     *     summary="permite copiar perfil de un usuario",
     *     @OA\Parameter(
     *         description="codigo de usuario a quien se le asignara el perfil",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "ramiro_reas",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="Id del usuario a quien se le asignara el perfil",
     *         in="path",
     *         name="IntIdUsua",
     *         required=true,
     *         example= "1",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="usuario a quien se le copiara el perfil",
     *         in="path",
     *         name="varUsuaPerf",
     *         required=true,
     *         example= "antony_rodasd",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="usuario quien esta realizando la copia del perfil",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "antony_rodasd",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdSoft",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ),
     *                 example={"varCodiUsua": "antony_rodsda", "IntIdUsua": "15","varUsuaPerf": "jaime_nuera","acti_usua": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Copia de perfil satisfactoria."
     *     )
     * )
     */
    public function copi_perf(Request $request) {

        $regla = [
            'varCodiUsua' => 'required|max:255',
            'IntIdUsua' => 'required|max:255',
            'varUsuaPerf' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['IntIdUsua', 'varNombUsua']);

        if (isset($sele)) {
            $usua_dele = UsuarioPrograma::where('IntIdUsua', '=', $request->input('IntIdUsua'))->delete();



            $usua_obtn = UsuarioPrograma::where('IntIdUsua', '=', $sele['IntIdUsua'])->get([
                'varCodiUsua', 'intIdProg', 'intIdSoft'
            ]);

            date_default_timezone_set('America/Lima'); // CDT


            for ($i = 0; $i < count($usua_obtn); $i++) {
                $usua_prog = UsuarioPrograma::create([
                            'IntIdUsua' => $request->input('IntIdUsua'),
                            'varCodiUsua' => $request->input('varUsuaPerf'),
                            'intIdProg' => $usua_obtn[$i]['intIdProg'],
                            'intIdSoft' => $usua_obtn[$i]['intIdSoft'],
                            'acti_usua' => $request->input('acti_usua'),
                            'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
            }

            // $list_soft=Software::where('intIdSoft',$request->input('intIdSoft'))->first(['varNombSoft','intIdSoft']);
            //$list_progr=Programa::where('intIdSoft',$list_soft['intIdSoft'])->get(['varNombProg']);
            $mensaje = [
                'mensaje' => 'Copia de perfil satisfactoria.'
            ];
        } else {
            $mensaje = [
                'mensaje' => 'EL USUARIO NO EXISTE.'
            ];
        }



        return $this->successResponse($mensaje);
    }

    /*     * **********************************ASIGNAR ETAPA************************************ */

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/regi_usua_etap",
     *     tags={"Asignar permiso al usuario etapa"},
     *     summary="permite asignar permisos al usuario para las etapas",
     *     @OA\Parameter(
     *         description="Colocar el Id de la etapa",
     *         in="path",
     *         name="intIdEtapa",
     *         required=true,
     *         example= "2",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *     @OA\Parameter(
     *         description="usuario a quien se le registraran permisos",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "antony_rodas",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
     *     @OA\Parameter(
     *         description="ingresar el codigo del usuario que ha dado el permiso de etapa.",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "andy_backlu",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdEtapa",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ),
     *              @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ),
     *                 example={"intIdEtapa": "2", "varCodiUsua": "antony_rodas", "acti_usua": "andy_blacklu"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Satisfactorio."
     *     )
     * )
     */
    public function regi_usua_etap(Request $request) {

        $regla = [
            'intIdEtapa' => 'required|max:255',
            'varCodiUsua' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varCodiUsua', 'intIdUsua']);

        if (isset($sele)) {
            $usua_dele = UsuarioEtapa::where('IntIdUsua', '=', $sele['intIdUsua'])->delete();

            $input = $request->input('intIdEtapa');

            date_default_timezone_set('America/Lima'); // CDT
            //   $usua_dele=UsuarioPrograma::where('IntIdUsua','=',$sele['intIdUsua'])->where('intIdSoft',$request->input('intIdSoft'))->delete();

            for ($i = 0; $i < count($input); $i++) {
                $usua_prog = UsuarioEtapa::create([
                            'intIdUsua' => $sele['intIdUsua'],
                            'intIdEtapa' => $input[$i],
                            'varCodiUsua' => $request->input('varCodiUsua'),
                            'acti_usua' => $request->input('acti_usua'),
                            'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
            }

            $mensaje = [
                'mensaje' => 'Registro Satisfactorio. '
            ];
        } else {
            $mensaje = [
                'mensaje' => 'EL USUARIO NO EXISTE. '
            ];
        }



        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/list_usua_etap",
     *     tags={"Lista la etapas asignadas a un usuario"},
     *     summary="Permite lista la etapas asignadas a un usuario",
     *     @OA\Parameter(
     *         description="Colocar codigo del usuario",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "andy_ancajima",
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
     *                 ),

     *                 example={ "varCodiUsua": "andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra la informacion que se ha asignado a un usuario"
     *     )
     * )
     */
    public function list_usua_etap(Request $request) {

        $regla = [
            'varCodiUsua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $validarUsuario = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))
                ->first(['varCodiUsua']);

        if (isset($validarUsuario)) {
            $list_usua_etapa = UsuarioEtapa::join('etapa', 'etapa.intIdEtapa', '=', 'usuario_etapa.intIdEtapa')
                    ->where('varCodiUsua', $request->input('varCodiUsua'))
                    ->select('usuario_etapa.intIdUsua', 'usuario_etapa.intIdEtapa', 'etapa.varDescEtap', 'usuario_etapa.varCodiUsua')
                    ->get();
            return $this->successResponse($list_usua_etapa);
        } else {
            $mensaje = [
                'mensaje' => 'EL USUARIO NO EXISTE.'
            ];
            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/elim_usua_etap",
     *     tags={"Eliminar las etapas aun usuario "},
     *     summary="Permite eliminar todas las etapas para una usuario",
     *     @OA\Parameter(
     *         description="Colocar codigo del usuario",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "andy_ancajima",
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
     *                 ),

     *                 example={ "varCodiUsua": "andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Eliminacion Satisfactoria"
     *     )
     * )
     */
    public function elim_usua_etap(Request $request) {

        $regla = [
            'varCodiUsua' => 'required|max:255'
        ];
        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['varCodiUsua', 'intIdUsua']);

        $usua_dele = UsuarioEtapa::where('IntIdUsua', '=', $sele['intIdUsua'])->delete();

        $mensaje = [
            'mensaje' => 'Eliminacion Satisfactoria'
        ];

        return $this->successResponse($mensaje);
    }

    /*     * ********************* PROYECTOS******************* */

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/regi_usua_proyecto",
     *     tags={"Asignar permiso al usuario en un proyecto"},
     *     summary="permite asignar permisos al usuario para los proyectos",
     *     @OA\Parameter(
     *         description="Colocar el Id de la etapa",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         example= "126",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *     @OA\Parameter(
     *         description="usuario a quien se le registraran permisos",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "antony_rodas",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
     *     @OA\Parameter(
     *         description="ingresar el codigo del usuario que ha realizado el registro",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "acti_usua",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="varCodiUsua",
     *                     type="string"
     *                 ),
     *              @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ),
     *                 example={"intIdEtapa": "2", "varCodiUsua": "antony_rodas", "acti_usua": "andy_blacklu"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Satisfactorio."
     *     )
     * )
     */
    public function regi_usua_proyecto(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            'varCodiUsua' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $sele = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))->first(['intIdUsua', 'varCodiUsua']);
        //  dd($request->input('intIdProy'));
        $usua_dele = UsuarioProyecto::where('intIdUsua', '=', $sele['intIdUsua'])->delete();

        $input = $request->input('intIdProy');

        //dd($input);
        date_default_timezone_set('America/Lima'); // CDT
        //   $usua_dele=UsuarioPrograma::where('IntIdUsua','=',$sele['intIdUsua'])->where('intIdSoft',$request->input('intIdSoft'))->delete();

        for ($i = 0; $i < count($input); $i++) {
            $usua_prog = UsuarioProyecto::create([
                        'intIdUsua' => $sele['intIdUsua'],
                        'intIdProy' => $input[$i],
                        'varCodiUsua' => $request->input('varCodiUsua'),
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
        }

        $mensaje = [
            'mensaje' => 'Registro Satisfactorio. '
        ];


        return $this->successResponse($mensaje);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/list_usua_proy",
     *     tags={"Listar los proyecto para  usuario"},
     *     summary="Permite lista los proyecto asignadas a un usuario",
     *     @OA\Parameter(
     *         description="Colocar codigo del usuario",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "andy_ancajima",
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
     *                 ),

     *                 example={ "varCodiUsua": "andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra la informacion que se ha asignado a un usuario"
     *     )
     * )
     */
    public function list_usua_proy(Request $request) {

        $regla = [
            'varCodiUsua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $validarUsuario = Usuario::where('varCodiUsua', $request->input('varCodiUsua'))
                ->first(['varCodiUsua']);

        if (isset($validarUsuario)) {
            $list_usua_proyecto = UsuarioProyecto::join('proyecto', 'usuario_proyecto.intIdProy', '=', 'proyecto.intIdProy')
                    ->where('varCodiUsua', $request->input('varCodiUsua'))
                    ->select('usuario_proyecto.intIdUsua', 'usuario_proyecto.intIdProy', 'proyecto.varDescProy')
                    ->get();
        } else {
            $mensaje = [
                'mensaje' => 'EL USUARIO NO EXISTE.'
            ];
            return $this->successResponse($mensaje);
        }




        return $this->successResponse($list_usua_proyecto);
    }

    /**
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/elim_usua_proyec",
     *     tags={"Eliminar todo los proyecto asignado para aun usuario"},
     *     summary="Permite eliminar todos proyectos asignados para a un determinado  usuario",
     *     @OA\Parameter(
     *         description="Colocar codigo del usuario",
     *         in="path",
     *         name="varCodiUsua",
     *         required=true,
     *         example= "andy_ancajima",
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
     *                 ),

     *                 example={ "varCodiUsua": "andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Eliminar usuario del proyecto"
     *     )
     * )
     */
    public function elim_usua_proyec(Request $request) {

        $regla = [
            'varCodiUsua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        
        $varCodiUsua=$request->input('varCodiUsua');
        
         $sele= Usuario::where('varCodiUsua','=',$varCodiUsua)->select('intIdUsua')->first();
         
     
        $usua_dele = UsuarioProyecto::where('intIdUsua', '=', $sele['intIdUsua'])->delete();

        $mensaje = [
            'mensaje' => 'Eliminacion Satisfactoria'
        ];

        return $this->successResponse($mensaje);
    }

    public function edit_foto_perfil(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'varNumeDni' => 'required|max:255',
            'varNombUsua' => 'required|max:255',
            'varApelUsua' => 'required|max:255',
            'varTelfUsua' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $varNumeDni = $request->input('varNumeDni');
        $varNombUsua = $request->input('varNombUsua');
        $varApelUsua = $request->input('varApelUsua');
        $varTelfUsua = $request->input('varTelfUsua');
        $usua_modi = $request->input('usua_modi');

        $regis_perfil = Usuario::where('varNumeDni', '=', $varNumeDni)
                ->update([
            'varNombUsua' => $varNombUsua,
            'varApelUsua' => $varApelUsua,
            'varTelfUsua' => $varTelfUsua,
            'usua_modi' => $usua_modi
        ]);

        return $this->successResponse($validar);
    }

  

    
        
    
    

}
