<?php include __DIR__ . '/../header.php'; ?>

<body>
    <h2>Payments</h2>
    <!-- Payment processing form goes here -->
    <p>Please fill in the form below to make a payment for your won bids.</p>
    <form action="handle_payments.php" method="post">
        <label for="bid_id">Bid ID:</label>
        <input type="text" id="bid_id" name="bid_id" required>
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required>
        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cash">Cash</option>
            <option value="mpesa">M-Pesa</option>
        </select>

        <button type="submit">Make Payment</button>
    </form>

</body>
<?php include __DIR__ . '/../footer.php'; ?>