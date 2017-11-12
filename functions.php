<?php

// создание языков для приложенных изображений к посту
 function translate_post_attaches( $post_id ) {

	// если это ревизия, не делать ничего
	if ( wp_is_post_revision( $post_id ) )
		return;

	 global $polylang;
    if(!$polylang) return;
    // чекаем языки
    $langs = array();
    foreach (pll_languages_list() as $lang){
      if(pll_get_post_language($post_id) !== $lang){
        $langs[] = $lang;
      }
    }
    
    // вытаскиваем атачи
    $postes = get_posts(array(
      'post_type'      => 'attachment',
      'posts_per_page' => -1,
      'lang'           => pll_get_post_language($post_id),
	  'post_parent'    => $post_id
    ));

    foreach ($postes as $poste) {
      
      foreach ($langs as $new_lang) {
        // фолсит, если перевод уже есть
        if ( ! $polylang->model->post->get_translation( $poste->ID, $new_lang ) ) {
          // создание перевода
		  
          $polylang->filters_media->create_media_translation( $poste->ID, $new_lang );
		  
		}
      }
    }
}
add_action( 'save_post', 'translate_post_attaches' ); 
?>