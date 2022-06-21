<?php
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>    
    <!-- Custom fonts for this template-->
    <link href="/ext/awesome/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="/ext/startbootstrap-sb-admin/css/sb-admin-2.min.css" rel="stylesheet">
    <?php $this->head() ?> 
</head>
<?php $this->registerCSS(<<<CSS
    .nav-item > a.active {
        font-weight: bolder !important;
    }
CSS); ?>
<body id="page-top">
    <?php $this->beginBody() ?>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php echo Nav::widget([
            'items' => [
                '<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">Тестирование <sup>Портал УФНС</sup></div>
                </a>',
                '<hr class="sidebar-divider my-0">',
                ['label' => '<i class="fas fa-external-link-alt"></i> На Портал', 'url' => ['/']],
                ['label' => '<i class="fas fa-home"></i> Главная', 'url' => ['/test/test/index']],
                ['label' => '<i class="fas fa-list-alt"></i> Мои результаты', 'url' => ['/test/result/index']],       
                ['label' => '<i class="fas fa-chart-pie"></i> Аналитика', 'url' => ['/test/analytics']],                                 
                '<hr class="sidebar-divider my-0">',
                ['label' => '<i class="fas fa-user"></i> Права доступа', 'url' => ['/test/access/index'], 'visible' => Yii::$app->user->can('admin')],
            ],
            'options' => ['class' => 'navbar-nav bg-gradient-primary sidebar sidebar-dark accordion'],
            'encodeLabels' => false,
        ]) ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
            
                <!-- Begin Page Content -->
                <div class="container-fluid mt-3">

                    <?= Breadcrumbs::widget([
                        'homeLink' => ['label' => 'Главная', 'url' => ['/test']],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],                        
                    ]) ?>
                    <?= $content ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>УФНС России по Ханты-Мансийскому автономному округу - Югре, <?= date('Y') ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Modal-->
    <div class="modal fade" id="modal-dialog" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 95%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap core JavaScript-->
    <script src="/ext/jquery/jquery.min.js"></script>
    <script src="/ext/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/ext/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/ext/startbootstrap-sb-admin/js/sb-admin-2.min.js"></script>
    
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>