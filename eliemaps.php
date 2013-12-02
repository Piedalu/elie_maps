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
*/

/* Initialisation */
add_action('init', 'eliemaps_init');

/* Permet d'initialiser le panneau d'administration des cartes */
function eliemaps_init(){
	register_post_type('carte', array('public' => TRUE, 'label' => 'Cartes'));
}

/* Affichage de la carte */
function eliemaps_affiche(){
	echo 'Ma carte sera ici !';
}



?>