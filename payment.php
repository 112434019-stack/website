<?php

session_start();

if (!isset($_SESSION["member_id"])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "power_gym"
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$member_id = $_SESSION["member_id"];

$message = "";

// Get member details
$stmt = $conn->prepare(
    "SELECT name, email, plan FROM members WHERE id = ?"
);

$stmt->bind_param("i", $member_id);
$stmt->execute();

$result = $stmt->get_result();
$member = $result->fetch_assoc();

$stmt->close();


// Process payment form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $plan = $_POST["plan"];
    $amount = $_POST["amount"];
    $payment_method = $_POST["payment_method"];

    $stmt = $conn->prepare(
        "INSERT INTO payments
        (member_id, plan, amount, payment_method, payment_status)
        VALUES (?, ?, ?, ?, 'Paid')"
    );

    $stmt->bind_param(
        "isds",
        $member_id,
        $plan,
        $amount,
        $payment_method
    );

    if ($stmt->execute()) {
        $message = "Payment successful!";
    } else {
        $message = "Payment failed.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Power Gym - Payment</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f2f2f2;
}

header {
    background: #111;
    color: white;
    padding: 20px;
    text-align: center;
}

header h1 {
    color: #ff3333;
    margin: 0;
}

.container {
    width: 90%;
    max-width: 600px;
    margin: 40px auto;
}

.payment-box {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 3px 10px #ccc;
}

h2 {
    text-align: center;
    color: #d00000;
}

.customer {
    background: #f5f5f5;
    padding: 15px;
    margin: 20px 0;
    border-radius: 8px;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input,
select {
    width: 100%;
    padding: 12px;
    margin-top: 7px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

button {
    width: 100%;
    margin-top: 25px;
    padding: 13px;
    border: none;
    border-radius: 6px;
    background: #d00000;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background: #a00000;
}

.message {
    margin-top: 20px;
    padding: 12px;
    text-align: center;
    background: #d4edda;
    color: #155724;
    border-radius: 6px;
}

.dashboard {
    display: block;
    margin-top: 20px;
    text-align: center;
    text-decoration: none;
    color: #d00000;
}

</style>

</head>

<body>

<header>

<h1>POWER GYM</h1>

<p>Payment</p>

</header>


<div class="container">

<div class="payment-box">

<h2>Make Payment</h2>


<div class="customer">

<p>
<strong>Name:</strong>
<?php echo htmlspecialchars($member["name"]); ?>
</p>

<p>
<strong>Email:</strong>
<?php echo htmlspecialchars($member["email"]); ?>
</p>

</div>


<form method="POST">

<label>Membership Plan</label>

<select name="plan" id="plan" onchange="updateAmount()" required>

<option value="">Select Plan</option>

<option value="Basic - 1 Month" data-price="1000">
Basic - 1 Month - ₹1000
</option>

<option value="Standard - 3 Months" data-price="2500">
Standard - 3 Months - ₹2500
</option>

<option value="Premium - 6 Months" data-price="4500">
Premium - 6 Months - ₹4500
</option>

<option value="Annual - 12 Months" data-price="8000">
Annual - 12 Months - ₹8000
</option>

</select>


<label>Amount</label>

<input
    type="number"
    name="amount"
    id="amount"
    readonly
    required
>


<label>Payment Method</label>

<select name="payment_method" required>

<option value="">Select Payment Method</option>

<option value="UPI">UPI</option>

<option value="Debit Card">Debit Card</option>

<option value="Credit Card">Credit Card</option>

<option value="Cash">Cash</option>

</select>


<button type="submit">
Pay Now
</button>

</form>


<?php if ($message != "") { ?>

<div class="message">

<?php echo $message; ?>

</div>

<?php } ?>


<a href="dashboard.php" class="dashboard">
← Back to Dashboard
</a>

</div>

</div>


<script>

function updateAmount() {

    let plan = document.getElementById("plan");

    let selected = plan.options[plan.selectedIndex];

    let price = selected.getAttribute("data-price");

    document.getElementById("amount").value = price || "";

}

</script>

</body>

</html>