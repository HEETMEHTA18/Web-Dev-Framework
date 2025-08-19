<?php
// game.php
session_start();

// Guard: must have ?name=...
if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die("Name parameter missing");
}

// Game setup
$names = array("Rock", "Paper", "Scissors");

function check_result($computer, $human) {
    if ($human == $computer) return "Tie";
    if (($human == 0 && $computer == 2) ||
        ($human == 1 && $computer == 0) ||
        ($human == 2 && $computer == 1)) {
        return "You Win";
    }
    return "You Lose";
}

// Inputs
$human = isset($_POST["human"]) ? (int)$_POST["human"] : -1;
$computer = rand(0, 2);
$result = false;

// Logout -> back to home (per spec)
if (isset($_POST['logout'])) {
    header("Location: index.php");
    exit;
}

// Compute result
if ($human === 3) {
    // Test mode table
    $result = "";
    for ($c = 0; $c < 3; $c++) {
        for ($h = 0; $h < 3; $h++) {
            $r = check_result($c, $h);
            $result .= "Human={$names[$h]}  Computer={$names[$c]}  Result=$r\n";
        }
    }
} elseif ($human >= 0 && $human <= 2) {
    $result = "Your Play={$names[$human]}  Computer Play={$names[$computer]}  Result=" . check_result($computer, $human);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>f6789b89 - Rock Paper Scissors</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; }
    h1, h2 { text-align: center; }
    form { text-align: center; margin-bottom: 16px; }
    select, input[type="submit"] { padding: 8px 10px; }
    pre { background: #f7f7f7; padding: 12px; overflow: auto; }

    /* Animation area */
    .arena {
      margin: 18px auto 10px;
      display: flex;
      justify-content: center;
      gap: 80px;
      align-items: center;
      min-height: 140px;
    }
    .hand {
      font-size: 80px;
      opacity: 0;
      transform: translateY(10px) scale(0.9);
      transition: transform 0.6s ease, opacity 0.6s ease;
    }
    .hand.show {
      opacity: 1;
      transform: translateY(0) scale(1.1);
    }

    /* Optional: little name tags */
    .tag {
      text-align: center;
      font-size: 14px;
      margin-top: 4px;
      color: #555;
    }

    /* Shake before reveal (quick) */
    @keyframes rps-shake {
      0% { transform: translateY(0); }
      25% { transform: translateY(-6px); }
      50% { transform: translateY(0); }
      75% { transform: translateY(-4px); }
      100% { transform: translateY(0); }
    }
    .shake {
      animation: rps-shake 0.45s ease-in-out 2;
    }
  </style>
</head>
<body>
  <h1>Rock Paper Scissors</h1>
  <h2>Welcome: <?php echo htmlspecialchars($_GET["name"]); ?></h2>

  <form method="POST">
    <select name="human" id="humanChoice">
      <option value="-1" <?php echo ($human === -1 ? 'selected' : ''); ?>>Select</option>
      <option value="0">Rock</option>
      <option value="1">Paper</option>
      <option value="2">Scissors</option>
      <option value="3">Test</option>
    </select>
    <input type="submit" value="Play" />
    <input type="submit" name="logout" value="Logout" />
  </form>

  <!-- Animation arena -->
  <div class="arena">
    <div>
      <div id="humanHand" class="hand"> </div>
      <div class="tag">You</div>
    </div>
    <div>
      <div id="computerHand" class="hand"> </div>
      <div class="tag">Computer</div>
    </div>
  </div>

  <?php if ($result !== false): ?>
    <pre><?php echo htmlspecialchars($result); ?></pre>
  <?php endif; ?>

  <script>
    // Map to emoji
    const handIcons = ["✊", "✋", "✌️"];

    // Values from PHP
    const humanChoice = <?php echo json_encode($human); ?>;
    const computerChoice = <?php echo json_encode($computer); ?>;

    const humanHand = document.getElementById("humanHand");
    const computerHand = document.getElementById("computerHand");

    // Only animate on actual play (0..2); skip Test and Select
    if (humanChoice >= 0 && humanChoice <= 2) {
      // Pre-reveal shake
      humanHand.textContent = "❓";
      computerHand.textContent = "❓";
      humanHand.classList.add("shake");
      computerHand.classList.add("shake");

      // Reveal with stagger
      setTimeout(() => {
        humanHand.classList.remove("shake");
        humanHand.textContent = handIcons[humanChoice];
        humanHand.classList.add("show");
      }, 500);

      setTimeout(() => {
        computerHand.classList.remove("shake");
        computerHand.textContent = handIcons[computerChoice];
        computerHand.classList.add("show");
      }, 900);
    } else {
      // Clear on Select/Test
      humanHand.textContent = "";
      computerHand.textContent = "";
      humanHand.classList.remove("show", "shake");
      computerHand.classList.remove("show", "shake");
    }
  </script>
</body>
</html>
