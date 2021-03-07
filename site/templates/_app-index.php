<?php /** @var \Kirby\Cms\Page $page */ ?>
<!DOCTYPE html>
<html lang="<?= kirby()->languageCode() ?? 'en' ?>">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php snippet('meta', compact('page', 'site')) ?>

  <?= vite()->preloadJson($page->uri()) ?>
  <?= vite()->preloadModule($page->intendedTemplate()->name()) ?>

  <?= vite()->client() ?>
  <?= vite()->css() ?>

</head>
<body>

  <div id="app"></div>
  <script id="site-data" type="application/json">
    <?= json_encode(vite()->useSite(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
  </script>

  <?= vite()->js() ?>

</body>
</html>