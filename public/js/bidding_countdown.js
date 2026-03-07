function setupBiddingCountdown(endTimeIso) {
    const countdownEl = document.getElementById("countdown");
    const bidForm = document.querySelector("form");

    if (!countdownEl || !endTimeIso) {
        return;
    }

    const endTime = new Date(endTimeIso).getTime();

    function updateCountdown() {
        const now = Date.now();
        const diff = endTime - now;

        if (diff <= 0) {
            countdownEl.innerText = "Auction ended";

            if (bidForm) {
                const amountInput = bidForm.querySelector("input[name='bid_amount']");
                const submitButton = bidForm.querySelector("button[type='submit']");

                if (amountInput) {
                    amountInput.disabled = true;
                }

                if (submitButton) {
                    submitButton.disabled = true;
                }
            }

            clearInterval(timer);
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        countdownEl.innerText = `Time left: ${hours}h ${minutes}m ${seconds}s`;
    }

    const timer = setInterval(updateCountdown, 1000);
    updateCountdown();
}
