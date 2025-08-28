<?php
// Simple, sober quotes viewer

$defaultLimit = 5;
$maxLimit = 20; // safety cap

$limit = $defaultLimit;
if (isset($_GET['limit'])) {
    $limit = intval($_GET['limit']);
}
if ($limit < 1) { $limit = 1; }
if ($limit > $maxLimit) { $limit = $maxLimit; }

// Try fetching quotes from dummyjson.com, fall back to a small local set
$quotes = [];
$json = @file_get_contents('https://dummyjson.com/quotes');
if ($json !== false) {
    $data = json_decode($json, true);
    if (isset($data['quotes']) && is_array($data['quotes'])) {
        $quotes = $data['quotes'];
    }
}

if (empty($quotes)) {
    // Fallback sample quotes
    $quotes = [
        ['quote' => 'Be yourself; everyone else is already taken.', 'author' => 'Oscar Wilde'],
        ['quote' => 'So many books, so little time.', 'author' => 'Frank Zappa'],
        ['quote' => 'Be the change that you wish to see in the world.', 'author' => 'Mahatma Gandhi'],
        ['quote' => 'If you tell the truth, you don\'t have to remember anything.', 'author' => 'Mark Twain'],
        ['quote' => 'In three words I can sum up everything I\'ve learned about life: it goes on.', 'author' => 'Robert Frost']
    ];
}

$limit = min($limit, count($quotes));
$display = array_slice($quotes, 0, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quotes</title>
    <style>
        /* Simple, sober styling */
        :root{--bg:#f4f5f6;--card:#ffffff;--text:#222;--muted:#666}
        html,body{height:100%;}
        body{font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:var(--bg); color:var(--text); margin:0; display:flex; align-items:flex-start; justify-content:center; padding:32px}
        .wrap{width:100%; max-width:760px}
        header{margin-bottom:18px}
        h1{font-size:18px;margin:0;color:var(--text)}
        form{display:flex; gap:8px; align-items:center; margin:12px 0}
        input[type=number]{width:84px;padding:6px;border:1px solid #d7d7d7;border-radius:4px;background:#fff;color:var(--text)}
        input[type=submit]{padding:6px 10px;border:1px solid #cfcfcf;background:#fff;border-radius:4px;cursor:pointer}
        .card{background:var(--card); border:1px solid #e6e6e6; padding:16px; border-radius:6px}
        ul{list-style:none;padding:0;margin:0}
        li + li{margin-top:12px}
        blockquote{margin:0;padding-left:12px;border-left:3px solid #e0e0e0;color:var(--muted)}
        blockquote p{margin:0;color:var(--text)}
        blockquote footer{margin-top:6px;font-size:13px;color:var(--muted)}
        .meta{font-size:13px;color:var(--muted);margin-top:8px}
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <h1>Quotes</h1>
            <div class="meta">Showing <?php echo $limit; ?> of <?php echo count($quotes); ?> available</div>
        </header>

        <form method="GET" action="">
            <label for="limit">Number:</label>
            <input id="limit" name="limit" type="number" min="1" max="<?php echo count($quotes); ?>" value="<?php echo htmlspecialchars($limit, ENT_QUOTES); ?>">
            <input type="submit" value="Apply">
        </form>

        <div class="card" id="quotes">
            <ul>
                <?php foreach ($display as $q): ?>
                    <li>
                        <blockquote>
                            <p><?php echo htmlspecialchars($q['quote'], ENT_QUOTES); ?></p>
                            <footer><?php echo htmlspecialchars($q['author'] ?? '', ENT_QUOTES); ?></footer>
                        </blockquote>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>