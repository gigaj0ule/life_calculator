<style type='text/css'>
    .border {
        width:70%;
    }

    .week, .age {
        display:inline-block;
        width: 1.8%;
        padding:0;
        box-sizing:border-box;
        overflow: hidden;
    }

    .week_color, .age {
        height:0.5rem;
    }

    .week {
        border:1px solid white;
    }

    .week_color {
        border:1px solid black;
        width:100%;
        background:white;
        box-sizing: border-box;
    }

    .age {
        border:1px solid transparent;
        width: 3%;
        text-align:right;
        padding-right:1rem;
        overflow:visible;
        padding-top: none;
        vertical-align: top;
    }

    .input_container, .weeks_container {
        width: 100%;
    }

    .input_container {
        margin-bottom: 1rem;
    }

    .input_label, .input_total_label {
        display:inline-block;
        padding-right: 1rem;
        text-align: right;
    }

    .input_label {
        width: 25%;
    }

    .input_total_label {
        padding-left: 1rem;
    }


    .input_row {
        padding-bottom:0.5rem;
        padding-top:0.5rem;
    }

    .used_by_yearly_expenses {
        background-color:orange;
    }

    .used_by_debts_and_expenses {
        background-color:red;
    }

    .passed_time {
        background-color:grey;
    }
</style>

<div class='border'>
<div class='input_container'>
    <div class='input_row'>
        <span class='input_label'>Your Age:</span>
        <input class='input_age' value='30' onBlur='recalculateWeeks(this)'/>
    </div>
    <div class='input_row'>
        <span class='input_label'>Present Incomes:</span>
        <input class='input_incomes' value='30000' onBlur='recalculateWeeks(this)'/>
        <span class='input_total_label'>Total:</span><span class='input_incomes_total'></span>
    </div>
    <div class='input_row'>
        <span class='input_label'>Weekly Working Hours:</span>
        <input class='input_hours_worked_per_week' value='40' onBlur='recalculateWeeks(this)'/>
        <span class='input_total_label'>Total:</span><span class='input_hours_worked_per_week_total'></span>
    </div>
    <div class='input_row'>
        <span class='input_label'>Hourly Rate:</span><span class='input_income_rate'></span>
    </div>
    <div class='input_row'>
        <span class='input_label'>Yearly Expenses:</span>
        <input class='input_yearly_expenses' value='10000' onBlur='recalculateWeeks(this)'/>
        <span class='input_total_label'>Total:</span><span class='input_yearly_expenses_total'></span>
    </div>
    <div class='input_row'>
        <span class='input_label'>One-Time Expenses:</span>
        <input class='input_expenses' value='0'  onBlur='recalculateWeeks(this)'/>
        <span class='input_total_label'>Total:</span><span class='input_expenses_total'></span>
    </div>
    <div class='input_row'>
        <span class='input_label'>Total Debts:</span>
        <input class='input_debts' value='0'  onBlur='recalculateWeeks(this)'/>
        <span class='input_total_label'>Total:</span><span class='input_debts_total'></span>
    </div>
    <div class='input_row'>
        <span class='input_label'>Non-recurring Expenses:</span><span class='input_non_recurring_expenses'></span>
    </div>
</div>

<div class="weeks_container">
<div id="the_html"></div>
</div>
</div>


<script type='text/javascript'>

class one_week {
  constructor(index) {
    this.index = index;
  }
}


let weeks_total = 100 * 52;

let weeks_array = Array();


function createWeeks() {

    week_printer = document.getElementById('the_html');

    // Iterate years
    for(y = 0; y < weeks_array.length / 52; y++) {

        let y_scale_label = '';

        if(y % 5 == 0) {
            y_scale_label = `${y}`;
        };

        let scale_element = document.createElement('div');
        scale_element.classList.add('age');
        scale_element.innerHTML = `${y_scale_label}`;

        week_printer.appendChild(scale_element);
        
        // Iterate weeks
        for(w = 0; w < 52; w++) {
            let this_week = weeks_array[y * 52 + w];

            week_printer.appendChild(weekArrayGenericIterator(this_week));
        }

        week_printer.appendChild(document.createElement('br'));
    }
    

}

function weekArrayGenericIterator(week){
    return week.html;
}

function setup() {

    for(i = 0; i < weeks_total; i++){
        let this_week = new one_week();

        this_week.index = parseInt(i);
        
        let weekDiv = document.createElement('div');
        weekDiv.classList.add('week');
        weekDiv.innerHTML = `<div class='week_color'></div>`;

        this_week.html = weekDiv;

        weeks_array.push(this_week);
    }
}

