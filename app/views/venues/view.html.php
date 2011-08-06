<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );

    $this->title($venue->name);

    $this->set(array(
        'header' => '<h1>Skripsi Gan</h1>',
//        'subHeader' => $venue->name,
        'navbar' => array('element' => 'navbar'),
        'footer' => '',
    ));
?>

<?php ob_start();?>
    <script type="text/javascript">
        $(document).ready(function(){
            
        });

        //toggling description
        function toggleDesc(obj){
            objDesc = '#'+obj.id+'Desc';
            $(objDesc).toggle();
        }
    </script>
<?php $this->scripts(ob_get_clean());?>

<div id="main-container">
    <?php //BREADCRUMB
        $breadcrumb = array(
    //        array($this->url(array('Venues::view', 'id' => $venue->_id)), $venue->name),
            array(APP_PATH, 'Home'),
            array($this->url(array('Venues::index')), 'List Venues'),
            array($venue->name, '#'),
        );
//        echo $this->view()->render(array('element' => 'breadcrumb'), compact('breadcrumb'));
        $this->set(compact('breadcrumb'));
    ?>
    
    <fieldset>
        <legend><?=$venue->name; ?></legend>
        <label>Description :</label>
        <blockquote><?=$venue->description; ?></blockquote>

        <div>
            <div id="venueTags">
                <?php if($venue->checkAttrib('tags')):?>
                    <label>Tag : </label> <?php foreach ($venue->tags as $tag): ?>
                        <?=$this->html->link($tag, array('Venues::index', 'args' => array($tag))); ?>
                    <?php endforeach ?>
                <?php endif;?>
            </div>
            <div id="venueRate">
                
            </div>
            <div id="venueDateTime">
                created at <span><?=date('d-m-y h:i:s', $venue->created->sec)?></span>
                updated at <span><?=date('d-m-y h:i:s', $venue->updated->sec)?></span>
            </div>
        </div>
        <hr>
        <legend>
            <div id="venueReviewsForm">
                <?=$this->form->create(null, array('url'=>"venues/writeReview/$venue->_id"));?>
                <?=$this->form->field('review', array('type'=>'textarea'));?>
                Rating<?=$this->form->select('rating',
                    array('0'=>'zero', '1'=>'one', '2'=>'two', '3'=>'three', '4'=>'four', '5'=>'five'),
                    array('id'=>'submitVenueRating')
                );?>
                <?=$this->form->hidden('user_id', array(
                    'value' => $web_user['_id']
                ));?>
                <?=$this->form->submit('submit');?>
                <?=$this->form->end();?>
            </div>
        </legend>
        <hr>
        <div>
            <p>
                <?//=$this->html->link('Dishes', array('Dishes::venueDishes', 'venueId' => $venue->_id));?>
                <?=$this->html->link('Change venue information', array('Venues::edit', 'id' => $venue->_id), array('class' => 'edit')); ?>
            </p>
        </div>
        <br>
        <fieldset>
            <legend>Venue Dishes</legend>
            <div>
                <dl>
<!--                    --><?php //if($venue->checkAttrib('dishes')):?>
                    <?php if($dishes):?>
                        <?php foreach($dishes as $dish):?>
                            <dt id="<?=\lithium\util\Inflector::camelize($dish->name);?>"
                                onclick="toggleDesc(this);">&bull; <?=$dish->name?></dt>
                            <dd class="hidden" id="<?=\lithium\util\Inflector::camelize($dish->name).'Desc';?>">
                                Price : <?=$dish->price?></br>
                                Description : <?=$dish->description?></br>
                                Tags : <?=implode(",", \lithium\data\collection\DocumentArray::toArray($dish->tags));?></br>
                                <div class="dishReviewsForm">
                                    <?=$this->form->create(null, array('url'=>"dishes/writeDishReview/$venue->_id/$dish->id"));?>
                                    <?=$this->form->field('review', array('type'=>'textarea'));?>
                                    Rating<?=$this->form->select('rating',
                                                                 array('0'=>'zero', '1'=>'one', '2'=>'two', '3'=>'three', '4'=>'four', '5'=>'five'),
                                                                 array('class'=>'submitDishRating')
                                );?>
                                    <?=$this->form->submit('submit');?>
                                    <?=$this->form->end();?>
                                </div>
                            </dd>
                        <?php endforeach;?>
                    <?php else:?>
                        This Venue is not have any dishes yet.
                    <?php endif;?>
                </dl>
            </div>
            <hr>    
<!--            --><?//=$this->html->link('Add Dish', array('Venues::addDish', 'id' => $venue->_id), array('class' => 'add'));?>
            <?=$this->html->link('Add Dish', ("dishes/add/$venue->_id"), array('class' => 'add'));?>
        </fieldset>
    </fieldset>

    <?//=$this->html->image("/venues/view/{$venue->_id}.jpg", array('alt' => $venue->name)); ?>

<!--    <div class="lithium-stack-trace">-->
<!--        <pre>-->
<!--        --><?//=print_r($venue);?>
<!--        </pre>-->
<!--    <div class="lithium-stack-trace">-->
</div>