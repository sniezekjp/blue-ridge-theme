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
        $data[$camp->name][$grade->slug] = count($players);     
    }
  }

  var_dump($data);

  echo '</pre>';
?>

<h1>Camp Numbers</h1>
  <?php foreach($data as $camp => $grades) : ?>
    <h3><?php echo $camp; ?></h3>
    <table>
      <tr>
        <th>Grade</th>
        <th>Count</th>
      </tr>
      <?php foreach($grades as $grade => $count) : ?>
        <tr>
          <td><?php echo $grade; ?></td>
          <td><?php echo $count; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endforeach; ?>



<?php
}