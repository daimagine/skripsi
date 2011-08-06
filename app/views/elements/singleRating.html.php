<script type="text/javascript">
    $(document).ready(function() {
        $(".objectRating<?=@$identifier?>").allRating({
            theme: 'tinystar',
            onClickEvent: function(input) {
                //                alert(input.val());
            },
            <?php if(!isset($rating)) { echo "input: 'radio',"; }?>
        });
    });
</script>
<?php
    $options = array(
        1 => 'So bad',
        2 => 'Once is enough',
        3 => "It's nice",
        4 => "Really Good",
        5 => "I love it"
    );
?>
<?php  if(isset($rating) && !is_array($rating)) : ?>
    <select class="objectRating<?=@$identifier?> singleRating" name="rating" disabled="disabled">
        <?php foreach($options as $key => $value): ?>
            <option <?php
                if($rating->value >= (int)$key) {
                    echo "selected='selected'";
                    $value = "<b>$rating->value</b> point from $rating->voters reviews";
                }
            ?>
                value="<?=$key?>"><?=$value;?></option>
        <?php endforeach; ?>
    </select>
<?php  elseif(isset($rating) && is_array($rating)) : ?>
    <select class="objectRating<?=@$identifier?> singleRating" name="rating" disabled="disabled">
        <?php foreach($options as $key => $value): ?>
            <option <?php
                if($rating['value'] >= (int)$key) {
                    echo "selected='selected'";
                    $value = "<b>".$rating['value']."</b> point from ".$rating['voters']." reviews";
                }
            ?>
                value="<?=$key?>"><?=$value;?></option>
        <?php endforeach; ?>
    </select>
<?php else: ?>
    <span class="objectRating<?=@$identifier?> ratingEditDisabled singleRating">
        <input type="radio" name="rating" value="1">
        <input type="radio" name="rating" value="2">
        <input type="radio" name="rating" value="3">
        <input type="radio" name="rating" value="4">
        <input type="radio" name="rating" value="5">
   </span>
<?php endif; ?>

