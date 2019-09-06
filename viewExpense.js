var options = {};
// options['Day'] = ['1', '2', '3', '4', '5', '6', '7'];
// options['Month'] = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
// options['Year'] = ['2018', '2019'];
function dropdown() {
    //decision is the first drop down menu: Average or Total
    var decision = document.getElementById("decision");
    //selection is the second drop down menu: Day, Month, or Year
    var selection = document.getElementsById("average").value;
    //subSelect is the third drop down menu
    var subSelect = document.getElementById("subSelect");

    while (subSelect.options.length > 1) {
        subSelect.remove(1);
    }
    //if year 2019 is chosen, option is not 2019 but rather a single digit number like 2, saying that it is the 2nd option from dropdown (MUST FIX)
    var option = options[selection];
    if (selection === 'Day') {
        for (var i = 0; i < 30; i++) {
            var newOption = new Option(i + 1, i + 1);
            subSelect.options.add(newOption);
        }
    } else if (option) {
        for (var i = 0; i < option.length; i++) {
            var newOption = new Option(option[i], i + 1);
            subSelect.options.add(newOption);
        }
    }
}

// function hideOptions() {
//     $("#container").children().hide();
// }

// function start() {
//     dropdown();
//     hideOptions();
// }