<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Test</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h2>Razorpay Test</h2>
    <button id="payButton">Pay ₹100</button>

    <script>
        $(document).ready(function(){
            $('#payButton').click(function(){
                var options = {
                    "key": "rzp_test_s8u2UQ54kE7TBA", // Your key ID
                    "amount": "10000", // 100 rupees in paise
                    "currency": "INR",
                    "name": "Beauty Blend",
                    "description": "Test Transaction",
                    "image": "https://example.com/your_logo.png", // Optional
                    "prefill": {
                        "name": "Test Customer",
                        "email": "customer@example.com",
                        "contact": "9999999999"
                    },
                    "theme": {
                        "color": "#F96D00"
                    },
                    "handler": function (response){
                        alert("Payment ID: " + response.razorpay_payment_id);
                        console.log(response);
                    }
                };
                
                var rzp = new Razorpay(options);
                rzp.on('payment.failed', function (response){
                    alert("Payment Failed. Error: " + response.error.description);
                    console.log(response.error);
                });
                rzp.open();
            });
        });
    </script>
</body>
</html>