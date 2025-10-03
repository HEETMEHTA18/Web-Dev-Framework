<?php
$apiKey = 'DUMMY-JOKES-KEY-12345';
$apiUrl = 'https://official-joke-api.appspot.com/jokes/ten';


$allowed = [1,3,5,10];
$count = 10;
if (isset($_GET['count'])) {
  $requested = intval($_GET['count']);
  if (in_array($requested, $allowed, true)) {
    $count = $requested;
  }
}

$options = [
    'http' => [
        'method' => 'GET',
        'header' => "Accept: application/json\r\n" .
                    "X-API-KEY: {$apiKey}\r\n",
        'timeout' => 5
    ]
];

$context = stream_context_create($options);
$result = @file_get_contents($apiUrl, false, $context);

if ($result === false) {
  $jokes = [];
  $error = 'Could not fetch jokes from remote API.';
} else {
  $jokes = json_decode($result, true);
  if (!is_array($jokes)) {
    $jokes = [];
    $error = 'Invalid API response format.';
  }
}

if (!empty($jokes) && is_array($jokes)) {
  $jokes = array_slice($jokes, 0, $count);
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dummy Jokes API</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 1rem; background:#f5f5f5 }
    .joke { background: white; padding: .75rem; margin: .5rem 0; border-radius:6px }
    .setup { font-weight: bold }
    .punch { color: #333 }
  </style>
</head>
<body>
  <h1>Jokes Json Parsing </h1>
  <form method="get" style="margin-bottom:1rem">
    <label for="count">Number of jokes</label>
    <select id="count" name="count">
      <option value="1" <?php if($count===1) echo 'selected'; ?>>1</option>
      <option value="3" <?php if($count===3) echo 'selected'; ?>>3</option>
      <option value="5" <?php if($count===5) echo 'selected'; ?>>5</option>
      <option value="10" <?php if($count===10) echo 'selected'; ?>>10</option>
    </select>
    <button type="submit">Show</button>
  </form>
  <?php if (!empty($error)): ?>
    <p style="color:red"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <?php if (empty($jokes)): ?>
    <p>No jokes available right now.</p>
  <?php else: ?>
    <div id="jokes">
      <?php foreach ($jokes as $j): ?>
        <div class="joke">
          <div class="setup"><?php echo htmlspecialchars($j['setup'] ?? ''); ?></div>
          <div class="punch"><?php echo htmlspecialchars($j['punchline'] ?? ''); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</body>
</html>
