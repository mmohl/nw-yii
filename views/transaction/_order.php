<?php

use app\models\Category;
?>

<div class="row">
    <button class="btn btn-primary pull-right" type="button" id="btn-trigger-modal-cart">Keranjang <span id="total-items">0</span></button>
</div>

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

<!-- modal order -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-item-order" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="" alt="" class="img-responsive" id="modal-menu-item-image">
                    </div>
                    <div class="col-md-6">
                        <div class="caption" style="overflow: auto; max-height: 10em; min-height: 8.5em;">
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sapiente beatae aperiam necessitatibus id. Saepe deserunt labore repellat porro, iste reprehenderit nam vero recusandae, eum optio ad beatae enim aperiam error.
                        </div>
                        <div class="input-group" style="margin: 1em 5.25em;">
                            <span class="input-group-btn">
                                <button class="btn btn-default counter" type="button" data-type="minus">
                                    <span class="glyphicon glyphicon-minus"></span>
                                </button>
                            </span>
                            <input readonly type="text" class="form-control" value="1" id="total-will-be-ordered">
                            <span class="input-group-btn">
                                <button class="btn btn-default counter" type="button" data-type="add">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-trigger-order-save">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batalkan</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- modal cart -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-cart" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Keranjang Pesanan</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <form class="form-inline">
                    <div class="form-group pull-left">
                        <label for="input-ordered-by">Atas Nama</label>
                        <input autocomplete="off" id="input-ordered-by" type="text" class="form-control" placeholder="Masukan nama pemesan">
                    </div>
                </form>
                <button type="button" class="btn btn-primary" id="btn-trigger-order-create" disabled>Pesan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>