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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .wrap {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .meta {
            color: #666;
            font-size: 0.9em;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="number"] {
            width: 60px;
            padding: 5px;
            font-size: 1em;
        }
        input[type="submit"] {
            padding: 5px 10px;
            font-size: 1em;
        }
        .card {
            background: #fafafa;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.05);
        }
        blockquote {
            margin: 0 0 10px 0;
            padding-left: 10px;
            border-left: 3px solid #ccc;
        }
        blockquote p {
            margin: 0 0 5px 0;
            font-style: italic;
        }
        blockquote footer {
            text-align: right;
            font-size: 0.9em;
            color: #555;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin-bottom: 15px;
        }
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