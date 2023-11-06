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

    var data = new FormData();
    data.append('year', currentYear);
    data.append('month', currentMonth+1);

    fetch('getBookingsByMonth.php', {
        method: 'POST',
        body: data,
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(bookingsForMonth) {
        let date = new Date(currentYear, currentMonth, 1)
        let startDay = date.getDay();
        if(startDay == 0) startDay = 6;
        else startDay -= 1;

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
            
            let otherBookings = 0;
            let userHasBooking = false;
            let courtsNum;
            const numOfHours = 8;

            for (let j = 0; j < bookingsForMonth.length; j++) {
                const book = bookingsForMonth[j];
                
                if(book.day.split('-')[2]==i+1){
                    if(book.email == book.current) {
                        dayDiv.classList.add("myBooking");
                        userHasBooking = true;
                    } else {
                        otherBookings+=1;
                    }
                }

                courtsNum = book.numCourts;
            }

            if(!userHasBooking){
                let thisDayInflux = ((otherBookings * 100) / (courtsNum * numOfHours));
                thisDayInflux = Math.ceil(thisDayInflux);
                
                if(thisDayInflux == 100) {
                    dayDiv.classList.add("fullBookings");
                } else if(thisDayInflux >= 50) {
                    dayDiv.classList.add("highInflux")
                }
            }

            dayDiv.appendChild(dayNumber);

            dayDiv.addEventListener("click", function() {
                let date = (currentYear+"-"+(currentMonth+1)+"-"+dayNumber.innerHTML);

                var data = new FormData();
                data.append('date', date);

                fetch('getBookingsByDay.php', {
                    method: 'POST',
                    body: data,
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(bookingsForDay) {
                    
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
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
    })
    .catch(function(error) {
        console.error('Error:', error);
    });

    
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
        let popup = document.getElementById("cancelDiv")
        popup.remove();
    });

    let cancelDiv = document.createElement("div");
    cancelDiv.id = "cancelDiv";

    let text = document.createElement("p");
    text.classList.add("text");
    text.innerHTML = "Are you sure you want to <br>cancel this booking?";
    cancelDiv.appendChild(text);
    
    let parent = document.getElementById(id).parentElement;
    let day = document.createElement("p");
    day.classList.add("day");
    let days = parent.getElementsByClassName("day");
    day.innerHTML = days[0].innerHTML;
    cancelDiv.appendChild(day);

    let month = document.createElement("p");
    let months = parent.getElementsByClassName("month");
    month.classList.add("month");
    month.innerHTML = months[0].innerHTML;
    cancelDiv.appendChild(month);

    let hour = document.createElement("p");
    let hours = parent.getElementsByClassName("hour");
    hour.classList.add("hour");
    hour.innerHTML = hours[0].innerHTML;
    cancelDiv.appendChild(hour);

    let court = document.createElement("p");
    let courts = parent.getElementsByClassName("court");
    court.classList.add("court");
    court.innerHTML = courts[0].innerHTML;
    cancelDiv.appendChild(court);

    let returnButton = document.createElement("button");
    returnButton.classList.add("returnButton");
    returnButton.innerText = "Return";

    returnButton.addEventListener("click", function(){
        backgroundDiv.click();
    });

    cancelDiv.appendChild(returnButton);

    let confirmButton = document.createElement("button");
    confirmButton.classList.add("confirmButton");
    confirmButton.innerText = "Confirm";

    confirmButton.addEventListener("click", function(){
        location.href = "user.php?cancelId="+id;
    })

    cancelDiv.appendChild(confirmButton);

    let body = document.getElementsByTagName("body");

    body[0].appendChild(cancelDiv)
}