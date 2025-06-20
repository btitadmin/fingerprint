<?php
// process.php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $action = $_POST['action'];
    $current_time = date('Y-m-d H:i:s');

    if ($action == 'check_in') {
        // ពិនិត្យមើលថាតើអ្នកប្រើប្រាស់បាន Check-in រួចហើយឬនៅ ហើយមិនទាន់ Check-out
        $sql = "SELECT * FROM records WHERE user_name = ? AND check_out_time IS NULL ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['message'] = "ឈ្មោះអ្នកប្រើប្រាស់នេះបាន Check-in រួចហើយ ហើយមិនទាន់ Check-out ទេ។";
            $_SESSION['message_type'] = "error";
        } else {
            // បញ្ចូលទិន្នន័យ Check-in ថ្មី
            $sql = "INSERT INTO records (user_name, check_in_time) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $user_name, $current_time);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Check-in បានជោគជ័យ!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "មានបញ្ហាក្នុងការ Check-in: " . $stmt->error;
                $_SESSION['message_type'] = "error";
            }
        }
        $stmt->close();

    } elseif ($action == 'check_out') {
        // ស្វែងរក Record ចុងក្រោយបំផុតដែលអ្នកប្រើប្រាស់បាន Check-in ហើយមិនទាន់ Check-out
        $sql = "SELECT id FROM records WHERE user_name = ? AND check_out_time IS NULL ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $record_id = $row['id'];

            // Update Check-out time សម្រាប់ Record នោះ
            $sql = "UPDATE records SET check_out_time = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $current_time, $record_id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Check-out បានជោគជ័យ!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "មានបញ្ហាក្នុងការ Check-out: " . $stmt->error;
                $_SESSION['message_type'] = "error";
            }
        } else {
            $_SESSION['message'] = "មិនមាន Record Check-in ដែលមិនទាន់ Check-out សម្រាប់ឈ្មោះអ្នកប្រើប្រាស់នេះទេ។";
            $_SESSION['message_type'] = "error";
        }
        $stmt->close();
    }
}

$conn->close();
header("Location: index.php"); // បញ្ជូនត្រឡប់ទៅទំព័រដើមវិញ
exit();
?>
