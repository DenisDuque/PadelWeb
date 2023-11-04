window.onload = allActions;

function allActions(){
    setupCalendar();
    addEvents();
    cancelButtons();
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

    let date = new Date(currentYear, currentMonth, 1)
    let startDay = date.getDay();
    if(startDay == 0) {
        startDay = 6
    } else {
        startDay -= 1;
    }

    let daysIterated = 0;

    let calendar = document.getElementById("calendar");

    const daysToRemove = calendar.querySelectorAll(".day");

    daysToRemove.forEach(function(day) {
        day.remove();
    });

    for (let i = 0; i < startDay; i++) {
        let emptyDiv = document.createElement("div");
        calendar.appendChild(emptyDiv);
        emptyDiv.classList.add("day");

        daysIterated++;
    }

    let numOfDays = new Date(currentYear, currentMonth+1, 0, 0).getDate();
    
    for (let i = 0; i < numOfDays; i++) {
        let dayDiv = document.createElement("div");
        dayDiv.classList.add("day");
        
        let dayNumber = document.createElement("p");
        dayNumber.innerHTML = i+1;
        
        dayDiv.appendChild(dayNumber);

        dayDiv.addEventListener("click", function() {
            console.log(currentYear+"-"+(currentMonth+1)+"-"+dayNumber.innerHTML);
        });
        calendar.appendChild(dayDiv);

        daysIterated++;
    }

    while (daysIterated < 42) {
        let emptyDiv = document.createElement("div");
        calendar.appendChild(emptyDiv);
        emptyDiv.classList.add("day");

        daysIterated++;
    }
}

function addEvents() {
    let prevButton = document.getElementById("prv");
    let nextButton = document.getElementById("nxt");
    let expandButton = document.getElementById("expandBookings");

    prevButton.addEventListener("click", function() {
        if (currentMonth == 0) {
            currentMonth=11;
            currentYear-=1;
        } else {
            currentMonth-=1;
        }
        setupCalendar();
    });

    nextButton.addEventListener("click", function() {
        if (currentMonth == 11) {
            currentMonth=0;
            currentYear+=1;
        } else {
            currentMonth+=1;
        }
        setupCalendar();
    })

    expandButton.addEventListener("click", function() {
        let bookingContainer = document.getElementById("bookingContainer");

        bookingContainer.style.maxHeight = "none";

        expandButton.remove();
    });
}

function cancelButtons(){
    let buttons = document.getElementsByClassName("cancelButton");
    for (let i = 0; i < buttons.length; i++) {
        let button = buttons[i]
        button.addEventListener("click", function(){
            openCancelBooking(button.id);
        })
    }
        
}

function openCancelBooking(id){
    let backgroundDiv = document.getElementById("background");
    backgroundDiv.classList.add("background");

    backgroundDiv.addEventListener("click", function() {
        backgroundDiv.classList.remove("background");
        let child = document.getElementById("cancelDiv")
        backgroundDiv.removeChild(child);
    });

    let cancelDiv = document.createElement("div");
    cancelDiv.id = "cancelDiv";
    cancelDiv.classList.add("cancelDiv");
    cancelDiv.innerHTML = "prueba " + id;

    backgroundDiv.appendChild(cancelDiv)
}