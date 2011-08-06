<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );

    $this->title($title);

    $this->set(array(
        'header' => '<h1>Skripsi Gan</h1>',
        'subHeader' => $subHeader,
        'navbar' => array('element' => 'navbar'),
        'footer' => '',
    ));

?>

<div id="main-container">
    <?php //BREADCRUMB
        $breadcrumb = array(
            array(APP_PATH, 'Home'),
            array('List Venues', '#'),
        );
//        echo $this->view()->render(array('element' => 'breadcrumb'), compact('breadcrumb'));
        $this->set(compact('breadcrumb'));
    ?>

    <fieldset>
        <legend>List Venues</legend>
        <ul id="listVenues">
            <?php foreach ($venues as $venue): ?>
                <li>
                    <?//=$this->html->image("/venues/view/{$venue->id}.jpg", array('width'=> 100)); ?>
                    <?=$this->html->link($venue->name, array('Venues::view', 'id' => $venue->_id)); ?>
                </li>
            <?php endforeach ?>
        </ul>

        <?php if (!count($venues)): ?>
            <p></p><em>No venues</em>.</p>
        <?php endif ?>

        <div>
            Your prefered venue isnt there yet?  <?=$this->html->link('Lets add one!', 'Venues::add'); ?>
        </div>
    </fieldset>
</div>