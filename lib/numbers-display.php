<?php

//Camp numbers submenu display
function display_camp_numbers(){ 
  
  echo '<pre>';
  $camps = get_terms(array('camp'));
  $grades = get_terms(array('grade'));
  $players = get_posts(array(
      'posts_per_page' => -1, 
      'post_type' => 'player',
      'camp'=> $camps[0]->slug,
      'grade'=> $grades[0]->slug
    ));

  // var_dump($camps);
  // var_dump($grades);
  // var_dump($players);

  $data = array();
  foreach($camps as $camp){
    foreach($grades as $grade){
        $players = get_posts(array(
          'posts_per_page' => -1, 
          'post_type' => 'player',
          'camp'=> $camp->slug,
          'grade'=> $grade->slug
        ));
        $data[$camp->slug][$grade->slug] = count($players);     
    }
  }

  var_dump($data);

  echo '</pre>';
?>

<h1>Camp Numbers</h1>
<table>
  <tr>
    <th>Camp</th>
    <th>Grade</th>
    <th>Count</th>
  </tr>
  <tr>
    <td>Boys</td>
    <td>1</td>
    <td>12</td>
  </tr>
</table>


<?php
}