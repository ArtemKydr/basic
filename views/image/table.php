<?php /**
 *  * @var \app\models\image\image[] $list
 */ ?>

<?php foreach ($list as $name):?>
    <?= $name->name ?>
    <?= $name->created_at?>
<?php endforeach;?>
