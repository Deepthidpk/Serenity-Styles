<?php
include 'connect.php'; // Include your database connection

if (isset($_GET['id']) && isset($_GET['status'])) {
    $appointment_id = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $message = "";

    $sql = "UPDATE tbl_appointment SET status = '$status' WHERE appointment_id = $appointment_id";
    if ($conn->query($sql) === TRUE) {
        $sql1 = "SELECT user_id, service_id, time, date FROM tbl_appointment WHERE appointment_id = $appointment_id";
        $result = $conn->query($sql1);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $service_id = intval($row['service_id']);
            $user_id = intval($row['user_id']);

            // Get service name
            $sql2 = "SELECT service_name FROM tbl_services WHERE service_id = $service_id AND status = 'active'";
            $result1 = $conn->query($sql2);
            if ($result1 && $result1->num_rows > 0) {
                $col = $result1->fetch_assoc();
                $service_name = $col['service_name'];

                // Get user email
                $sql3 = "SELECT email FROM tbl_login WHERE user_id = $user_id AND status = 'Active'";
                $result2 = $conn->query($sql3);
                if ($result2 && $result2->num_rows > 0) {
                    $row1 = $result2->fetch_assoc();
                    $email = $row1['email'];

                    // Based on status
                    if ($status === 'Approved') {
                        require("send_approval.php");
                        smtp_mailer($email, $row['time'], $row['date'], $service_name);
                        $message = "Appointment Approved Successfully.";
                    } else if ($status === 'Rejected') {
                        require("send_rejection.php");
                        smtp_rejection_mailer($email, $row['time'], $row['date'], $service_name);
                        $message = "Appointment Rejected Successfully.";
                    } else if ($status === 'Cancelled') {
                        require("send_cancellation.php");

                        $sql4 = "SELECT amount FROM tbl_service_payment WHERE appointment_id = $appointment_id";
                        $result4 = $conn->query($sql4);

                        if ($result4 && $result4->num_rows > 0) {
                            $row4 = $result4->fetch_assoc();
                            $amount = $row4['amount'];

                            $sql5 = "INSERT INTO tbl_refund(user_id, appointment_id, amount, status) VALUES ('$user_id','$appointment_id','$amount','Success')";
                            if ($conn->query($sql5)) {
                                smtp_cancellation_mailer($email, $row['time'], $row['date'], $service_name);
                                $message = "Appointment Cancelled and Refund Processed Successfully.";
                            } else {
                                $message = "Appointment Cancelled. Refund failed to process.";
                            }
                        } else {
                            smtp_cancellation_mailer($email, $row['time'], $row['date'], $service_name);
                            $message = "Appointment Cancelled Successfully. No payment found for refund.";
                        }
                    }
                }
            }
        }

        // Redirect with message as GET parameter
        header("Location: manage_appointment.php?msg=" . urlencode($message));
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>