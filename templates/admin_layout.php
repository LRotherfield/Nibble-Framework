<!doctype html>
<html>
  <head>
    <title><?php echo $page_title ?></title>
    <?php echo $this->renderMeta() ?>
    <meta name="google-site-verification" content="Uvdp0pOSmJhWcoMqL_AegxnViphnU3GSL2xl0eTBzok" />
    <link href='http://fonts.googleapis.com/css?family=Molengo&amp;subset=latin' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Reenie+Beanie&amp;subset=latin' rel='stylesheet' type='text/css' />
    <?php echo $this->renderScripts() ?>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="head">
      <header>
        <h1><?php echo SITE_NAME ?></h1>
      </header>
      <nav>
        <ul>
          <?php echo $this->navigation->printNavTier(0) ?>
        </ul>
        <div class="cb"></div>
      </nav>

    </div>
    <div class="wrap rounded">

      <div class="w-large float">

        <section>
          <?php $this->renderView(); ?>
        </section>

        <div class="cb"></div>
      </div>
      <div class="float w-small">
        <?php if($this->navigation->printNavTier(1)): ?>
        <h2>Navigation</h2>
        <nav class="nav2">
        <ul>
          <?php echo $this->navigation->printNavTier(1) ?>
        </ul>
        </nav>
        <?php endif ?>
        <div class="cb"></div>
        <div id="extra-html"></div>
      </div>
      <div class="cb"></div>
    </div>
  <footer>
    <div id="working" title="">
      <img src="/graphic/loading.gif" alt="loading" />
    </div>

    <!-- Footer -->
  </footer>
  <?php $this->flash() ?>

</body>
</html>

