<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );

    $this->title($user->name);

    $this->set(array(
        'header' => '<h1>Skripsi Gan</h1>',
//        'subHeader' => $user->name,
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
    //        array($this->url(array('Users::view', 'id' => $user->_id)), $user->name),
            array(APP_PATH, 'Home'),
            array($this->url(array('Users::index')), 'List Users'),
            array($user->name, '#'),
        );
//        echo $this->view()->render(array('element' => 'breadcrumb'), compact('breadcrumb'));
        $this->set(compact('breadcrumb'));
    ?>
    
    <fieldset>
        <legend><?=$user->name; ?></legend>
        <label>Description :</label>
        <blockquote><?=$user->description; ?></blockquote>

        <div>
            <div id="userTags">
                <?php if($user->checkAttrib('tags')):?>
                    <label>Tag : </label> <?php foreach ($user->tags as $tag): ?>
                        <?=$this->html->link($tag, array('Users::index', 'args' => array($tag))); ?>
                    <?php endforeach ?>
                <?php endif;?>
            </div>
            <div id="userRate">
                
            </div>
        </div>
        <hr>

        <div>
            <p>
                <?//=$this->html->link('Products', array('Products::userProducts', 'userId' => $user->_id));?>
                <?=$this->html->link('Change user information', array('Users::edit', 'id' => $user->_id), array('class' => 'edit')); ?>
            </p>
        </div>
        <br>
        <fieldset>
            <legend>User Products</legend>
            <div>
                <dl>
                    <?php if($user->checkAttrib('products')):?>
                        <?php foreach($user->products as $product):?>
                            <dt id="<?=\lithium\util\Inflector::camelize($product->name);?>"
                                onclick="toggleDesc(this);">&bull; <?=$product->name?></dt>
                            <dd class="hidden" id="<?=\lithium\util\Inflector::camelize($product->name).'Desc';?>">
                                Price : <?=$product->price?></br>
                                Description : <?=$product->description?></br>
                                Tags : <?=implode(",", \lithium\data\collection\DocumentArray::toArray($product->tags));?></br>
                            </dd>
                        <?php endforeach;?>
                    <?php else:?>
                        This User is not have any products yet.
                    <?php endif;?>
                </dl>
            </div>
            <hr>    
            <?=$this->html->link('Add Product', array('Users::addProduct', 'id' => $user->_id), array('class' => 'add'));?>
        </fieldset>
    </fieldset>

    <?//=$this->html->image("/users/view/{$user->_id}.jpg", array('alt' => $user->name)); ?>

<!--    <div class="lithium-stack-trace">-->
        <pre>
        <?=print_r($user);?>
        </pre>
<!--    <div class="lithium-stack-trace">-->
</div>