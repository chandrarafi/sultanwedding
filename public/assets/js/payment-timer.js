/**
 * Payment Timer Script
 * Handles countdown timer for payment deadline
 */
document.addEventListener("DOMContentLoaded", function () {
  const timerElement = document.getElementById("payment-timer");

  if (timerElement) {
    const remainingSeconds = parseInt(
      timerElement.dataset.remainingSeconds || 300
    );
    let timeLeft = remainingSeconds;

    // Update timer every second
    const timerInterval = setInterval(function () {
      if (timeLeft <= 0) {
        clearInterval(timerInterval);
        handleExpiredPayment();
        return;
      }

      // Update display
      const minutes = Math.floor(timeLeft / 60);
      const seconds = timeLeft % 60;
      timerElement.textContent = `${minutes
        .toString()
        .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;

      // Change color when less than 1 minute
      if (timeLeft < 60) {
        timerElement.classList.remove("text-blue-800");
        timerElement.classList.add("text-red-600");

        // Add pulse animation when less than 30 seconds
        if (timeLeft < 30) {
          timerElement.classList.add("animate-pulse");
        }
      }

      timeLeft--;
    }, 1000);

    // Function to handle expired payment
    function handleExpiredPayment() {
      timerElement.textContent = "00:00";
      timerElement.classList.remove("text-blue-800", "animate-pulse");
      timerElement.classList.add("text-red-600");

      const timerContainer = document.getElementById("timer-container");
      if (timerContainer) {
        timerContainer.classList.remove("bg-blue-50", "border-blue-200");
        timerContainer.classList.add("bg-red-50", "border-red-200");
      }

      // Show expired message
      const expiredMessage = document.createElement("div");
      expiredMessage.className = "mt-2 text-red-600 font-medium";
      expiredMessage.textContent =
        "Waktu pembayaran telah habis. Halaman akan dialihkan...";
      timerContainer.appendChild(expiredMessage);

      // Check payment status via AJAX
      const kdpemesanan = document.getElementById("kdpemesanan")?.value;
      if (kdpemesanan) {
        fetch(`${BASE_URL}/pelanggan/pemesanan/check-status/${kdpemesanan}`)
          .then((response) => response.json())
          .then((data) => {
            if (data.expired) {
              // Redirect after 3 seconds
              setTimeout(() => {
                window.location.href = data.redirect || `${BASE_URL}/`;
              }, 3000);
            }
          })
          .catch((error) => {
            console.error("Error checking payment status:", error);
          });
      }
    }
  }
});
