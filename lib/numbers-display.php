<?php

add_action('init', 'export_players')
function export_players(){
  if($_GET['export']){
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=file.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "record1,record2,record3\n";
    return; 
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