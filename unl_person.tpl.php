<?php
$url = UNLPersonEntityController::$api_url . '?uid=' . $unl_person->uid;
$partial_url = $url . '&format=partial';
?>
<script type="text/javascript">
    WDN.loadJQuery(function() {
      WDN.jQuery.get('<?php echo $partial_url?>', function(data) {
        WDN.jQuery('.unl_person_<?php echo $unl_person->uid; ?>').html(data);
      });
    });
</script>

<div class="unl_person unl_person_<?php echo $unl_person->uid; ?> unl_person_affiliation_<?php echo $unl_person->edu_affiliation ?> echo <?php $partial_class ?>">
  <a href="<?php echo $url ?>"><?php echo $unl_person->first_name ?> <?php echo $unl_person->last_name ?></a>
</div>