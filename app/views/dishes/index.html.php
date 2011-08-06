<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );

//    $this->title($title);
//
//    $this->set(array(
//        'header' => '<h1>Skripsi Gan</h1>',
//        'subHeader' => $subHeader,
//        'navbar' => array('element' => 'navbar'),
//        'footer' => '',
//    ));

?>

<div id="main-container">
    <?php //BREADCRUMB
        $breadcrumb = array(
            array(APP_PATH, 'Home'),
            array('List Dishes', '#'),
        );
//        echo $this->view()->render(array('element' => 'breadcrumb'), compact('breadcrumb'));
        $this->set(compact('breadcrumb'));
    ?>

    <fieldset>
        <legend>List Dishes</legend>
        <ul id="listDishes">
            <?php foreach ($dishes as $dish): ?>
                <li>
                    <?//=$this->html->image("/dishes/view/{$dish->id}.jpg", array('width'=> 100)); ?>
                    <?=$this->html->link($dish->name, array('Dishes::view', 'id' => $dish->_id)); ?>
                </li>
            <?php endforeach ?>
        </ul>

        <?php if (!count($dishes)): ?>
            <em>No dishes</em>.
        <?php endif ?>

        <div>
            Contribute yours. <?=$this->html->link('Add one', 'Dishes::add'); ?>.
        </div>
    </fieldset>
</div>