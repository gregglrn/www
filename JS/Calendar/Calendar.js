document.addEventListener('DOMContentLoaded', function() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const monthYear = document.getElementById('monthYear');
    const daysContainer = document.querySelector('.days');

    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    function generateCalendar(year, month) {
        const firstDay = new Date(year, month, 1);
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const startingDayOfWeek = (firstDay.getDay() + 6) % 7;

        monthYear.textContent = new Intl.DateTimeFormat('en-US', { year: 'numeric', month: 'long' }).format(firstDay);

        daysContainer.innerHTML = '';

        function openDayDetails(dayNumber, month, year) {
            const redirectURL = `../Heure/Heure.php?day=${dayNumber}&month=${month + 1}&year=${year}`;
            window.location.href = redirectURL;
        }

        // Affichez les jours du mois précédent
        for (let i = startingDayOfWeek - 1; i >= 0; i--) {
            const prevMonthDay = document.createElement('div');
            prevMonthDay.classList.add('inactive');
            prevMonthDay.textContent = new Date(year, month, -i).getDate();
            daysContainer.appendChild(prevMonthDay);
        }

        // Affichez les jours du mois en cours
        for (let i = 0; i < daysInMonth; i++) {
            const dayElement = document.createElement('div');
            const dayNumber = i + 1;
            dayElement.textContent = dayNumber;
            dayElement.classList.add('day');
            dayElement.addEventListener('click', function() {
                openDayDetails(dayNumber, currentMonth, currentYear);
            });
            daysContainer.appendChild(dayElement);
        }

        // Affichez les jours du mois suivant
        const daysAfter = 42 - startingDayOfWeek - daysInMonth;
        for (let i = 0; i < daysAfter; i++) {
            const nextMonthDay = document.createElement('div');
            nextMonthDay.classList.add('inactive');
            nextMonthDay.textContent = new Date(year, month + 1, i + 1).getDate();
            daysContainer.appendChild(nextMonthDay);
        }
    }

    generateCalendar(currentYear, currentMonth);

    prevBtn.addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar(currentYear, currentMonth);
    });

    nextBtn.addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar(currentYear, currentMonth);
    });
});
