<div>
    <div x-data="{
  running: false,
  seconds: 0,
  minutes: 0,
  timer: null
}">
        <div x-text="minutes + 'm ' + seconds + 's'">
            <input type="text" x-model="seconds">
        </div>
        <button x-on:click="toggleTimer()">Start</button>

        <script>
            function pad(val) {
                return val > 9 ? val : "0" + val;
            }
            window.pad = pad;
        </script>

        <script>
            function toggleTimer() {
                console.log("CLic");
                if (this.running) {
                    this.timer = setInterval(() => {
                        this.seconds++;
                        if (this.seconds === 60) {
                            this.seconds = 0;
                            this.minutes++;
                        }
                    }, 1000);
                } else {
                    clearInterval(this.timer);
                }
            }
            window.toggleTimer = toggleTimer;
        </script>
    </div>
</div>
