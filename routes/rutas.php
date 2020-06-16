<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$router->post('/regi_usua','UsuarioController@regi_usua');



$router->post('/validar_usuario','UsuarioController@validar_usuario');
$router ->post('/actu_usua','UsuarioController@actu_usua');
$router->post('/elim_usua','UsuarioController@elim_usua');
$router->get('/list_usua','UsuarioController@list_usua');



$router->post('/elim_usua_prog','UsuarioController@elim_usua_prog');
$router->post('/regi_usua_prog','UsuarioController@regi_usua_prog');
$router->post('/obte_perm','UsuarioController@obte_perm');

$router->post('/elim_usua_perf','UsuarioController@elim_usua_perf');


$router->post('/copi_perf','UsuarioController@copi_perf');


$router->post('/regi_usua_etap','UsuarioController@regi_usua_etap');
$router->post('/list_usua_etap','UsuarioController@list_usua_etap');
$router->post('/elim_usua_etap','UsuarioController@elim_usua_etap');



$router->post('/regi_usua_proyecto','UsuarioController@regi_usua_proyecto');

$router->post('/list_usua_proy','UsuarioController@list_usua_proy');

$router->post('/elim_usua_proyec','UsuarioController@elim_usua_proyec');

$router->post('/edit_foto_perfil','UsuarioController@edit_foto_perfil');







// controller Usuario Accion 
$router->post('/gsu_obte_prog_medi_idusu','UsuarioAccionController@gsu_obte_prog_medi_idusu');
$router->get('/list_boto','UsuarioAccionController@list_boto');
$router->post('/vali_boto','UsuarioAccionController@vali_boto');
$router->post('/regi_boto','UsuarioAccionController@regi_boto');
$router->post('/actua_boto','UsuarioAccionController@actua_boto');

$router->post('/elim_asig_boto_todo','UsuarioAccionController@elim_asig_boto_todo');

$router->post('/comb_asig_etapa_actu_proy','UsuarioAccionController@comb_asig_etapa_actu_proy');

$router->post('/obte_boto_medi_modu_prog','UsuarioAccionController@obte_boto_medi_modu_prog');

$router->post('/check_permiso_boto','UsuarioAccionController@check_permiso_boto');



$router->post('/regi_check_usua_boto','UsuarioAccionController@regi_check_usua_boto');





$router->post('/noti_usua','NotificacionesController@noti_usua');
$router->post('/most_info_noti','NotificacionesController@most_info_noti');


$router->post('/traer_tags_usua_publi','NotificacionesController@traer_tags_usua_publi');

$router->post('/muest_integrante_tags','NotificacionesController@muest_integrante_tags');

$router->post('/regis_noti_deta_noti','NotificacionesController@regis_noti_deta_noti');

$router->post('/modi_tags','NotificacionesController@modi_tags');

$router->post('/regis_tags','NotificacionesController@regis_tags');

$router->get('/list_usua_noti','NotificacionesController@list_usua_noti');

/*RUTAS PARA LA SUSC_FIRE*/
$router->get('/listar_susc_fire','NotificacionesController@listar_susc_fire');
$router->post('/listar_susc_deta','NotificacionesController@listar_susc_deta');
$router->post('/regist_susc','NotificacionesController@regist_susc');
$router->post('/editar_susc','NotificacionesController@editar_susc');

