<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in BEFORE setting a default
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: ../staff/staff_login.php');
    exit();
}


include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';




?>
<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $action = $_POST['action'];
    $date = $_POST['evaluation_date'];
    // format date to yyyy-mm-dd for database storage
    $date = explode('/', $date);
    if (count($date) === 3) {
        $date = $date[2] . '-' . $date[1] . '-' . $date[0];
    } else {
        $date = date('Y-m-d'); // Fallback to current date if format is incorrect
    }

    if (empty($_POST['eval_notes']) || $_POST['reserved_price'] <= 0) {
        $_SESSION['error'] = "Invalid evaluation data";
        exit();
    }

    if ($action === 'approved') {
        // Insert evaluation data into evaluated_items table
        $tmt = $conn->prepare("INSERT INTO evaluated_items (item_id, evaluator_id,evaluation_date ,condition_rating, authenticity_status,reserve_price, evaluation_notes,final_decision) VALUES (:item_id, :evaluator_id, :evaluation_date, :condition_rating, :authenticity_status, :reserve_price, :evaluation_notes, :final_decision)");
        $success = $tmt->execute([
            ':item_id' => $item_id,
            ':evaluator_id' => (int) $_SESSION['user_id'],
            ':evaluation_date' => $date,
            ':condition_rating' => $_POST['rating'],
            ':authenticity_status' => $_POST['authenticity'],
            ':reserve_price' => $_POST['reserved_price'],
            ':evaluation_notes' => $_POST['eval_notes'],
            ':final_decision' => $action
        ]);
        if (!$success) {
            $_SESSION['error'] = "Failed to save evaluation data";
            logActivity($conn, $_SESSION['user_id'] ?? null, $_SESSION['username'] ?? 'Unknown', "Failed to save evaluation data for item ID: " . $item_id);
            exit();
        } elseif ($success) {
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Approved item ID: " . $item_id);

            // Update item status to approved in the database
            $stmt = $conn->prepare("UPDATE consigner_items SET item_status = :action WHERE item_id = :item_id");
            $stmt->execute([':action' => $action, ':item_id' => $item_id]);
        }

        $_SESSION['success'] = " Item approved successfully!";


    } elseif ($action === 'rejected') {
        // Insert evaluation details into evaluated_items table with final_decision as 'rejected'
        $tmt = $conn->prepare("INSERT INTO evaluated_items (item_id, evaluator_id,evaluation_date ,condition_rating, authenticity_status,reserve_price, evaluation_notes,final_decision) VALUES (:item_id, :evaluator_id, :evaluation_date, :condition_rating, :authenticity_status, :reserve_price, :evaluation_notes, :final_decision)");
        ;
        $success = $tmt->execute([
            ':item_id' => $item_id,
            ':evaluator_id' => (int) $_SESSION['user_id'],
            ':evaluation_date' => $date,
            ':condition_rating' => $_POST['rating'],
            ':authenticity_status' => $_POST['authenticity'],
            ':reserve_price' => $_POST['reserved_price'],
            ':evaluation_notes' => $_POST['eval_notes'],
            ':final_decision' => $action
        ]);
        if (!$success) {
            $_SESSION['error'] = "Failed to save evaluation data";
            logActivity($conn, $_SESSION['user_id'] ?? null, $_SESSION['username'] ?? 'Unknown', "Failed to save evaluation data for item ID: " . $item_id);
            exit();
        } elseif ($success) {
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Rejected item ID: " . $item_id);

            // Update item status to rejected in the database
            $stmt = $conn->prepare("UPDATE consigner_items SET item_status = 'rejected' WHERE item_id = :item_id");
            $stmt->execute([':item_id' => $item_id]);

            $_SESSION['success'] = "Item rejected.";
        }

    }
    header("Location: staff_dashboard.php");
    exit();
}
?>

