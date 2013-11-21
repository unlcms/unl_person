<?php
$url = UNLPersonEntityController::$api_url . '?uid=' . $unl_person->uid;
$partial_url = $url . '&format=partial';

$partial_class = 'partial_no';
if ($partial = file_get_contents($partial_url)) {
  $partial_class = 'partial_yes';
}
?>

<div class="unl_person unl_person_<?php echo $unl_person->uid; ?> unl_person_affiliation_<?php echo $unl_person->edu_affiliation ?> echo <?php $partial_class ?>">
<?php
if ($partial) {
  echo $partial;
} else {
  ?>
  <a href="<?php echo $url ?>"><?php echo $unl_person->first_name ?> <?php echo $unl_person->last_name ?></a>
  <?php
}
?>
</div>