<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : (isset($this) && isset($this->layout) ? $this->layout->yield('title', 'Error') : 'Error') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>body{font-family:Arial,Helvetica,sans-serif;background:#fff;color:#222;margin:40px;} .container{max-width:700px;margin:0 auto}</style>
</head>
<body>
    <div class="container">
        <h1><?= (isset($status_code) ? intval($status_code) : '') ?> <?= isset($heading) ? htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') : '' ?></h1>
        <div>
            <?= isset($message) ? nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) : '' ?>
        </div>
    </div>
</body>
</html>