function recalculateWeeks(an_input){

    let inputs_container = document.getElementsByClassName('input_container')[0];
    let input_age = parseFloat(inputs_container.querySelector('.input_age').value.replace(/,/g, ''));
    //console.log(input_age);

    // Total Incomes
    let input_incomes_csv = inputs_container.querySelector('.input_incomes').value;
    let input_incomes_array = CSVToFloatArray(input_incomes_csv);
    let input_incomes_total = input_incomes_array.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
    inputs_container.querySelector('.input_incomes_total').innerHTML = input_incomes_total; 

    // Working Hours
    let input_hours_worked_per_week_csv = inputs_container.querySelector('.input_hours_worked_per_week').value;
    let input_hours_worked_per_week_array = CSVToFloatArray(input_hours_worked_per_week_csv);
    let input_hours_worked_per_week_total = input_hours_worked_per_week_array.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
    inputs_container.querySelector('.input_hours_worked_per_week_total').innerHTML = input_hours_worked_per_week_total; 

    // Calculate hourly rate
    let input_income_hourly_rate = input_incomes_total / input_hours_worked_per_week_total / 52;
    inputs_container.querySelector('.input_income_rate').innerHTML = input_income_hourly_rate; 

    // Calculate weekly rate
    let input_income_weekly_rate = input_income_hourly_rate * 24 * 7;

    // Yearly Expenses
    let input_yearly_expenses_csv = inputs_container.querySelector('.input_yearly_expenses').value;
    let input_yearly_expenses_array = CSVToFloatArray(input_yearly_expenses_csv);
    let input_yearly_expenses_total = input_yearly_expenses_array.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
    inputs_container.querySelector('.input_yearly_expenses_total').innerHTML = input_yearly_expenses_total; 

    // Non Recurring Expenses
    let input_expenses_csv = inputs_container.querySelector('.input_expenses').value;
    let input_expenses_array = CSVToFloatArray(input_expenses_csv);
    let input_expenses_total = input_expenses_array.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
    inputs_container.querySelector('.input_expenses_total').innerHTML = input_expenses_total; 

    // Debts @TODO: Add Interest
    let input_debts_csv = inputs_container.querySelector('.input_debts').value;
    let input_debts_array = CSVToFloatArray(input_debts_csv);
    let input_debts_total = input_debts_array.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
    inputs_container.querySelector('.input_debts_total').innerHTML = input_debts_total; 

    // Calculate total debts & expenses
    let total_debts_and_expenses = input_debts_total + input_expenses_total;
    inputs_container.querySelector('.input_non_recurring_expenses').innerHTML = total_debts_and_expenses; 



    for(i = 0; i < weeks_array.length; i ++) {

        let this_week = weeks_array[i];
        let week_color = this_week.html.querySelector('.week_color');

        // Reset classes
        week_color.classList.remove('passed_time');
        week_color.classList.remove("used_by_yearly_expenses");
        week_color.classList.remove("used_by_debts_and_expenses");

        // Mark passed time
        if(i < input_age * 52) {
            week_color.classList.add('passed_time');
        }
    }

    // Divide yearly expenses by hourly rate
    let total_debts_and_expenses_downcounter = total_debts_and_expenses;

    // Iterate years
    for(y = parseInt(input_age); y < weeks_array.length / 52; y++) {

        // How much time is allocated to new yearly expenses?
        let input_yearly_expenses_total_downcounter = input_yearly_expenses_total;
        
        // Iterate weeks
        for(w = 0; w < 52; w++) {
            let this_week = weeks_array[y * 52 + w];
            let week_color = this_week.html.querySelector('.week_color');

            input_yearly_expenses_total_downcounter -= input_income_weekly_rate;

            // Was this week of work was used for paying down yearly expenses?
            if(input_yearly_expenses_total_downcounter > 0) {
                week_color.classList.add("used_by_yearly_expenses");
            }

            // No? Well, how about paying down total one-time expenses and debts?
            else if(total_debts_and_expenses_downcounter > 0) {
                total_debts_and_expenses_downcounter -= input_income_weekly_rate;
                week_color.classList.add("used_by_debts_and_expenses");
            }
        }
    }
    
}

setup();
createWeeks();
recalculateWeeks();




function CSVToFloatArray( strData, strDelimiter ){
    // Check to see if the delimiter is defined. If not,
    // then default to comma.
    strDelimiter = (strDelimiter || ",");

    // Create a regular expression to parse the CSV values.
    var objPattern = new RegExp(
        (
            // Delimiters.
            "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

            // Quoted fields.
            "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

            // Standard fields.
            "([^\"\\" + strDelimiter + "\\r\\n]*))"
        ),
        "gi"
        );


    // Create an array to hold our data. Give the array
    // a default empty first row.
    var arrData = [[]];

    // Create an array to hold our individual pattern
    // matching groups.
    var arrMatches = null;

    // Catch empty case
    if(strData == '') return [];

    // Keep looping over the regular expression matches
    // until we can no longer find a match.
    while (arrMatches = objPattern.exec( strData )){

        // Get the delimiter that was found.
        var strMatchedDelimiter = arrMatches[ 1 ];

        // Check to see if the given delimiter has a length
        // (is not the start of string) and if it matches
        // field delimiter. If id does not, then we know
        // that this delimiter is a row delimiter.
        if (
            strMatchedDelimiter.length &&
            strMatchedDelimiter !== strDelimiter
            ){

            // Since we have reached a new row of data,
            // add an empty row to our data array.
            arrData.push( [] );

        }

        var strMatchedValue;

        // Now that we have our delimiter out of the way,
        // let's check to see which kind of value we
        // captured (quoted or unquoted).
        if (arrMatches[ 2 ]){

            // We found a quoted value. When we capture
            // this value, unescape any double quotes.
            strMatchedValue = arrMatches[ 2 ].replace(
                new RegExp( "\"\"", "g" ),
                "\""
                );

        } else {

            // We found a non-quoted value.
            strMatchedValue = arrMatches[ 3 ];

        }


        // Now that we have our value string, let's add
        // it to the data array.
        let float = parseFloat(strMatchedValue);
        if(!isNaN(float)) {
            arrData[ arrData.length - 1 ].push( float );
        }
    }

    // Return the parsed data.
    return( arrData[0] );
}



</script>