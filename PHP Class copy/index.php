<?php
    require "./config.php";
    //creating object of db class from config file
    $obj = new db();
    //calling viewData method
    $data = $obj->viewData();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD </title>
</head>
<body>
    <h1>Data</h1>
    
    <h2>Insert Data</h2>
    <form action="" method="post">
        <input type="hidden" name="action" value="insert">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="mobile" placeholder="Mobile" required>
        <button type="submit">Insert</button>
    </form>
    <?php 
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert'){
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
            if ($name !== '' && $email !== '' && $mobile !== '') {
                $res = $obj->insertData($name, $email, $mobile);
                if ($res === false) {
                    // insertion failed
                    $err = isset($obj->lastError) ? urlencode($obj->lastError) : 'unknown';
                    header("Location: " . $_SERVER['PHP_SELF'] . "?inserted=0&err={$err}");
                    exit;
                } else {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?inserted=1");
                    exit;
                }
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?inserted=0&err=invalid_input");
                exit;
            }
        }
    ?>
    <h2>Delete Data</h2>
    <form action="" method="post">
        <input type="hidden" name="action" value="delete">
        <input type="number" name="id" placeholder="ID to delete" required>
        <button type="submit">Delete</button>
    </form>
    <?php 
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])){
            $id = intval($_POST['id']);
            try {
                $deleted = $obj->deleteData($id);
                if ($deleted > 0) {
                    // success
                    header("Location: ".$_SERVER['PHP_SELF'].'?deleted=1');
                    exit;
                } else {
                    header("Location: ".$_SERVER['PHP_SELF'].'?deleted=0');
                    exit;
                }
            } catch (Exception $e) {
                // redirect with error
                header("Location: ".$_SERVER['PHP_SELF'].'?deleted=error');
                exit;
            }
        }
        // Show delete status messages
        if (isset($_GET['deleted'])) {
            if ($_GET['deleted'] === '1') {
                echo "<p style='color:green;'>Record deleted successfully.</p>";
            } elseif ($_GET['deleted'] === '0') {
                echo "<p style='color:orange;'>No record found with that ID.</p>";
            } else {
                echo "<p style='color:red;'>An error occurred while deleting the record.</p>";
            }
        }
    ?>

    <h2>All Data</h2>
    <?php if (!empty($obj->lastError)) : ?>
        <p style="color:red;">Database error: <?php echo htmlspecialchars($obj->lastError); ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['inserted'])) {
        if ($_GET['inserted'] === '1') {
            echo "<p style='color:green;'>Record inserted successfully.</p>";
        } else {
            $err = isset($_GET['err']) ? urldecode($_GET['err']) : 'unknown error';
            echo "<p style='color:red;'>Insert failed: " . htmlspecialchars($err) . "</p>";
        }
    }
    ?>
    <?php if (empty($data)) : ?>
        <p>No records found.</p>
    <?php else: ?>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Action</th>
        </tr>
        <?php foreach($data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($row['mobile'] ?? ''); ?></td>
            <td>
                <form method="post" style="display:inline;" onsubmit="return confirm('Delete ID <?php echo $row['id']; ?>?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</body>
</html>
