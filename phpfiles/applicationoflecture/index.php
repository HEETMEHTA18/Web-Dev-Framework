<?php
session_start();

/*
 Simple single-file Lecture Management webapp (SQLite + PDO)
 Features:
 - List lectures
 - Add lecture
 - Edit lecture
 - View lecture details
 - Delete lecture
 Notes:
 - DB file is created automatically at ./data/lectures.sqlite
 - Safe prepared statements used
 - Output escaped with htmlspecialchars()
*/

/* --- Setup DB --- */
$dbDir = __DIR__ . '/data';
$dbFile = $dbDir . '/lectures.sqlite';
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* Create table if missing */
$pdo->exec("CREATE TABLE IF NOT EXISTS lectures (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    speaker TEXT NOT NULL,
    date TEXT NOT NULL,
    location TEXT DEFAULT '',
    description TEXT DEFAULT '',
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
)");

/* --- Helpers --- */
function h($s) { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

/* --- Handle POST actions --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        $title = trim($_POST['title'] ?? '');
        $speaker = trim($_POST['speaker'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($title === '' || $speaker === '' || $date === '') {
            $_SESSION['error'] = 'Title, speaker and date are required.';
            header('Location: ' . ($_POST['redirect'] ?? 'index.php'));
            exit;
        }

        if (!empty($_POST['id'])) {
            // update
            $stmt = $pdo->prepare("UPDATE lectures SET title = ?, speaker = ?, date = ?, location = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $speaker, $date, $location, $description, intval($_POST['id'])]);
            $_SESSION['success'] = 'Lecture updated.';
        } else {
            // insert
            $stmt = $pdo->prepare("INSERT INTO lectures (title, speaker, date, location, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $speaker, $date, $location, $description]);
            $_SESSION['success'] = 'Lecture added.';
        }

        header('Location: index.php');
        exit;
    }

    if (isset($_POST['delete']) && !empty($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM lectures WHERE id = ?");
        $stmt->execute([intval($_POST['id'])]);
        $_SESSION['success'] = 'Lecture deleted.';
        header('Location: index.php');
        exit;
    }
}

/* --- Fetch data for views --- */
if ($action === 'view' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM lectures WHERE id = ?");
    $stmt->execute([$id]);
    $lecture = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM lectures WHERE id = ?");
    $stmt->execute([$id]);
    $lecture = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

if ($action === 'list') {
    $stmt = $pdo->query("SELECT * FROM lectures ORDER BY date DESC, created_at DESC");
    $lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* --- Minimal HTML UI --- */
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Lecture App</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding: 2rem; }
    .card { margin-bottom: 1rem; }
    .small-muted { font-size: .9rem; color: #666; }
  </style>
</head>
<body>
<div class="container">
  <header class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3">Lecture Manager</h1>
    <div>
      <a class="btn btn-primary" href="index.php?action=list">Home</a>
      <a class="btn btn-success" href="index.php?action=add">Add Lecture</a>
    </div>
  </header>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= h($_SESSION['success']); unset($_SESSION['success']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= h($_SESSION['error']); unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <?php if ($action === 'list'): ?>
    <h2 class="h5 mb-3">All Lectures (<?= count($lectures) ?>)</h2>
    <?php if (empty($lectures)): ?>
      <div class="alert alert-info">No lectures yet. Click "Add Lecture" to create one.</div>
    <?php endif; ?>

    <?php foreach ($lectures as $l): ?>
      <div class="card">
        <div class="card-body d-flex justify-content-between align-items-start">
          <div>
            <h5 class="card-title mb-1"><?= h($l['title']) ?></h5>
            <div class="small-muted">By <?= h($l['speaker']) ?> • <?= h($l['date']) ?> <?= $l['location'] ? '• ' . h($l['location']) : '' ?></div>
            <p class="mt-2 mb-0"><?= nl2br(h(substr($l['description'],0,200))) ?><?= strlen($l['description'])>200?'...':'' ?></p>
          </div>
          <div class="text-end">
            <a class="btn btn-outline-primary btn-sm mb-1" href="index.php?action=view&id=<?= $l['id'] ?>">View</a>
            <a class="btn btn-outline-secondary btn-sm mb-1" href="index.php?action=edit&id=<?= $l['id'] ?>">Edit</a>
            <form method="post" style="display:inline" onsubmit="return confirm('Delete this lecture?');">
              <input type="hidden" name="id" value="<?= $l['id'] ?>">
              <button name="delete" class="btn btn-outline-danger btn-sm">Delete</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  <?php elseif ($action === 'view' && !empty($lecture)): ?>

    <article class="card">
      <div class="card-body">
        <h2 class="card-title"><?= h($lecture['title']) ?></h2>
        <div class="small-muted mb-3">By <?= h($lecture['speaker']) ?> • <?= h($lecture['date']) ?> <?= $lecture['location'] ? '• ' . h($lecture['location']) : '' ?></div>
        <div class="mb-3"><?= nl2br(h($lecture['description'])) ?></div>
        <a class="btn btn-secondary" href="index.php?action=edit&id=<?= $lecture['id'] ?>">Edit</a>
        <a class="btn btn-outline-primary" href="index.php">Back</a>
      </div>
    </article>

  <?php elseif ($action === 'add' || ($action === 'edit' && !empty($lecture))): 
      $isEdit = ($action === 'edit');
      $formTitle = $isEdit ? 'Edit Lecture' : 'Add Lecture';
      $vals = $isEdit ? $lecture : ['id'=>'','title'=>'','speaker'=>'','date'=>date('Y-m-d'),'location'=>'','description'=>''];
  ?>
    <div class="card">
      <div class="card-body">
        <h2 class="h5"><?= $formTitle ?></h2>
        <form method="post" class="row g-3">
          <input type="hidden" name="id" value="<?= h($vals['id']) ?>">
          <div class="col-md-6">
            <label class="form-label">Title</label>
            <input required name="title" class="form-control" value="<?= h($vals['title']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Speaker</label>
            <input required name="speaker" class="form-control" value="<?= h($vals['speaker']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Date</label>
            <input required type="date" name="date" class="form-control" value="<?= h($vals['date']) ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Location</label>
            <input name="location" class="form-control" value="<?= h($vals['location']) ?>">
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" rows="5" class="form-control"><?= h($vals['description']) ?></textarea>
          </div>
          <div class="col-12">
            <button name="save" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Create' ?></button>
            <a class="btn btn-link" href="index.php">Cancel</a>
          </div>
        </form>
      </div>
    </div>

  <?php else: ?>
    <div class="alert alert-warning">Item not found or invalid action. <a href="index.php">Back to list</a></div>
  <?php endif; ?>

  <footer class="mt-5 small text-muted">
    Data file: <?= h(str_replace(__DIR__, '', $dbFile)) ?> • <?= date('Y') ?>
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>