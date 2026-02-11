<head>
    <style>
        .datepicker_container {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .datepicker_container .date_input {
            width: 100%;
        }

        .date_input {
            padding: 8px;
            width: 150px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }

        .datepicker_popup {
            position: absolute;
            top: 40px;
            left: 0;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 500px;
            padding: 10px;
        }

        .datepicker_popup button {
            cursor: pointer;
            border: none;
            border-radius: 3px;
            background: transparent;
            font-weight: 500;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .days {
            font-size: 10px;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            gap: 5px;
            text-transform: uppercase;
        }

        .days span {
            padding: 6px 0;
            text-align: center;
        }

        .dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .dates button {
            padding: 6px;
            border: none;
            background-color: #f0f0f0;
            border-radius: 4px;
            cursor: pointer;
        }

        .dates button:hover {
            background-color: #d0d0d0;
        }

        .dates button.today {
            background-color: #ffffff;
            color: #363535;
            border: 1px solid #000000;
        }

        .dates button.selected {
            background-color: #000000;
            color: #fff;
        }

        .datepicker_header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }


        .datepicker_header select,
        .datepicker_header input {
            background: none;
            font-weight: bold;
            text-align: center;
            max-width: 80px;
            padding: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 10px;
        }

        .datepicker_header button {
            background-color: #000000;
            color: #ffffff;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s all ease-out;
            max-width: 100px;
        }

        .datepicker_header button:hover {
            background-color: #000000;
            color: #ffffff;

        }

        .datepicker_footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        .datepicker_footer .apply {
            background-color: #000000;
            color: #ffffff;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s all ease-out;
        }

        .datepicker_footer .close:hover {
            background-color: #000000;
            color: #ffffff;

        }

        .datepicker_footer .apply:hover {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #000000;
        }

        .datepicker_footer .close:hover~.apply {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #000000;
        }
    </style>
</head>
<div class="datepicker_container">
    <input type="text" class="date_input" placeholder="Select Date" readonly>
    <input type="hidden" class="date_hidden_input" name="<?= $input_name ?? 'date' ?>">

    <div class="datepicker_popup" style="display: none;">
        <!-- /.datepicker_header -->
        <div class="datepicker_header">
            <button class="prev">Prev</button>
            <div>
                <select class="month_input">
                    <option value="0">January</option>
                    <option value="1">February</option>
                    <option value="2">March</option>
                    <option value="3">April</option>
                    <option value="4">May</option>
                    <option value="5">June</option>
                    <option value="6">July</option>
                    <option value="7">August</option>
                    <option value="8">September</option>
                    <option value="9">October</option>
                    <option value="10">November</option>
                    <option value="11">December</option>
                </select>
                <input type="number" class="year_input" value="2024" min="1900" max="2100">
            </div>
            <button class="next">Next</button>
        </div>

        <!-- /.days -->
        <div class="days">
            <span>Sun</span>
            <span>Mon</span>
            <span>Tue</span>
            <span>Wed</span>
            <span>Thu</span>
            <span>Fri</span>
            <span>Sat</span>
        </div>
        <!-- /.days -->

        <div class="dates">
            <!-- Dates will be dynamically generated here -->
            <button disabled>1</button>
            <button disabled>2</button>
            <button disabled>3</button>
            <button disabled>4</button>
            <button>5</button>
            <button>6</button>
            <button>7</button>
            <button>8</button>
            <button>9</button>
            <button>10</button>
            <button>11</button>
            <button>12</button>
            <button>13</button>
            <button>14</button>
            <button class="today">15</button>
            <button>16</button>
            <button>17</button>
            <button>18</button>
            <button>19</button>
            <button>20</button>
            <button>21</button>
            <button>22</button>
            <button class="selected">23</button>
            <button>24</button>
            <button>25</button>
            <button>26</button>
            <button>27</button>
            <button>28</button>
            <button>29</button>
            <button>30</button>
            <button>31</button>




        </div>

        <!-- /.datepicker_footer -->
        <div class="datepicker_footer">
            <button class="close">Close</button>
            <button class="apply">Apply</button>
        </div>

    </div>
</div>
<script>
/*
  We loop through ALL datepickers on the page.
  This allows us to reuse the same datepicker.php
  multiple times (start date, end date, etc.)
*/
document.querySelectorAll('.datepicker_container').forEach(datepicker => {

    // ==============================
    // GET ELEMENTS INSIDE THIS PICKER
    // ==============================

    // Visible input (what the user clicks)
    const dateInput = datepicker.querySelector('.date_input');

    // Popup calendar container
    const datepickerPopup = datepicker.querySelector('.datepicker_popup');

    // Buttons inside the popup
    const closeBtn = datepicker.querySelector('.close');
    const applyBtn = datepicker.querySelector('.apply');
    const prevBtn = datepicker.querySelector('.prev');
    const nextBtn = datepicker.querySelector('.next');

    // Calendar body where days are rendered
    const datesContainer = datepicker.querySelector('.dates');

    // Month & year controls
    const monthInput = datepicker.querySelector('.month_input');
    const yearInput = datepicker.querySelector('.year_input');

    // Hidden input (actual value sent to PHP)
    const hiddenDateInput = datepicker.querySelector('.date_hidden_input');

    // Holds the currently selected date
    let selectedDate = new Date();

    // Extract year and month from the selected date
    let year = selectedDate.getFullYear();   // e.g. 2026
    let month = selectedDate.getMonth();     // 0–11 (Jan–Dec)

    // ==============================
    // SHOW DATEPICKER
    // ==============================

    // When user clicks the visible input, show popup
    dateInput.addEventListener('click', () => {
        datepickerPopup.style.display = 'block';
    });

    // ==============================
    // CLOSE DATEPICKER
    // ==============================

    // Close button hides the popup
    closeBtn.addEventListener('click', () => {
        datepickerPopup.style.display = 'none';
    });

    // ==============================
    // APPLY SELECTED DATE
    // ==============================

    applyBtn.addEventListener('click', () => {

        // Extract date parts
        const y = selectedDate.getFullYear();
        const m = String(selectedDate.getMonth() + 1).padStart(2, '0');
        const d = String(selectedDate.getDate()).padStart(2, '0');

        // Store database-friendly value (YYYY-MM-DD)
        hiddenDateInput.value = `${y}-${m}-${d}`;

        // Store readable date for the user
        dateInput.value = selectedDate.toDateString();

        // Close the popup
        datepickerPopup.style.display = 'none';
    });

    // ==============================
    // RENDER CALENDAR DATES
    // ==============================

    function displayDates() {

        // Clear old dates
        datesContainer.innerHTML = '';

        // Get weekday of first day of the month (0 = Sunday)
        const firstDay = new Date(year, month, 1).getDay();

        // Get number of days in the month
        const lastDate = new Date(year, month + 1, 0).getDate();

        // Today's date (used for highlighting)
        const today = new Date();

        // --------------------------------
        // Empty spaces before first date
        // --------------------------------
        for (let i = 0; i < firstDay; i++) {
            const btn = document.createElement('button');
            btn.disabled = true; // Not clickable
            datesContainer.appendChild(btn);
        }

        // --------------------------------
        // Create date buttons
        // --------------------------------
        for (let day = 1; day <= lastDate; day++) {

            const btn = document.createElement('button');
            btn.textContent = day;

            // Highlight today
            if (
                day === today.getDate() &&
                month === today.getMonth() &&
                year === today.getFullYear()
            ) {
                btn.classList.add('today');
            }

            // Highlight selected date
            if (
                day === selectedDate.getDate() &&
                month === selectedDate.getMonth() &&
                year === selectedDate.getFullYear()
            ) {
                btn.classList.add('selected');
            }

            // When a date is clicked
            btn.addEventListener('click', () => {
                selectedDate = new Date(year, month, day);
                displayDates(); // Re-render to update highlight
            });

            datesContainer.appendChild(btn);
        }

        // Update month & year inputs
        monthInput.value = month;
        yearInput.value = year;
    }

    // ==============================
    // MONTH NAVIGATION
    // ==============================

    nextBtn.addEventListener('click', () => {
        month++;

        // If month goes past December
        if (month > 11) {
            month = 0;
            year++;
        }

        displayDates();
    });

    prevBtn.addEventListener('click', () => {
        month--;

        // If month goes before January
        if (month < 0) {
            month = 11;
            year--;
        }

        displayDates();
    });

    // ==============================
    // INITIAL RENDER
    // ==============================

    displayDates();
});
</script>
