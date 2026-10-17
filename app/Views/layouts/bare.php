<?php

use App\Core\Csrf;
use App\Core\View;

$pageTitle = $pageTitle ?? 'Vipasa Yoga';
$pageCss = $pageCss ?? null;
?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= View::e($pageTitle) ?></title>
<meta name="csrf-token" content="<?= View::e(Csrf::token()) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap">
<link rel="stylesheet" href="<?= View::e(View::asset('/assets/css/design-tokens.css')) ?>">
<link rel="stylesheet" href="<?= View::e(View::asset('/assets/css/base.css')) ?>">
<link rel="stylesheet" href="<?= View::e(View::asset('/assets/css/components.css')) ?>">
<?php if ($pageCss): ?>
<link rel="stylesheet" href="<?= View::e(View::asset('/assets/css/pages/' . $pageCss . '.css')) ?>">
<?php endif; ?>
</head>
<body>
<?= $content ?>
<script type="module" src="<?= View::e(View::asset('/assets/js/app.js')) ?>"></script>
</body>
</html>
