<?php
/**
 * Plugin Name: eliemaps
 * Description: Affiche une carte Google maps.
 * Version: 1.0
 * Author: Elie Deilhes
 * License: ---
 */



/*
Prévoir un panneau d'administration pour générer la carte à partir d'une adresse
Possibilité d'enregistrer plusieurs cartes.
Afficher un apperçu automatique lors de la saisie d'adresse...
Améliorer la sécurité du plugin
*/

/* Initialisation */
add_action('init', 'eliemaps_init');
add_action('add_meta_boxes', 'eliemaps_metabox');
add_action('save_post', 'eliemaps_savepost', 10,2);

/* Permet d'initialiser le panneau d'administration des cartes */
function eliemaps_init(){

	$labels = array(
		'name' => 'Cartes',
		'singular_name' => 'carte',
		'menu_name' => 'Eliemaps',
		'add_new' => 'Ajouter une carte',
		'add_new_item' => 'Ajouter une nouvelle carte',
		'edit_item' => 'Editer une carte',
		'new_item' => 'Nouvelle carte',
		'view_item' => 'Voir la carte',
		'search_item' => 'Rechercher une carte',
		'not_found' => 'Aucune carte trouvée',
		'not_found_in_trash' => 'Aucune carte trouvée dans la corbeille',
		);

	register_post_type('eliemaps', array(
		'public' => TRUE,
		'publicly_queryable' => FALSE,
		'labels' => $labels,
		'supports' => array('title')
		));
}

/* Création d'une métabox pour les champs personnalisés */
function eliemaps_metabox(){
	add_meta_box('adresse', 'Adresse', 'eliemaps_metabox_adresse', 'eliemaps');
	/* ajouter un emplacement pour visualiser la carte */
}

/* Création du champs personnalisé : "Adresse" et affichage de la carte */
function eliemaps_metabox_adresse($object){
	//Création de l'url de la carte Google Maps
	$url = "http://maps.googleapis.com/maps/api/staticmap?center=";
	$url = $url . rawurlencode(get_post_meta($object->ID, '_adresse', TRUE));
	$url = $url . "&zoom=15&size=400x400&sensor=false";
	
	?>
	<label>Adresse:</label>
	<input type="text" name="eliemaps_adresse" value="<?php echo esc_attr(get_post_meta($object->ID, '_adresse', TRUE)); ?>" style="width:100%"/>

	<label>Carte:</label>
	<img src="<?php echo $url; ?>"></img>
	<?php
}

/* Enregistrement des valeurs saisies */
function eliemaps_savepost($post_id, $post){
	if(!isset($_POST['eliemaps_adresse'])) {
		return $post_id;
	}

	update_post_meta($post_id, '_adresse', $_POST['eliemaps_adresse']);
}

/* Affichage de la carte */
function eliemaps_affiche(){
	echo 'Ma carte sera ici !';
}



?>