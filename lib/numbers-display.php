<?php

//Camp numbers submenu display
function display_camp_numbers(){ 
  
  echo '<pre>';
  $camps = get_terms(array('camp'));
  $grades = get_terms(array('grade'));
  $players = get_posts(array(
      'posts_per_page' => -1, 
      'post_type' => 'player',
      'camp'=> $terms[0]->slug
    ));

  var_dump($camps);
  var_dump($grades);
  var_dump($players);
?>

<h1>Camp Numbers</h1>



<?php
}