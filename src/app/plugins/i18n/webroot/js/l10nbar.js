/**
 * Crea la barra en la que se muestra el boton para iniciar la edición del contenido
 */
$(function(){
	$("body").createAppend(
		'div',{id:'L10nRibbonBar'},[
			'div',{className:'ribbonContent'},[
				'div',{className:'ribbonCenter',href:''},[
					'div',{className:'RibbonTabsTools'},[
						'a',{className:'showBar startEdition',id:'L10nRibbonBarHiderShower',href:'/admin/i18n/l10n/edit'},__("start_edition")
					]
				]
			]
		]
	);
});