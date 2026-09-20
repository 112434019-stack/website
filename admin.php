
<?php

// ===============================
// PHP + MYSQL CONNECTION
// ===============================

$host = "localhost";
$user = "root";
$password = "";
$database = "power_gym";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


// ===============================
// DELETE MEMBER
// ===============================

if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM members WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->close();

    header("Location: admin.php");
    exit();
}


// ===============================
// ADD MEMBER
// ===============================

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $plan = $_POST["plan"];

    $stmt = $conn->prepare(
        "INSERT INTO members
        (name, email, phone, plan)
        VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssss",
        $name,
        $email,
        $phone,
        $plan
    );

    if ($stmt->execute()) {
        $message = "Member added successfully!";
    } else {
        $message = "Failed to add member.";
    }

    $stmt->close();
}


// ===============================
// GET TOTAL MEMBERS
// ===============================

$totalResult = $conn->query(
    "SELECT COUNT(*) AS total FROM members"
);

$totalMembers = $totalResult
    ->fetch_assoc()["total"];


// ===============================
// GET MEMBERS
// ===============================

$members = $conn->query(
    "SELECT * FROM members ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Power Gym Admin Dashboard</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        /* HEADER */

        header {
            background: #111;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            color: #ff3333;
        }

        /* NAVIGATION */

        nav {
            background: #222;
            padding: 12px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        nav a:hover {
            color: #ff3333;
        }

        /* CONTAINER */

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        /* CARDS */

        .cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 25px;
            flex: 1;
            min-width: 200px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #ccc;
            text-align: center;
        }

        .card h2 {
            color: #d00000;
            font-size: 30px;
        }

        /* FORM */

        .form-box {
            background: white;
            margin-top: 30px;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #ccc;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            border: 1px solid #aaa;
            border-radius: 5px;
        }

        button {
            background: #d00000;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #990000;
        }

        /* TABLE */

        .table-box {
            background: white;
            margin-top: 30px;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #ccc;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #111;
            color: white;
        }

        .delete {
            background: #d00000;
            color: white;
            padding: 7px 12px;
            border-radius: 4px;
            text-decoration: none;
        }

        .delete:hover {
            background: #990000;
        }

        .message {
            color: green;
            font-weight: bold;
        }

        /* MOBILE */

        @media(max-width:600px) {

            nav a {
                display: block;
                margin: 10px;
            }

        }

    </style>

</head>

<body>


<!-- ===============================
     HEADER
     =============================== -->

<header>

    <h1>POWER GYM</h1>

    <p>Admin Dashboard</p>

</header>


<!-- ===============================
     NAVIGATION
     =============================== -->

<nav>

    <a href="admin.php">
        Dashboard
    </a>

    <a href="#members">
        Members
    </a>

    <a href="#add">
        Add Member
    </a>

    <a href="index.php">
        Home
    </a>

</nav>


<div class="container">


    <!-- ===============================
         DASHBOARD CARDS
         =============================== -->

    <div class="cards">

        <div class="card">

            <h3>Total Members</h3>

            <h2>
                <?php echo $totalMembers; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Total Trainers</h3>

            <h2>5</h2>

        </div>


        <div class="card">

            <h3>Active Plans</h3>

            <h2>4</h2>

        </div>


        <div class="card">

            <h3>Monthly Revenue</h3>

            <h2>₹50,000</h2>

        </div>

    </div>


    <!-- ===============================
         ADD MEMBER
         =============================== -->

    <div class="form-box" id="add">

        <h2>Add New Member</h2>

        <?php if ($message != ""): ?>

            <p class="message">
                <?php echo $message; ?>
            </p>

        <?php endif; ?>


        <form method="POST"
              onsubmit="return validateForm()">

            <label>Member Name</label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter member name"
                required
            >


            <label>Email</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter email"
                required
            >


            <label>Phone</label>

            <input
                type="text"
                id="phone"
                name="phone"
                placeholder="Enter phone number"
                required
            >


            <label>Membership Plan</label>

            <select
                id="plan"
                name="plan"
                required
            >

                <option value="">
                    Select Plan
                </option>

                <option>
                    Basic - 1 Month
                </option>

                <option>
                    Standard - 3 Months
                </option>

                <option>
                    Premium - 6 Months
                </option>

                <option>
                    Annual - 1 Year
                </option>

            </select>


            <button type="submit">
                Add Member
            </button>

        </form>

    </div>


    <!-- ===============================
         MEMBERS TABLE
         =============================== -->

    <div class="table-box" id="members">

        <h2>Member Details</h2>

        <table>

            <tr>

                <th>ID</th>

                <th>Name</th>

                <th>Email</th>

                <th>Phone</th>

                <th>Plan</th>

                <th>Action</th>

            </tr>


            <?php while ($row = $members->fetch_assoc()): ?>

            <tr>

                <td>
                    <?php echo $row["id"]; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row["name"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row["email"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row["phone"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row["plan"]); ?>
                </td>

                <td>

                    <a
                        class="delete"
                        href="admin.php?delete=<?php echo $row["id"]; ?>"
                        onclick="return confirm('Delete this member?')"
                    >
                        Delete
                    </a>

                </td>

            </tr>

            <?php endwhile; ?>

        </table>

    </div>

</div>


<!-- ===============================
     JAVASCRIPT
     =============================== -->

<script>

function validateForm() {

    let name =
        document.getElementById("name").value.trim();

    let email =
        document.getElementById("email").value.trim();

    let phone =
        document.getElementById("phone").value.trim();

    if (name === "") {

        alert("Please enter member name.");

        return false;
    }

    if (email === "") {

        alert("Please enter email.");

        return false;
    }

    if (phone === "") {

        alert("Please enter phone number.");

        return false;
    }

    alert("Member details submitted successfully!");

    return true;
}

</script>


</body>

</html>

<?php

$conn->close();

?>
