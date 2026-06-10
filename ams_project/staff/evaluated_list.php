<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['login_type'] !== 'admin' && $_SESSION['login_type'] !== 'staff')) {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin or staff to access this page.";
    exit();
}

$stmt = mysqli_prepare($conn, "
    SELECT 
        ei.eval_id,
        ei.item_id,
        i.item_name,
        ei.evaluator_id,
        s.username AS evaluator_name,
        ei.evaluation_date,
        ei.condition_rating,
        ei.authenticity_status,
        ei.reserve_price,
        ei.evaluation_notes,
        ei.final_decision,
        ei.created_at

    FROM evaluated_items ei

    LEFT JOIN consigner_items i 
        ON ei.item_id = i.item_id

    LEFT JOIN staff s 
        ON ei.evaluator_id = s.staff_id

    ORDER BY ei.created_at DESC
");

mysqli_stmt_execute($stmt);
$evaluations = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

?>

<head>
    <title>Evaluated Items</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>
    <h2 style="text-align: center;">Evaluated Items List</h2>
        <a href="staff_dashboard.php" style="margin:auto" class="back">
            Back to Dashboard
        </a>
    
        <a href="list_items.php" style="margin:4px auto" class="back">
            To Consigned Items
        </a>

    <div class="outer_container"style="margin-top: 20px;">
        <div class="inner_container">

            <table>
                <thead>
                    <tr>
                        <th>Evaluation ID</th>
                        <th>Item</th>
                        <th>Evaluator</th>
                        <th>Date</th>
                        <th>Condition</th>
                        <th>Authenticity</th>
                        <th>Reserve Price</th>
                        <th>Notes</th>
                        <th>Decision</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($evaluations)): ?>

                        <tr>
                            <td colspan="10">
                                No evaluations found.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($evaluations as $eval): ?>

                            <tr>

                                <td> <?=  ($eval['eval_id']) ?> </td>
                                <td> <?=  ($eval['item_name']) ?> </td>
                                <td> <?=  ($eval['evaluator_name']) ?> </td>
                                <td> <?=  ($eval['evaluation_date']) ?> </td>
                                <td> <?=  ($eval['condition_rating']) ?> </td>
                                <td> <?=  ($eval['authenticity_status']) ?> </td>
                                <td> Ksh <?= number_format($eval['reserve_price'], 2) ?> </td>
                                <td> <?=  ($eval['evaluation_notes']) ?> </td>
                                <td> <?=  ($eval['final_decision']) ?> </td>
                                <td> <?=  ($eval['created_at']) ?> </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>
            </table>

        </div>
    </div>
</body>
<?php include __DIR__ . ("/../footer.php"); ?>