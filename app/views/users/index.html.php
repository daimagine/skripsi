<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );

    $this->title($title);

    $this->set(array(
        'header' => '<h1>Skripsi Gan</h1>',
//        'subHeader' => $subHeader,
        'navbar' => array('element' => 'navbar'),
        'footer' => '',
    ));

?>

<div id="main-container">
    <?php //BREADCRUMB
        $breadcrumb = array(
            array(APP_PATH, 'Home'),
            array('List Users', '#'),
        );
//        echo $this->view()->render(array('element' => 'breadcrumb'), compact('breadcrumb'));
        $this->set(compact('breadcrumb'));
    ?>

    <fieldset>
        <legend>List Users</legend>
        <ul id="listUsers">
            <?php foreach ($users as $user): ?>
                <li>
                    <?//=$this->html->image("/users/view/{$user->id}.jpg", array('width'=> 100)); ?>
                    <?=$this->html->link($user->getName(), array('Users::view', 'id' => $user->_id)); ?>
                </li>
            <?php endforeach ?>
        </ul>

        <?php if (!count($users)): ?>
            <p></p><em>No users</em>.</p>
        <?php endif ?>

        <div>
<!--            Your prefered user isnt there yet?  --><?//=$this->html->link('Lets add one!', 'Users::add'); ?>
            <p>Login with your account <?=$this->html->link('here', 'Users::login');?>.<br>
            Have no account yet? Join our community by sign up <?=$this->html->link('here', 'Users::signup');?></p>
        </div>
    </fieldset>
</div>