window.onload = allActions;

function allActions(){
    setupCalendar();
    addEvents();
    
}

let monthsName = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
  ];

let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

function setupCalendar(){
    let currentMonthLabel = document.getElementById("month");
    let currentYearLabel = document.getElementById("year");

    currentMonthLabel.innerHTML = monthsName[currentMonth];
    currentYearLabel.innerHTML = currentYear;
}

function addEvents() {
    let prevButton = document.getElementById("prv");
    let nextButton = document.getElementById("nxt");

    prevButton.addEventListener("click", function() {
        if (currentMonth == 0) {
            currentMonth=11;
            currentYear-=1;
        } else {
            currentMonth-=1;
        }
        refreshCalendar();
    });

    nextButton.addEventListener("click", function() {
        if (currentMonth == 11) {
            currentMonth=0;
            currentYear+=1;
        } else {
            currentMonth+=1;
        }
        refreshCalendar();
    })
}

function refreshCalendar() {
    let currentMonthLabel = document.getElementById("month");
    let currentYearLabel = document.getElementById("year");

    currentMonthLabel.innerHTML = monthsName[currentMonth];
    currentYearLabel.innerHTML = currentYear;
}