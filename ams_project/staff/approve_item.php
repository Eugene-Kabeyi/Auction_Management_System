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




?>

<head>
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
            include __DIR__ . '/../config.php';
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
            <p style="text-align: center; font-weight: 200;"><i>Please review the item details below and approve or reject the item.</i></p>
            <form action="" method="post" class="form_data">
                <label for="item_id">Item ID:</label>
                <!--display fetched item_id and make it read-only-->
                <input type="text" id="item_id" name="item_id"
                    style="border: 1px solid #030303; background-color: #838383; cursor: not-allowed;"
                    value="<?php echo htmlspecialchars($item['item_id'] ?? ''); ?>" readonly>

                <label for="item_name">Item Name:</label>
                <input type="text" id="item_name" name="item_name"
                    style="border: 1px solid #030303; background-color: #838383; cursor: not-allowed;"
                    value="<?php echo htmlspecialchars($item['item_name'] ?? ''); ?>" readonly>

                <label for="eval_notes">Evaluation notes:</label>
                <textarea id="eval_notes" name="eval_notes" required></textarea>

                <label for="reserved_price">Reserved Price:</label>
                <input type="number" id="reserved_price" name="reserved_price" required>

                <?php include __DIR__ . '/../datepicker.php'; ?>

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
    </script>
</body>
<?php include __DIR__ . '/../footer.php';
?>
<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $action = $_POST['action'];
    $date = $_POST['date_hidden_input'];

    if ($action === 'approved') {
        // Insert evaluation data into evaluated_items table
        $tmt = $conn->prepare("INSERT INTO evaluated_items (item_id, evaluator_id,evaluation_date ,condition_rating, authenticity_status,reserve_price, evaluation_notes,final_decision) VALUES (:item_id, :evaluator_id, :evaluation_date, :condition_rating, :authenticity_status, :reserve_price, :evaluation_notes, :final_decision)");
        $tmt->execute([
            ':item_id' => $item_id,
            ':evaluator_id' => (int) $_SESSION['user_id'],
            ':evaluation_date' => $date,
            ':condition_rating' => $_POST['rating'],
            ':authenticity_status' => $_POST['authenticity'],
            ':reserve_price' => $_POST['reserved_price'],
            ':evaluation_notes' => $_POST['eval_notes'],
            ':final_decision' => $action
        ]);

        // Update item status to approved in the database
        $stmt = $conn->prepare("UPDATE consigner_items SET item_status = :action WHERE item_id = :item_id");
        $stmt->execute([':action' => $action, ':item_id' => $item_id]);
        echo "✅ Item approved successfully!";


    } elseif ($action === 'rejected') {
        // Insert evaluation details into evaluated_items table with final_decision as 'rejected'
        $tmt = $conn->prepare("INSERT INTO evaluated_items (item_id, evaluator_id,evaluation_date ,condition_rating, authenticity_status,reserve_price, evaluation_notes,final_decision) VALUES (:item_id, :evaluator_id, :evaluation_date, :condition_rating, :authenticity_status, :reserve_price, :evaluation_notes, :final_decision)");
        ;
        $tmt->execute([
            ':item_id' => $item_id,
            ':evaluator_id' => (int) $_SESSION['user_id'],
            ':evaluation_date' => $date,
            ':condition_rating' => $_POST['rating'],
            ':authenticity_status' => $_POST['authenticity'],
            ':reserve_price' => $_POST['reserved_price'],
            ':evaluation_notes' => $_POST['eval_notes'],
            ':final_decision' => $action
        ]);
        // Update item status to rejected in the database
        $stmt = $conn->prepare("UPDATE consigner_items SET item_status = 'rejected' WHERE item_id = :item_id");
        $stmt->execute([':item_id' => $item_id]);
        echo "❌ Item rejected.";

    }
}
?>