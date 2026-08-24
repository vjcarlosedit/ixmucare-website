<?php
include 'main/header.php'; 
session_start();
include 'dbCon.php';
$con = connect();

// Ensure user is logged in
if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
    exit;
}

// Fetch messages
$user_role = $_SESSION['user_role'];
$user_id = $_SESSION['id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['email'];

$query = "SELECT * FROM messages ORDER BY created_at DESC";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="assets/styles/custom.css">
</head>
<body>
    <?php include 'main/top-bar.php'; ?>
    
    <div class="inner-wrapper">
        <?php include 'main/left-bar.php'; ?>

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Mensajes</h2>
                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="index.php">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Mensajes</span></li>
                    </ol>
                    <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
                </div>
            </header>

            <!-- Start: Messages Section -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="text-center mb-4">Todos los Mensajes</h3>

                        <!-- Received Messages -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Recibidos</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Enviado Por</th>
                                                <th>Mennsaje</th>
                                                <th>Dia Creado</th>
                                                <th>Responder</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()) { 
                                                // Display received messages only
                                                if ($row['user_name'] != 'admin') { ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                                        <td>
                                                            <button class="btn btn-primary" onclick="openReplyForm('<?php echo $row['id']; ?>')">
                                                                <i class="fa fa-reply"></i> Responder
                                                            </button>
                                                        </td>
                                                    </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Sent Messages -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Sent Messages</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Enviado Por</th>
                                                <th>Mensaje</th>
                                                <th>Dia Creado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            // Display sent messages (assuming admin is sending replies)
                                            $sentMessagesQuery = "SELECT * FROM messages WHERE user_name = 'admin' ORDER BY created_at DESC";
                                            $sentResult = $con->query($sentMessagesQuery);

                                            while ($row = $sentResult->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Form (Hidden by default) -->
                        <div id="replyForm" class="card mt-4" style="display:none;">
                            <div class="card-header">
                                <h4>Reply to Message</h4>
                            </div>
                            <div class="card-body">
                                <form action="save_reply.php" method="POST">
                                    <input type="hidden" name="parent_message_id" id="parent_message_id">
                                    <div class="form-group">
                                        <label for="reply">Your Reply:</label>
                                        <textarea name="reply" id="reply" class="form-control" rows="4" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-paper-plane"></i> Submit Reply
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="closeReplyForm()">
                                            <i class="fa fa-times"></i> Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End: Messages Section -->
        </section>
    </div>

    <?php include 'main/right-bar.php'; ?>
    <?php include 'main/script-res.php'; ?>
    <script>
        function openReplyForm(messageId) {
            document.getElementById('parent_message_id').value = messageId;
            document.getElementById('replyForm').style.display = 'block';
            document.getElementById('replyForm').scrollIntoView();
        }

        function closeReplyForm() {
            document.getElementById('replyForm').style.display = 'none';
        }
    </script>
</body>
</html>

<?php
$con->close();
?>
