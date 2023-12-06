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
        const startingDayOfWeek = (firstDay.getDay() + 6) % 7; // Modifié pour commencer à partir de lundi
    
        monthYear.textContent = new Intl.DateTimeFormat('en-US', { year: 'numeric', month: 'long' }).format(firstDay);
    
        daysContainer.innerHTML = '';
    
        function openDayDetails(dayNumber, month, year) {
            const redirectURL = `../Heure/Heure.php?day=${dayNumber}&month=${month + 1}&year=${year}`;
            window.location.href = redirectURL;
        }
    
        for (let i = 0; i < 7; i++) { // Boucle pour les jours de la semaine (0: lundi, 1: mardi, ..., 6: dimanche)
            const dayElement = document.createElement('div');
            const dayNumber = (i - startingDayOfWeek + 7) % 7 || 7 ; // Calcul du numéro du jour
            dayElement.textContent = dayNumber; // Affiche le numéro du jour
    
            dayElement.classList.add('day');
            dayElement.addEventListener('click', function() {
                openDayDetails(dayNumber, currentMonth, currentYear);
            });
    
            daysContainer.appendChild(dayElement);
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
