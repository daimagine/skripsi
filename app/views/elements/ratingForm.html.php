<script type="text/javascript">
    $(document).ready(function() {
//        $(".boxInputRating").stars({
//            inputType: 'select',
//            disableValue: false
//        });
        $(".dunfy").allRating({
            theme: 'tinystar',
            input: 'radio',
            onClickEvent: function(input) {
//                alert(input.val());
            }
        });
    });
</script>

<style type="text/css">

    .inputRating {
        display: block;
        width: 500px;
        padding: 5px;
        background: #FFFFFF;
        border: 1px solid #D9D9D9;
        height: 65px;
    }
    .inputRating a {
        margin-left: 0;
    }
    .boxInputRating {
        margin-right: 12px;
        float: left;
    }
    .boxInputRating .ratingTitle {
        display: block;
        margin-bottom: 4px;
        margin-left: 0;
    }
    .ratingHeader {
        color: #666666;
        font-size: 1.1em;
    }
</style>

<?php
    $options = array(
        1 => 'So bad',
        2 => 'Once is enough',
        3 => "It's nice",
        4 => "Really Good",
        5 => "I love it"
    );
?>

<div class="inputRating linkPanel marginLeftInfo">
    <span class="ratingHeader">Please give your desired rating.</span><hr class="hrGray">
    <div class="boxInputRating">
        <span class="ratingTitle">Overall</span>
<!--        <select name="rating[overall]" class="inputBox dunfy">-->
<!--            --><?php //foreach($options as $key => $value): ?>
<!--                <option value="--><?//=$key?><!--">--><?//=$value;?><!--</option>-->
<!--            --><?php //endforeach; ?>
<!--        </select>-->
        <div class="dunfy">
            <?php foreach($options as $key => $value): ?>
                <label for="overall<?=$key;?>"><?=$value;?></label>
                <input name="rating[overall]" value="<?=$key;?>" id="overall<?=$key;?>" type="radio" />
            <?php endforeach; ?>
        </div>
    </div>
<?php if(isset($ratingFor) && $ratingFor != 'dishes' && $ratingFor != 'promos'):?>
    <div class="boxInputRating">
        <span class="ratingTitle">Food</span>
<!--        <select name="rating[food]" class="inputBox dunfy">-->
<!--            --><?php //foreach($options as $key => $value): ?>
<!--                <option value="--><?//=$key?><!--">--><?//=$value;?><!--</option>-->
<!--            --><?php //endforeach; ?>
<!--        </select>-->
        <div class="dunfy">
            <?php foreach($options as $key => $value): ?>
                <label for="overall<?=$key;?>"><?=$value;?></label>
                <input name="rating[food]" value="<?=$key;?>" id="overall<?=$key;?>" type="radio" />
            <?php endforeach; ?>
        </div>
    </div>
    <div class="boxInputRating">
        <span class="ratingTitle">Service</span>
<!--        <select name="rating[service]" class="inputBox dunfy">-->
<!--            --><?php //foreach($options as $key => $value): ?>
<!--            <option value="--><?//=$key?><!--">--><?//=$value;?><!--</option>-->
<!--            --><?php //endforeach; ?>
<!--        </select>-->
        <div class="dunfy">
            <?php foreach($options as $key => $value): ?>
                <label for="overall<?=$key;?>"><?=$value;?></label>
                <input name="rating[service]" value="<?=$key;?>" id="overall<?=$key;?>" type="radio" />
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($ratingFor) && $ratingFor != 'promos'):?>
    <div class="boxInputRating">
        <span class="ratingTitle">Price</span>
<!--        <select name="rating[price]" class="inputBox dunfy">-->
<!--            --><?php //foreach($options as $key => $value): ?>
<!--            <option value="--><?//=$key?><!--">--><?//=$value;?><!--</option>-->
<!--            --><?php //endforeach; ?>
<!--        </select>-->
        <div class="dunfy">
            <?php foreach($options as $key => $value): ?>
                <label for="overall<?=$key;?>"><?=$value;?></label>
                <input name="rating[price]" value="<?=$key;?>" id="overall<?=$key;?>" type="radio" />
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($ratingFor) && $ratingFor == 'dishes'):?>
    <div class="boxInputRating">
        <span class="ratingTitle">Taste</span>
<!--        <select name="rating[taste]" class="inputBox dunfy">-->
<!--            --><?php //foreach($options as $key => $value): ?>
<!--            <option value="--><?//=$key?><!--">--><?//=$value;?><!--</option>-->
<!--            --><?php //endforeach; ?>
<!--        </select>-->
        <div class="dunfy">
            <?php foreach($options as $key => $value): ?>
                <label for="overall<?=$key;?>"><?=$value;?></label>
                <input name="rating[taste]" value="<?=$key;?>" id="overall<?=$key;?>" type="radio" />
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
</div>