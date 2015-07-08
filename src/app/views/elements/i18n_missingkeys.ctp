<?php
/**
 * Este archivo agrega las claves de traducciones faltantess
 * aqui se ponene las traducciones faltantes de la aplicacion completa
 * y en el element dentro de la carpeta del modulo se ponen las clavez que pertenencen al controller actual
 * y en la vista se pondran las clavez faltantes de esa vista
 */
if(isset($this->params['controller']) && $this->params['controller'] != "pages"){
	$this->element($this->params['controller'].'/'.'i18n_missingkeys');
}

$this->I18n->addMissing('spanish',array('desc'=>'Nombre del idioma español','js'=>true));
$this->I18n->addMissing('english',array('desc'=>'Nombre del idioma ingles','js'=>true));
$this->I18n->addMissing('yes',array('desc'=>'la palabra si','js'=>true));
$this->I18n->addMissing('no',array('desc'=>'la palabra no','js'=>true));
$this->I18n->addMissing('acept',array('desc'=>'la palabra aceptar','js'=>true));
$this->I18n->addMissing('cancel',array('desc'=>'la palabra cancelar','js'=>true));

# claves de la barra de edicion
$this->I18n->addMissing('start_edition',array('desc'=>'Boton de la barra que inicia la edicion del contenido','js'=>true,'tab'=>'barra_edicion'));
if(Configure::read("I18n.L10n.active")){
	$this->I18n->addMissing('System.save_content',array('desc'=>'tooltip que aparece en el boton guardar de la barra de edicion','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.cancel_edit',array('desc'=>'tooltip que aparece en el boton cancelar de la barra de edicion','js'=>true,'tab'=>'barra_edicion'));
	#$this->I18n->addMissing('System.select_a_lang',array('desc'=>'Mensaje que aparece en la barra de edicion "Seleccione un idioma"','js'=>true,'tab'=>'barra_edicion'));
	#$this->I18n->addMissing('System.onunload_text',array('desc'=>'Mensaje que aparece cuando se le da click al boton cancelar la edicion','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.title_bold',array('desc'=>'negritas','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('title_italic',array('desc'=>'cursiva','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.title_underline',array('desc'=>'subrayada','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.title_strike_trough',array('desc'=>'tachado','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.title_superscript',array('desc'=>'superindice','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.title_subscript',array('desc'=>'subindice','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.Font',array('desc'=>'palabra fuente','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.Normal',array('desc'=>'palabra fuente','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.Header',array('desc'=>'palabra fuente','js'=>true,'tab'=>'barra_edicion'));
	#$this->I18n->addMissing('System.Font_types',array('desc'=>'Mensaje tipos de fuente que aparece en la lista desplegable que contiene las fuentes en la barra de edicion','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.Paragraph',array('desc'=>'palabra parrafo','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.select_a_language',array('desc'=>'Seleccione un lenguaje','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.title_bulleted_list',array('desc'=>'lista de vinetas','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.title_numbered_list',array('desc'=>'lista numerada','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.increase_indent',array('desc'=>'aumentar sangria','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.decrease_indent',array('desc'=>'disminuir sangria','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.align_left',array('desc'=>'Alinear a la izquierda ','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.align_right',array('desc'=>'Alinear a la derecha','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.align_justify',array('desc'=>'Justificar','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.align_center',array('desc'=>'Centrar','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.Styles',array('desc'=>'palabra estilos','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.Tools',array('desc'=>'palabra herramientas','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.insert_link',array('desc'=>'insertar link','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.remove_link',array('desc'=>'eliminar link','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.select_all',array('desc'=>'seleccionar todo','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.remove_format',array('desc'=>'borrar formato','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.undo',array('desc'=>'palabra deshacer','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.redo',array('desc'=>'palabra rehacer','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.copy',array('desc'=>'palabra copiar','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.cut',array('desc'=>'palabra cortar','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.Insert_image',array('desc'=>'insertar imagen','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.title_help',array('desc'=>'Tooltip del boton ayuda','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.save',array('desc'=>'palabra guardar','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.tab_Meta_datos',array('desc'=>'titulo de la pestaña de metadatos de la barra de edicion','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.tab_Elementos',array('desc'=>'titulo de la pestaña de elementos htnl no editables de la barra de edicion','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.Finalization',array('desc'=>'Nombre de seccion de la barra de edicion','js'=>true,'tab'=>'barra_edicion'));
	#$this->I18n->addMissing('System.select_a_lang',array('desc'=>'Seleccione un idioma: aparece en la lista desplegable de la barra de edicion','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.show_missing_keys',array('desc'=>'mostrar las clavez faltantes','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.show_bar',array('desc'=>'Texto del boton que muestra la barra de edicion','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.hide_bar',array('desc'=>'Texto del boton que oculta la barra de edicion','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.select_a_lang_to_save_please',array('desc'=>'Mensaje que aparece cuando se le da click al boton guardar sin antes aver seleccionado un idioma','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.contect_saved_successfully',array('desc'=>'Mensaje que aparece cuando los cambios en el contenido se guardo correctamente','js'=>true,'tab'=>'barra_edicion'));
	// ventana links
	$this->I18n->addMissing('System.window_link_title',array('desc'=>'Titulo del cuadro de dialogo de link','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.L10n_url_link_label',array('desc'=>'Etiqueta de la caja de texto url del link','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.L10n_title_link',array('desc'=>'Etiqueta de la caja de texto del mensaje que aparece cuando pasa el mouse por arriba del link','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.L10n_target_link',array('desc'=>'Etiqueta que indica que el link se abrira en una ventana o pestaña del navegador nueva','js'=>true,'tab'=>'barra_edicion'));
	// ventana que aparece cuando se intenta ir a otra pagina
	$this->I18n->addMissing('System.leave_this_page_quest',array('desc'=>'Mensaje de confirmacion que aparece cuanso se intena seguir un link ','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.save_and_leave',array('desc'=>'Boton de guardar y salir','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.only_leave',array('desc'=>'Botono de salir sin guardar','js'=>true,'tab'=>'barra_edicion'));
	// ventana de ayuda
	$this->I18n->addMissing('System.L10nHelpDialogTitle',array('desc'=>'Titulo de la ventana de ayudar','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.L10nHelpBasicTab',array('desc'=>'Titulo de la pestaña de ayuda basica','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.L10nHelpAdvancedTab',array('desc'=>'titulo de la pestaña de ayuda avanzada','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.L10nHelpShotcutsTab',array('desc'=>'Titulo de la pestaña de atajos del teclado','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.L10nHelpBasicTabContent',array('desc'=>'Contenido de la ayuda basica','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.L10nHelpAdvancedTabContent',array('desc'=>'Contenido de la ayuda avanzada','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.L10nHelpShortcutsTabContent',array('desc'=>'Contenido de ayuda atajos del teclado','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.close',array('desc'=>'palabra cerrar','js'=>true,'tab'=>'barra_edicion'));

	$this->I18n->addMissing('System.text-element',array('desc'=>'Titulo de la ventana de edicion de un elemento de texto','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.button-element',array('desc'=>'Titulo de la ventana de edicion de un elemento de texto','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.select-element',array('desc'=>'Titulo de la ventana de edicion de un elemento de texto','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.img-element',array('desc'=>'Titulo de la ventana de edicion de un elemento de texto','js'=>true,'tab'=>'barra_edicion'));
	$this->I18n->addMissing('System.link-element',array('desc'=>'Titulo de la ventana de edicion de un elemento de texto','js'=>true,'tab'=>'barra_edicion'));
}
	# terminan las clavez de la barra de edicion

	# Claves para todo los mensajes de los modulos
	$this->I18n->addMissing('specify_a_state',array('desc'=>'Mensaje de error que aparece cuando no se especifica el estado que desa poner a los elementos','js'=>true,'tab'=>'modulo'));
	$this->I18n->addMissing('an_error_ocurred_on_the_server',array('desc'=>'Mensaje de error en el servidor','js'=>true,'tab'=>'modulo'));
	$this->I18n->addMissing('some_fields_invalid',array('desc'=>'Mensaje que aparece cuando nos se llenaron bien los campos de los formularios','js'=>true,'tab'=>'modulo'));
	$this->I18n->addMissing('no_items_selected',array('desc'=>'Mensaje de error que aparece cuando no se selecciona ningun user para eleminar o hacer un cambio de estado','js'=>true,'tab'=>'modulo'));
	$this->I18n->addMissing('required_field',array('desc'=>'Mensaje de error de campo requerido','js'=>true,'tab'=>'modulo'));
	$this->I18n->addMissing('valid_email',array('desc'=>'Mensaje de error de email valido','js'=>true,'tab'=>'modulo'));

	$this->I18n->addMissing("[:System.back_to_list:]", array('desc'=>"boton volver a la lista que aparece cuando no encuentra ningun elemento en las busquedas","tab"=>"modulo"));

	$this->I18n->addMissing('m_articles_url',array('desc'=>'url del blog','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('movies_url',array('desc'=>'url de detalle de pelicula','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('billboard_url',array('desc'=>'url de cartelera','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('shows_buy_url',array('desc'=>'url del proceso de compra','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('buy_url',array('desc'=>'url del detalle de la compra','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('profile_url',array('desc'=>'url del perfil de usuarios','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('edit_profile_url',array('desc'=>'url de la edicion del perfil de usuarios','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('change_pass_url',array('desc'=>'url de la pagina cambio de contraseña','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('set_pass_url',array('desc'=>'url de la pagina poner contraseña','js'=>false,'tab'=>'urls'));

	$this->I18n->addMissing('m_about_url',array('desc'=>'url de la pagina about','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('m_services_url',array('desc'=>'url del blog','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('m_locations_url',array('desc'=>'url del blog','js'=>false,'tab'=>'urls'));

	$this->I18n->addMissing('m_contacts_url',array('desc'=>'url de la pagina de la pagina de contacto','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('tag_url',array('desc'=>'url de la pagina de la etiqueta','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('category_url',array('desc'=>'url de la pagina de la categoria','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('privacy_policies_url',array('desc'=>'url de la pagina de politicas de privacidad','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('sitemap_url',array('desc'=>'url de la pagina de mapa del sitio','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('search_url',array('desc'=>'url de la pagina del buscador','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('thanks_url',array('desc'=>'url de la pagina de agradecimiento de contacto','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('m_register_url',array('desc'=>'url de la pagina de registro','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('user_confirm_url',array('desc'=>'url de la pagina de confirmacion de usuarios','js'=>false,'tab'=>'urls'));
	$this->I18n->addMissing('buy_error_url',array('desc'=>'url de la pagina de error en la compra','js'=>false,'tab'=>'urls'));

	$this->I18n->addMissing("[:year:]",array('desc'=>'Palabra año','js'=>true,'tab'=>"extras"));
	$this->I18n->addMissing("[:month:]",array('desc'=>'Palabra mes','js'=>true,'tab'=>"extras"));
	$this->I18n->addMissing("[:hoy:]",array('desc'=>'Palabra hoy','js'=>true,'tab'=>"extras"));
	$this->I18n->addMissing("[:manana:]",array('desc'=>'Palabra mañana','js'=>true,'tab'=>"extras"));

	foreach(range(1,12) as $i){
		$this->I18n->addMissing($this->Time->format("[:F:]",  mktime(null,null,null,$i,2,2010)),array('desc'=>'Mes','js'=>true,'tab'=>"extras"));
		$this->I18n->addMissing($this->Time->format("[:M:]",  mktime(null,null,null,$i,2,2010)),array('desc'=>'Mes abreviado','js'=>true,'tab'=>"extras"));
	}

	foreach(range(14,20) as $i){
		$this->I18n->addMissing($this->Time->format("[:l:]",  mktime(null,null,null,6,$i,2015)),array('desc'=>'Día','js'=>true,'tab'=>"extras"));
		$this->I18n->addMissing($this->Time->format("[:D:]",  mktime(null,null,null,6,$i,2015)),array('desc'=>'Día abreviado','js'=>true,'tab'=>"extras"));
	}

	if(isset($this->Uploader)){
		$this->I18n->addMissing("choose_files", "Boton Elegir Archivos","uploader",true);
		$this->I18n->addMissing("delete_uploads", "Boton Eliminar todo","uploader",true);
		$this->I18n->addMissing("cancel_uploads", "Boton cancelar todo","uploader",true);
		$this->I18n->addMissing("files_loaded", "texto 'archivos cargados' que aparece en la barra de estado'","uploader",true);
		$this->I18n->addMissing("files_loading", "texto 'archivos cargados' que aparece en laSystem. barra de estado'","uploader",true);
		$this->I18n->addMissing("file-name", "Etiqueta el campo nombre del archivo","uploader",true);
		$this->I18n->addMissing("img-alt-attr", "Etiqueta el campo alt del archivo","uploader",true);
		$this->I18n->addMissing("file-description", "Etiqueta el campo descripcion del archivo","uploader",true);
		$this->I18n->addMissing("no-archivos-mismo-nombre", "Mensaje de error que sale cuando se repite el nombre del arcSystem.hivo","uploader",true);
		$this->I18n->addMissing("demasiado-grande", "Mensaje de error que sale cuando el archivo es muy grande","uploader",true);
		$this->I18n->addMissing("demasiado-grande-para-ser-procesado", "Mensaje de error que sale cuanSystem.do el archivo es muy grande para ser procesado","uploader",true);
		$this->I18n->addMissing("delete_all_uploads", "Esta seguro que desea eliminar todo?","uploader",true);
		$this->I18n->addMissing("no_files_loaded", "Mensaje que aparece en la barra de estado cuando no hay archivos cargados","uploader",true);
		$this->I18n->addMissing("se-alcanzo-el-limite-de-archivos-permitidos", "Mensaje que aparece cuando se alcanzo el limite de archSystem.iSystem.vos en la galeria","uploader",true);
		$this->I18n->addMissing("solo-se-puede-subir-un-archivo", "Mensaje que aparece cuando solo se puede subir un archivo","uploader",true);
	}

if($loggedUser['User']['group_id'] != Configure::read("Group.System")){
	$this->I18n->addHiddeTabs(array('uploader','barra_edicion','urls'));
}

?>