<?php

use Kirby\Cms\Url;

$assetsDir = Url::path(env('VITE_ASSETS_DIR'), true, true);
$apiLocation = Url::path(env('CONTENT_API_SLUG'), true);

$assetPath = function (string $pattern) use ($assetsDir) {
  $filename = glob(kirby()->roots()->index() . $assetsDir . $pattern)[0] ?? null;
  if ($filename === null) throw new Exception('No production assets found. You have to bundle the app first. Run `npm run build`.');
  return $assetsDir . basename($filename);
};

$modulePreloadLink = function (string $name) use ($assetsDir) {
  $filename = glob(kirby()->roots()->index() . $assetsDir . ucfirst($name) . '.*.js')[0] ?? null;
  if ($filename === null) return;
  return '<link rel="modulepreload" href="' . $assetsDir . basename($filename) . '">';
};

?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php snippet('meta', compact('page', 'site')) ?>

  <link rel="preload" href="<?= $apiLocation . '/home.json' ?>" as="fetch" crossorigin>
  <?= $modulePreloadLink($page->intendedTemplate()->name()) ?>
  <link rel="stylesheet" href="<?= $assetPath('style.*.css') ?>">

</head>
<body>

  <div id="app"></div>
  <script type="module" src="<?= $assetPath('index.*.js') ?>"></script>

</body>
</html>