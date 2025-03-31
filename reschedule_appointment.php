<?php
include('connect.php');

if (!empty($_SESSION["email"])) {
    $email = $_SESSION["email"];
    $sql = "SELECT u.name FROM tbl_user AS u JOIN tbl_login AS l ON u.user_id = l.user_id WHERE l.email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
}

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];
    $query = "SELECT * FROM tbl_appointment WHERE appointment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $appointment = $stmt->get_result()->fetch_assoc();
    $selected_time = $appointment['time'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_date = $_POST['date'];
    $new_time = $_POST['appointment_time'];
    
    $update_query = "UPDATE tbl_appointment SET date = ?, time = ? WHERE appointment_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $new_date, $new_time, $appointment_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Appointment rescheduled successfully!'); window.location.href='viewappointments.php';</script>";
    } else {
        echo "<script>alert('Error rescheduling appointment.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>BeautyBlend - Reschedule Appointment</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Reschedule Appointment</h2>
        <div class="billing-form ftco-bg-dark p-3 p-md-5">
            <form method="post" id="rescheduleForm">
                <div class="form-group">
                    <label for="date">New Date</label>
                    <input type="text" id="appointment_date" name="date" class="form-control datepicker" value="<?php echo $appointment['date']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="appointment_time">New Time</label>
                    <select id="appointment_time" name="appointment_time" class="form-control" required>
                        <option value="">Select Time</option>
                        <?php 
                        $times = [
                            "04:00 AM", "05:00 AM", "06:00 AM", "07:00 AM", "08:00 AM", "09:00 AM", "10:00 AM",
                            "11:00 AM", "12:00 PM", "01:00 PM", "02:00 PM", "03:00 PM", "04:00 PM", "05:00 PM",
                            "06:00 PM", "07:00 PM", "08:00 PM", "09:00 PM"
                        ];
                        foreach ($times as $time) {
                            $selected = ($time == $selected_time) ? 'selected' : '';
                            echo "<option value=\"$time\" $selected>$time</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Reschedule</button>
                    <a href="viewappointments.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function () {
    // Initialize Bootstrap Datepicker with 1-year limit
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
        startDate: new Date(), // Start from today
        endDate: "+1y", // Restrict to 1 year in advance
        daysOfWeekDisabled: [0] // Disable Sundays if needed
    });

    // Custom validation to check if the selected date is within 1 year
    $.validator.addMethod("validDate", function (value, element) {
        let selectedDate = new Date(value);
        let today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time for accurate comparison

        let oneYearLater = new Date();
        oneYearLater.setFullYear(today.getFullYear() + 1); // One year from today

        return selectedDate >= today && selectedDate <= oneYearLater;
    }, "Please select a date within the next year.");

    // Custom validation for time slot availability
    $.validator.addMethod("timeSlotAvailable", function (value, element) {
        let isValid = false;
        let date = $("#appointment_date").val();

        if (date && value) {
            $.ajax({
                url: 'check_availability.php',
                type: 'POST',
                data: { date: date, time: value },
                dataType: "json",
                async: false, // Synchronous request for validation
                success: function (response) {
                    isValid = response.available;
                },
                error: function () {
                    isValid = false; // If there's an error, assume slot is unavailable
                }
            });
        }
        return isValid;
    }, "The selected time slot is not available. Please choose a different time.");

    // Form validation rules
    $("#rescheduleForm").validate({
        rules: {
            date: {
                required: true,
                validDate: true
            },
            appointment_time: {
                required: true,
                timeSlotAvailable: true
            }
        },
        messages: {
            date: {
                required: "Please select a new date.",
                validDate: "Please select a date within the next year."
            },
            appointment_time: {
                required: "Please select a new appointment time."
            }
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
            error.addClass("text-danger"); // Bootstrap error styling
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        submitHandler: function (form) {
            // Show loading indicator and disable submit button
            $(form).find('button[type="submit"]')
                .prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Rescheduling...');
            
            form.submit();
        }
    });

    // Trigger real-time validation for time slot availability
    $("#appointment_time, #appointment_date").on('change', function () {
        $("#rescheduleForm").valid(); // Validate the entire form again
    });
});
</script>

</body>
</html>
