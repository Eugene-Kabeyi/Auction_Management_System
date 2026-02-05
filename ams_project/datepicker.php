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
            padding: 6px ;
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
    <input type="text" class="date_input" placeholder="Select Date">
    <input type="hidden" class="date_hidden_input" name="date_hidden_input">
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
    const dateInput = document.querySelector('.date_input');
    const datepickerPopup = document.querySelector('.datepicker_popup');
    const closeBtn = document.querySelector('.close');
    const applyBtn = document.querySelector('.apply');
    const datesContainer = document.querySelector('.dates');
    const monthInput = document.querySelector('.month_input');
    const yearInput = document.querySelector('.year_input');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    const hiddenDateInput = document.querySelector('.date_hidden_input');


    let selectedDate = new Date(); // Initialize with current date
    let year = selectedDate.getFullYear(); // This is because the year input is a number input where the value corresponds to the actual year (e.g., 2024). So we set it to the full year of the selected date.
    let month = selectedDate.getMonth(); // This is because the month input is a select element where the value corresponds to the month index (0 for January, 1 for February, etc.). So we set it to the month index of the selected date.

    // Show datepicker
    dateInput.addEventListener('click', () => {
        datepickerPopup.style.display = 'block'; // Show the datepicker popup
    });

    // Hide datepicker
    closeBtn.addEventListener('click', () => {
        datepickerPopup.style.display = 'none';
    });

    // Apply selected date
    applyBtn.addEventListener('click', () => {
        // DB-friendly format
        const y = selectedDate.getFullYear();
        const m = String(selectedDate.getMonth() + 1).padStart(2, '0');
        const d = String(selectedDate.getDate()).padStart(2, '0');

        hiddenDateInput.value = `${y}-${m}-${d}`; // YYYY-MM-DD

        dateInput.value = selectedDate.toDateString(); // This sets the value of the date input to a human-readable string representation of the selected date. You can customize this format as needed, for example using toLocaleDateString() for a more localized format.
        datepickerPopup.style.display = 'none';
    });



    // Change month/year
    monthInput.addEventListener('change', () => {
        month = parseInt(monthInput.value); //This is because the month input is a select element where the value corresponds to the month index (0 for January, 1 for February, etc.). So we parse it as an integer and set it to the month variable.
        displayDates();
    });

    yearInput.addEventListener('change', () => {
        year = parseInt(yearInput.value);
        displayDates();
    });

    function displayDates() {
        datesContainer.innerHTML = '';

        const firstDay = new Date(year, month, 1).getDay(); // Gets the first day of the month (0-6, where 0 is Sunday)
        const lastDate = new Date(year, month + 1, 0).getDate(); //This is because month is 0-indexed, so we get the last day of the current month by asking for the 0th day of the next month.
        const today = new Date();

        // Empty slots before first day
        for (let i = 0; i < firstDay; i++) {
            const btn = document.createElement('button'); // Create a button element for each empty slot before the first day of the month
            btn.disabled = true; // Disable the button to indicate that it's not a valid date
            datesContainer.appendChild(btn); // This adds the disabled button to the dates container, creating empty slots before the first day of the month in the calendar grid.
        }

        // Actual dates
        for (let day = 1; day <= lastDate; day++) {
            const btn = document.createElement('button');
            btn.textContent = day;

            // Mark today
            if (
                day === today.getDate() &&
                month === today.getMonth() &&
                year === today.getFullYear()
            ) {
                btn.classList.add('today');
            }

            // Mark selected
            if (
                day === selectedDate.getDate() &&
                month === selectedDate.getMonth() &&
                year === selectedDate.getFullYear()
            ) {
                btn.classList.add('selected');
            }

            // Click handler
            btn.addEventListener('click', () => {
                selectedDate = new Date(year, month, day);
                displayDates();
            });

            datesContainer.appendChild(btn);
        }

        monthInput.value = month;
        yearInput.value = year;
    }
    nextBtn.addEventListener('click', () => {
        month++;

        // If month goes after December
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



    displayDates();
</script>