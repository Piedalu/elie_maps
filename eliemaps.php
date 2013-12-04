<?php
/**
 * Plugin Name: eliemaps
 * Description: Affiche une carte Google maps.
 * Version: 1.0
 * Author: Elie Deilhes
 * License: ---
 */



/*
Possibilité d'enregistrer plusieurs cartes.
Ajouter un paramètre (ID de la carte enregistrée) lors de l'appel de la fonction d'affichage
Afficher un aperçu automatique lors de la saisie d'adresse...
Améliorer la sécurité du plugin (vérifier les autorisations, insérer un "nonce"...)
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
	add_meta_box('marqueurs', 'Marqueurs', 'eliemaps_metabox_marqueurs', 'eliemaps');
	add_meta_box('format', 'Format', 'eliemaps_metabox_format', 'eliemaps');
	add_meta_box('carte', 'Carte', 'eliemaps_metabox_carte', 'eliemaps');
}

/* Création du champs personnalisé : "Adresse" */
function eliemaps_metabox_format($object){
	?>
	<?php 
	/* Table des formats disponibles */
	$format = array(
		'200x200' => 'Miniature (200x200)',
		'400x400' => 'Standard (400x400)',
		'800x400' => 'Large (800x400)',
		'800x200' => 'Bannière (800x200)'
		);
	?>
	<label>Format:</label>
	<select name="eliemaps_format">
		<?php
		foreach ($format as $valeur => $nom) {
			$option = '<option value = "' . $valeur . '"';
			if ($valeur == get_post_meta($object->ID, '_format', TRUE)) {
				$option = $option . 'selected';
			}
			$option = $option . '>' . $nom . '</option>';

			echo $option;	
		}
		?>
	</select>

	<?php /* possibilité d'ajouter une échelle... */ ?>
	<label>Zoom:</label>
	<input type="range" name="eliemaps_zoom" min="12" max="16" value="<?php echo get_post_meta($object->ID, '_zoom', TRUE); ?>")/>
	<?php
}

/* Création du champs personnalisé : "Adresse" */
function eliemaps_metabox_marqueurs($object){
	?>
	<fieldset>
		<label>Adresse 1:</label>
		<input type="text" name="eliemaps_adresse_1" value="<?php echo esc_attr(get_post_meta($object->ID, '_adresse_1', TRUE)); ?>" style="width:100%"/>
		<label>Nom:</label>
		<input type="text" name="eliemaps_nom_1" value="<?php echo esc_attr(get_post_meta($object->ID, '_nom_1', TRUE)); ?>"/>
		<label>Couleur:</label>
		<input type="color" name="eliemaps_couleur_1" value="<?php echo esc_attr(get_post_meta($object->ID, '_couleur_1', TRUE)); ?>"/>
	</fieldset>

	<fieldset>
		<label>Adresse 2:</label>
		<input type="text" name="eliemaps_adresse_2" value="<?php echo esc_attr(get_post_meta($object->ID, '_adresse_2', TRUE)); ?>" style="width:100%"/>
	</fieldset>
	<?php
}

/* Création et affichage de la carte en fonction des paramètres choisis */
function eliemaps_metabox_carte($object){
	//Création de l'url de la carte Google Maps
	$url = "http://maps.googleapis.com/maps/api/staticmap?center=";
	$url = $url . rawurlencode(get_post_meta($object->ID, '_adresse_1', TRUE));
	$url = $url . "&zoom=" . get_post_meta($object->ID, '_zoom', TRUE);
	$url = $url . "&size=" . get_post_meta($object->ID, '_format', TRUE);
	$url = $url . "&markers=color:" . get_post_meta($object->ID, '_couleur_1', TRUE)
			 . "%7Clabel:" . get_post_meta($object->ID, '_nom_1', TRUE)
			 . "%7C" . rawurlencode(get_post_meta($object->ID, '_adresse_1', TRUE));
	$url = $url . "&sensor=false";

	?>
	<img src="<?php echo $url; ?>"></img>
	<?php
}

/* Enregistrement des valeurs saisies */
function eliemaps_savepost($post_id, $post){
	if(isset($_POST['eliemaps_adresse_1'])) {
		update_post_meta($post_id, '_adresse_1', $_POST['eliemaps_adresse_1']);
	}

	if(isset($_POST['eliemaps_nom_1'])) {
		update_post_meta($post_id, '_nom_1', $_POST['eliemaps_nom_1']);
	}

	if (isset($_POST['eliemaps_couleur_1'])) {
		update_post_meta($post_id, '_couleur_1', $_POST['eliemaps_couleur_1']);
	}

	if(isset($_POST['eliemaps_adresse_2'])) {
		update_post_meta($post_id, '_adresse_2', $_POST['eliemaps_adresse_2']);
	}

	if (isset($_POST['eliemaps_couleur_2'])) {
		update_post_meta($post_id, '_couleur_2', $_POST['eliemaps_couleur_2']);
	}

	if(isset($_POST['eliemaps_format'])) {
		update_post_meta($post_id, '_format', $_POST['eliemaps_format']);
	}

	if(isset($_POST['eliemaps_zoom'])) {
		update_post_meta($post_id, '_zoom', $_POST['eliemaps_zoom']);
	}
}

/* Affichage de la carte */
function eliemaps_affiche(){
	echo 'Ma carte sera ici !';
}



?>