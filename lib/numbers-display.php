<?php

add_action('init', 'export_players');
function export_players(){
  if($_GET['export']){
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=players.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    $players = get_posts(array(
      'posts_per_page' => -1, 
      'post_type' => 'player'
    ));
    foreach($players as $player){
      $id = $player->ID; 

     $camp = wp_get_post_terms($id, array('camp')); 
     $grade = wp_get_post_terms($id, array('grade')); 
     $size = wp_get_post_terms($id, array('size')); 

      //player name
      $names = explode(' ', trim($player->post_title));
      $lastIndex = count($names) - 1;
      
      echo $player->post_title . ',';

      if($names[$lastIndex] == 'Jr.')
        echo $names[$lastIndex - 1] . ',';
      else
        echo $names[$lastIndex] . ',';

      //camp and grade and size
      echo $camp[0]->name . ',';
      echo $grade[0]->name . ',';
      echo $size[0]->name . ',';


      //contact
      echo get_post_meta($id, 'guardian', true) . ',';
      echo get_post_meta($id, 'guardian_phone', true) . ',';
      echo get_post_meta($id, 'guardian_email', true) . ',';
      $info = get_post_meta($id, 'medical_info', true);
      echo '"' . $info . '",'; 
 
      //new line
      echo "\n";
    }
    //echo "record1,record2,record3\n";
    exit();
  }
}

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
   //var_dump($players);
   $terms = wp_get_post_terms($players[0]->ID, array('camp', 'grade'), array('fields'=> 'names'));
   //var_dump($terms);

  $data = array();
  foreach($camps as $camp){
    foreach($grades as $grade){
        $players = get_posts(array(
          'posts_per_page' => -1, 
          'post_type' => 'player',
          'camp'=> $camp->slug,
          'grade'=> $grade->slug
        ));
        $data[$camp->name][$grade->name] = count($players);     
    }
  }

  //var_dump($data);

  echo '</pre>';
?>

<h1>Camp Numbers</h1>

<p><a href="?page=camp_numbers&export=true">Click here to export player data</a></p>

  <?php foreach($data as $camp => $grades) : ?>
    <h3><?php echo $camp; ?></h3>
    <table>
      <tr>
        <td><strong>Grade</strong></td>
        <td><strong>Count</strong></td>
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