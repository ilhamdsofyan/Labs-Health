<?php
    $this->layout->setSection('title', 'Menu');
    $this->layout->setSection('subtitle', 'Lists');
    $this->layout->setSection('title_icon', 'layers');
?>

<?php $this->layout->startSection('content') ?>

<div class="row">
    <div class="col-lg-4">
        <div class="nav-sticky">
            <?php echo $this->layout->renderPartial('_side_menu') ?>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card light bordered">
            <div class="card-header">
                Group name : <strong><?php echo $menuType ?></strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <strong><?= 'Menu Structure' ?></strong>
                        <p>Seret tiap daftar ke posisi yg anda sukai. Klik simbol 'tambah' dibagian samping untuk melihat pengaturan tambahan.</p>
                    </div>
                    <div class="col-md-4">
                        <?= $this->html->submitButton('<i class=\'fa fa-save me-2\'></i> Save Menu', ['name'=> 'save', 'class' => 'btn btn-primary float-end btn-save-menu']);?>
                    </div>
                </div>

                <div class="card card-icon mb-4">
                    <div class="row g-0">
                        <div class="col-auto card-icon-aside bg-info">
                            <i data-feather="info" class="text-white-50"></i>
                        </div>

                        <div class="col">
                            <div class="card-body py-5">
                                <h5 class="card-title">Tips!</h5>
                                <p class="card-text">
                                    Icon harus berisi berdasarkan <b>Feather Icon</b>. <br/>
                                    Ex. home <i data-feather="home"></i> <br/>
                                    Klik link untuk referensi <a href="https://feathericons.com/" target="_blank">https://feathericons.com/</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <?= form_open('', ['id' => 'formMenu']); ?>
                    <div class="dd">
                        <ol class="dd-list">
                        <?php
                        if (!empty($menus)) {
                            $i = 0;
                            foreach ($menus as $key => $menu) {
                                if ($menu->menu_parent == 0) {
                                    echo $this->layout->renderPartial('_view', [
                                        'menu' => $menu,
                                        'listGroup' => $listGroup,
                                        'childMenus' => $menus,
                                        'menuType' => $menuType,
                                        'i' => $i,
                                    ]);
                                }
                                $i++;
                            }
                        }
                        ?>
                        </ol>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->layout->endSection() ?>
