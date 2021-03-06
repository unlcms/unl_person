<?php
$url = UNLPersonEntityController::$api_url . 'people/' . $unl_person->uid;
$partial_url = $url . '.partial';
?>
<div class="unl_person unl_person_<?php echo $unl_person->uid; ?> unl_person_affiliation_<?php echo $unl_person->edu_affiliation ?>">
  <a href="<?php echo $url ?>"><?php echo $unl_person->first_name ?> <?php echo $unl_person->last_name ?></a>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        require(['jquery'], function($) {
            $.get('<?php echo $partial_url?>', function(data) {
                var vcard;
                if ($('.vcard', $.parseHTML(data)).length > 0) {
                    // Someone with Knowledge data
                    var vcard = $('.vcard', $.parseHTML(data));
                } else {
                    // Someone without Knowledge data
                    var vcard = $($.parseHTML(data)).filter('.vcard');
                }
                $('.unl_person_<?php echo $unl_person->uid; ?>').html(vcard);
            });
        });
    });
</script>
