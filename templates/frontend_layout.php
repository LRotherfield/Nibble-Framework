<!doctype html>
<html>
  <head>
    <title><?= $page_title ?></title>
    <?= $this->renderMeta() ?>
    <meta name="google-site-verification" content="Uvdp0pOSmJhWcoMqL_AegxnViphnU3GSL2xl0eTBzok" />
    <link href='http://fonts.googleapis.com/css?family=Molengo&amp;subset=latin' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Reenie+Beanie&amp;subset=latin' rel='stylesheet' type='text/css' />
    <?= $this->renderScripts() ?>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="head">
      <header>
        <h1><?= SITE_NAME ?></h1>
      </header>
      <nav>
        <ul>
          <?= $this->navigation->printNavTier(0) ?>
        </ul>
        <div class="cb"></div>
      </nav>

    </div>
    <div class="wrap rounded">
      <section>
        <? $this->renderView(); ?>
        <div class="cb"></div>
      </section>
      <div class="cb"></div>
    </div>
  <footer>
    <!-- Footer -->
  </footer>
  <? $this->flash() ?>
</body>
</html>

