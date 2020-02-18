<?php

use app\models\Category;
?>

<div class="row">
    <div class="col-md-3">
        <ul class="nav nav-pills nav-stacked hidden-xs" id="tab-big">
            <?php foreach ($categories as $i => $category) : ?>
                <li role="presentation" class="<?= $i == 0 ? 'active' : '' ?>">
                    <a href="#<?= strtolower($category->name) ?>" data-category="<?= $category->name ?>">
                        <?= ucfirst(Category::getTranslateCategoryName($category->name))  ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>

        <ul class="nav nav-tabs hidden-lg" id="tab-small">
            <?php foreach ($categories as $i => $category) : ?>
                <li role="presentation" class="<?= $i == 0 ? 'active' : '' ?>">
                    <a href="#<?= strtolower($category->name) ?>" data-category="<?= $category->name ?>">
                        <?= ucfirst(Category::getTranslateCategoryName($category->name))  ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <?php foreach ($categories as $i => $category) : ?>
                <div class="row tab-pane <?= $i == 0 ? 'active' : '' ?>" id="<?= strtolower($category->name) ?>"></div>
            <?php endforeach ?>
        </div>
    </div>
</div>