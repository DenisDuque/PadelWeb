window.onload = allActions;

function allActions(){
    setupCalendar(); // Configura el calendario
    addEvents();     // Agrega eventos a botones previos y siguientes
    cancelButtons(); // Agrega eventos a botones de cancelación de reserva
}

// Arreglo de nombres de meses.
const monthsName = [
    "January", "February", "March", "April", "May", "June", "July",
    "August", "September", "October", "November", "December"
];

const weekdaysName = [
    'Sunday', 'Monday', 'Tuesday', 'Wednesday', 
    'Thursday', 'Friday', 'Saturday'
];

const hoursAvailable = [
    '08:00','09:30','11:00','12:30','15:00','16:30','18:00','19:30', '21:00'
];

// Obtiene la fecha actual.
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

function setupCalendar(){
    // Obtiene elementos de la interfaz de usuario.
    let currentMonthLabel = document.getElementById("month");
    let currentYearLabel = document.getElementById("year");

    // Muestra el mes y año actual en la interfaz.
    currentMonthLabel.innerHTML = monthsName[currentMonth];
    currentYearLabel.innerHTML = currentYear;


    var data = new FormData();
    data.append('year', currentYear);
    data.append('month', currentMonth+1);

    // Realiza una solicitud al servidor para obtener las reservas del mes.
    fetch('getBookingsByMonth.php', {
        method: 'POST',
        body: data,
    })
    .then(function(response) {
        return response.json(); 
    })
    .then(function(bookingsForMonth) {

        // Una vez recibe los datos, procesa el calendario
        let date = new Date(currentYear, currentMonth, 1)
        let startDay = date.getDay();
        if(startDay == 0) startDay = 6;
        else startDay -= 1;

        let daysIterated = 0;

        // Obtiene el calendario
        let calendar = document.getElementById("calendar");

        // Quita los dias que ya hubiese en el dalendario
        const daysToRemove = calendar.querySelectorAll(".day");
        daysToRemove.forEach(function(day) {
            day.remove();
        });

        // Crea los dias vacios al principo del mes
        for (let i = 0; i < startDay; i++) {
            let emptyDiv = document.createElement("div");
            calendar.appendChild(emptyDiv);
            emptyDiv.classList.add("day");

            daysIterated++;
        }

        // Crea los dias con numero y evento
        let numOfDays = new Date(currentYear, currentMonth+1, 0, 0).getDate();
        for (let i = 0; i < numOfDays; i++) {
            let dayDiv = document.createElement("div");
            dayDiv.classList.add("day");
            
            // Se añade el numero del dia al div
            let dayNumber = document.createElement("p");
            dayNumber.innerHTML = i+1;
            dayDiv.appendChild(dayNumber);

            let otherBookings = 0;
            let userHasBooking = false;
            let courtsNum;
            

            for (let j = 0; j < bookingsForMonth.length; j++) {
                const book = bookingsForMonth[j];
                if(book.day.split('-')[2]==i+1){
                    if(book.email == book.current) {
                        // Si hay reserva del usuario el dia se verá azul
                        dayDiv.classList.add("myBooking");
                        userHasBooking = true;
                    } else {
                        // Si hay reserva y no es del usuario se guarda
                        otherBookings+=1;
                    }
                }
                courtsNum = book.numCourts;
            }

            // Si no hay ninguna reserva del usuario
            if(!userHasBooking){
                // Se calcula el % de gente que hay ese dia
                let thisDayInflux = ((otherBookings * 100) / (courtsNum * hoursAvailable.length));
                thisDayInflux = Math.ceil(thisDayInflux);
                
                if(thisDayInflux == 100) {
                    // Si esta completo el dia se verá rojo
                    dayDiv.classList.add("fullBookings");
                } else if(thisDayInflux >= 50) {
                    // Si esta al 50 o mas se verá amarillo
                    dayDiv.classList.add("highInflux")
                }
            }

            // Se añade un evento on click al dia
            dayDiv.addEventListener("click", function() {
                let numberDay = dayNumber.innerHTML;
                if(numberDay.length===1) numberDay = "0"+numberDay;

                let date = (currentYear+"-"+(currentMonth+1)+"-"+numberDay);

                // Filtra las resevas para el dia en el que se clica
                var bookingsForDay = bookingsForMonth.filter(function(reserva) {
                    return reserva.day === date;
                });

                console.log(bookingsForDay);
                
                // Crea el div
                let bookingSelector = document.createElement("div");
                bookingSelector.classList.add("bookingSelector");

                // Consigue el dia en el que clica
                let nameDay = weekdaysName[new Date(date).getDay()];
                let numberDayP = document.createElement("p");
                numberDayP.innerHTML = nameDay;
                numberDayP.innerHTML += " " + dayNumber.innerHTML + "  " + monthsName[currentMonth];
                numberDayP.classList.add("date")
                bookingSelector.appendChild(numberDayP);

                let hourContainer = document.createElement("div");
                hourContainer.classList.add("hourContainer");
                
                hoursAvailable.forEach(hour => {
                    hourDiv = document.createElement("div");
                    hourDiv.classList.add("hour");
                    hourDiv.id = hour;

                    let hourDisplay = document.createElement("p");
                    hourDisplay.innerHTML = hour.startsWith("0") ? hour.substring(1) : hour;
                    hourDiv.appendChild(hourDisplay);

                    hourDiv.addEventListener("click", function() {
                        hourEvent(hour);
                    })

                    hourContainer.appendChild(hourDiv);
                });
                bookingSelector.appendChild(hourContainer);

                let confirmDiv = document.createElement("div");
                confirmDiv.classList.add("confirmDiv");
                let bookAtP = document.createElement("p")
                bookAtP.innerHTML = "Select a hour to book";
                confirmDiv.appendChild(bookAtP);
                let confirmButton = document.createElement("button");
                confirmButton.innerHTML = "Confirm";
                confirmButton.id = "confirmB";
                confirmButton.disabled = true;
                confirmDiv.appendChild(confirmButton);
                bookingSelector.appendChild(confirmDiv);

                let infoDiv = document.createElement("div");
                infoDiv.classList.add("infoDiv");

                let halfBooked = document.createElement("p");
                halfBooked.classList.add("halfBooked");
                halfBooked.innerText = "50% booked";
                let fullBooked = document.createElement("p");
                fullBooked.classList.add("fullBooked");
                fullBooked.innerText = "All booked";
                let yourBooking = document.createElement("p");
                yourBooking.classList.add("yourBooking");
                yourBooking.innerText = "Your booking";

                infoDiv.appendChild(halfBooked);
                infoDiv.appendChild(fullBooked);
                infoDiv.appendChild(yourBooking);

                bookingSelector.appendChild(infoDiv);
                
                calendar.parentElement.appendChild(bookingSelector);
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

function hourEvent(hour) {
    let clickedDiv = document.getElementsByClassName("clicked");
    if (clickedDiv.length>0) {
        clickedDiv[0].classList.remove("clicked");
    }
    
    let hourDiv = document.getElementById(hour);
    hourDiv.classList.add("clicked");
    
    let confirmButton = document.getElementById("confirmB");
    confirmButton.disabled = false;
    let bookAtP = confirmButton.parentElement.getElementsByTagName("p");
    bookAtP = bookAtP[0];
    bookAtP.innerHTML = "Book at "
    bookAtP.innerHTML += hour.startsWith("0") ? hour.substring(1) : hour;
    
    var data = new FormData();
    data.append('hour',hour);

    fetch('getAvailableCourt.php', {
        method: 'POST',
        body: data,
    })
    .then(function(response) {
        return response.json(); 
    })
    .then(function(availableCourt) {

    })
    .catch(function(error) {
        console.error('Error:', error);
    });
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