{{-- Scripts Partial --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function countdown(targetDate) {
        return {
            days: '00',
            hours: '00',
            minutes: '00', 
            seconds: '00',
            init() {
                this.updateCountdown();
                setInterval(() => this.updateCountdown(), 1000);
            },
            updateCountdown() {
                const target = new Date(targetDate).getTime();
                const now = new Date().getTime();
                const diff = target - now;
                
                if (diff > 0) {
                    this.days = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
                    this.hours = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                    this.minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    this.seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
                } else {
                    this.days = '00';
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                }
            }
        }
    }
</script>
