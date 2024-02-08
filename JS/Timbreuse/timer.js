
  let timerInterval;
  let startTime = 0;
  let elapsedTime = 0;
  let isRunning = false;

  const chronoInput = document.getElementById('chronoInput');
  const startPauseButton = document.getElementById('startPauseButton');
  const endButton = document.getElementById('endButton');

  startPauseButton.addEventListener('click', () => {
    if (!isRunning) {
      startPauseButton.textContent = 'Pause';
      startTime = Date.now() - elapsedTime;
      timerInterval = setInterval(updateChrono, 1000);
    } else {
      startPauseButton.textContent = 'Start';
      clearInterval(timerInterval);
    }
    isRunning = !isRunning;
  });

  endButton.addEventListener('click', () => {
    if (isRunning) {
      clearInterval(timerInterval);
      isRunning = false;
      startPauseButton.textContent = 'Start';
    }

    const currentTime = chronoInput.value;
    // Envoyer currentTime à un script PHP pour enregistrement en base de données
    fetch('../../PHP/timbreuse/timbreuse.php', {
      method: 'POST',
      body: JSON.stringify({ time: currentTime }),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      // Traiter la réponse du serveur si nécessaire
    });
  });

  function updateChrono() {
    const currentTime = Date.now() - startTime;
    elapsedTime = currentTime;
    const hours = Math.floor(currentTime / 3600000);
    const minutes = Math.floor((currentTime % 3600000) / 60000);
    const seconds = Math.floor((currentTime % 60000) / 1000);
    chronoInput.value = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
  }
