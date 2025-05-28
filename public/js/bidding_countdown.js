const endTime = new Date("<?php echo $item['end_time']; ?>").getTime();
const countdownEl = document.getElementById("countdown");
const bidForm = document.querySelector("form");

function updateCountdown() {
    const now = new Date().getTime();
    const diff = endTime - now;

    if (diff <= 0) {
        countdownEl.innerText = "Auction ended";
        if (bidForm) {
            bidForm.querySelector("input[name='bid_amount']").disabled = true;
            bidForm.querySelector("button[type='submit']").disabled = true;
        }
        clearInterval(timer);
    } else {
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        countdownEl.innerText = `Time left: ${minutes}m ${seconds}s`;
    }
}

const timer = setInterval(updateCountdown, 1000);
updateCountdown(); // run once immediately
