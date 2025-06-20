<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ប្រព័ន្ធ Check-in Check-out</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        h2 {
            color: #0056b3;
            text-align: center;
        }
        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        form label {
            margin-right: 10px;
            font-weight: bold;
        }
        form input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
            flex-grow: 1;
            max-width: 300px;
        }
        form button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        form button.check-in {
            background-color: #28a745;
            color: white;
        }
        form button.check-out {
            background-color: #dc3545;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ប្រព័ន្ធ Check-in Check-out</h2>

        <?php
        session_start();
        if (isset($_SESSION['message'])) {
            echo '<div class="message ' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <form action="process.php" method="POST">
            <label for="user_name">ឈ្មោះអ្នកប្រើប្រាស់:</label>
            <input type="text" id="user_name" name="user_name" required>
            <button type="submit" name="action" value="check_in" class="check-in">Check-in</button>
            <button type="submit" name="action" value="check_out" class="check-out">Check-out</button>
        </form>

        <h3>ប្រវត្តិ Check-in/Check-out</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ឈ្មោះអ្នកប្រើប្រាស់</th>
                    <th>Check-in Time</th>
                    <th>Check-out Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'db_connection.php';

                $sql = "SELECT * FROM records ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["user_name"] . "</td>";
                        echo "<td>" . ($row["check_in_time"] ? date('Y-m-d H:i:s', strtotime($row["check_in_time"])) : 'N/A') . "</td>";
                        echo "<td>" . ($row["check_out_time"] ? date('Y-m-d H:i:s', strtotime($row["check_out_time"])) : 'N/A') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>មិនទាន់មានទិន្នន័យនៅឡើយទេ។</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
