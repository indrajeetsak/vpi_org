<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4">
        <?= $this->renderSection('content') ?>
    </div>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