<head>
    <title>Approve Item</title>
    <style>
        .outer_container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 640px;
            margin: 0 auto;
            justify-content: center;
        }

        .f_inner_container {
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-left: 120px;
        }

        .new_form_container {
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form_data {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form_data label {
            font-weight: bold;
        }

        .form_data input,
        .form_data textarea,
        .form_data select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }



        .f_inner_container h3 {
            margin: 0;
        }

        .f_inner_container p {
            margin: 5px 0;

        }

        .f_inner_container img {
            margin-top: 10px;
            height: auto;
            width: 100%;
        }

        .form_data .submit {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #1f2933;
            color: #ffffff;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
        }

        .form_data .submit:hover {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #1f2933;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2>Approve Item</h2>
    <!-- Form for approve_item.php goes here -->
    <div class="outer_container">
        <!-- Display item details make items be seen and unseen -->
        <div class="f_inner_container">
            <?php
            // Fetch item details from the database based on item_id
            
            $item_id = $_GET['item_id'] ?? null;
            if ($item_id) {
                $stmt = $conn->prepare("SELECT * FROM consigner_items WHERE item_id = :item_id ");
                $stmt->execute([':item_id' => $item_id]);
                $item = $stmt->fetch();
                if ($item) {
                    echo "<h3>Item ID: " . htmlspecialchars($item['item_id']) . "</h3>";
                    echo "<h3>Item Name: " . htmlspecialchars($item['item_name']) . "</h3>";
                    echo "<p>Description: " . htmlspecialchars($item['item_description']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($item['item_category']) . "</p>";
                    echo "<p>Condition: " . htmlspecialchars($item['item_condition']) . "</p>";
                    if ($item['image_path']) {
                        echo '<img src="' . htmlspecialchars($item['image_path']) . '" alt="Item Image" style="max-width:300px;">';
                    } else {
                        echo "<p>No image available.</p>";
                    }
                } else {
                    echo "<p>Item not found.</p>";
                }
            } else {
                echo "<p>No item ID provided.</p>";
            }
            ?>
        </div>
        <div class="new_form_container">
            <p style="text-align: center; font-weight: 200;"><i>Please review the item details below and approve or
                    reject the item.</i></p>
            <form action="" method="post" class="form_data" onsubmit="return validateEvaluation()">
                <label for="item_id">Item ID:</label>
                <!--display fetched item_id and make it read-only-->
                <input type="text" id="item_id" name="item_id"
                    style="border: 1px solid #030303; background-color: #838383; cursor: not-allowed;"
                    value="<?php echo htmlspecialchars($item['item_id'] ?? ''); ?>" >

                <label for="item_name">Item Name:</label>
                <input type="text" id="item_name" name="item_name"
                    style="border: 1px solid #030303; background-color: #838383; cursor: not-allowed;"
                    value="<?php echo htmlspecialchars($item['item_name'] ?? ''); ?>" >

                <label for="eval_notes">Evaluation notes:</label>
                <textarea id="eval_notes" name="eval_notes" ></textarea>

                <label for="reserved_price">Reserved Price:</label>
                <input type="text" id="reserved_price" name="reserved_price" >

                <label for="evaluation_date">Evaluation Date:</label>
                <input type="text" id="evaluation_date" name="evaluation_date" placeholder="dd/mm/yyyy">

                <label for="rating">Rating:</label>
                <select name="rating" id="rating" required>
                    <option value="">-- Select Rating --</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
                    <option value="poor">Poor</option>
                </select>

                <label for="authenticity">Authenticity:</label>
                <select name="authenticity" id="authenticity" required>
                    <option value="">-- Select Authenticity --</option>
                    <option value="authentic">Authentic</option>
                    <option value="replica">Replica</option>
                    <option value="questionable">Questionable</option>
                </select>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="action" value="approved" class="submit">Approve Item</button>
                    <button type="submit" name="action" value="rejected" class="submit">Reject Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Make the form read-only for review purposes - later will apply DRY principle
        document.getElementById('item_id').readOnly = true;
        document.getElementById('item_name').readOnly = true;



        // MAIN CONTROLLER

        function validateEvaluation() {

            if (!validateNotes()) return false;
            if (!validatePrice()) return false;
            if (!validateDate()) return false;
            if (!validateRating()) return false;
            if (!validateAuthenticity()) return false;

            return true;
        }

        // Evaluation Notes
        function validateNotes() {

            var notes = document.getElementById("eval_notes").value.trim();

            if (notes.length == 0) {
                alert("Evaluation notes are required");
                document.getElementById("eval_notes").focus();
                return false;
            }

            if (notes.length < 10) {
                alert("Evaluation notes must be at least 10 characters");
                document.getElementById("eval_notes").focus();
                return false;
            }

            return true;
        }

        // Reserved Price
        function validatePrice() {

            var price = document.getElementById("reserved_price").value;

            if (price.length == 0 || isNaN(price)) {
                alert("Reserved price must be a valid number");
                document.getElementById("reserved_price").focus();
                return false;
            }

            if (parseFloat(price) <= 0) {
                alert("Reserved price must be greater than 0");
                document.getElementById("reserved_price").focus();
                return false;
            }

            return true;
        }

        // Date Validation (dd/mm/yyyy)
        function validateDate() {

            var date = document.getElementById("evaluation_date").value;

            if (date.length == 0) {
                alert("Evaluation date is required");
                document.getElementById("evaluation_date").focus();
                return false;
            }

            if (date.indexOf("/") == -1) {
                alert("Date must be in format dd/mm/yyyy");
                return false;
            }

            var parts = date.split("/");

            if (parts.length != 3) {
                alert("Invalid date format");
                return false;
            }

            if (isNaN(parts[0]) || isNaN(parts[1]) || isNaN(parts[2])) {
                alert("Date must contain only numbers");
                return false;
            }

            var day = parseInt(parts[0]);
            var month = parseInt(parts[1]);
            var year = parseInt(parts[2]);

            if (day < 1 || day > 31) {
                alert("Invalid day");
                return false;
            }

            if (month < 1 || month > 12) {
                alert("Invalid month");
                return false;
            }
            //ensure date is not in the future
            var today = new Date();
            var evalDate = new Date(year, month - 1, day);
            if (evalDate > today) {
                alert("Evaluation date cannot be in the future");
                return false;
            }

            return true;
        }

        //Rating
        function validateRating() {

            var index = document.getElementById("rating").selectedIndex;

            if (index == 0) {
                alert("Please select a rating");
                return false;
            }

            return true;
        }

        //Authenticity
        function validateAuthenticity() {

            var index = document.getElementById("authenticity").selectedIndex;

            if (index == 0) {
                alert("Please select authenticity status");
                return false;
            }

            return true;
        }

    </script>
</body>
<?php include __DIR__ . '/../footer.php';
?>