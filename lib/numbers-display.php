<?php

//Camp numbers submenu display
function display_camp_numbers(){ 
  
  echo '<pre>';
  $terms = get_terms(array('camp'));
  
  var_dump($terms);

  $players = get_posts(array(
      'posts_per_page' => -1, 
      'post_type' => 'player'
    ));

  var_dump($players);
?>

<h1>Camp Numbers</h1>



<?php
}