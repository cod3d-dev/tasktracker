

<x-dynamic-component
    :component="$getFieldWrapperView()" :field="$field"
>

    <div class="container">

        <h1>Timer</h1>

        <div class="countdown" x-data="countdown()">
            <form class="countdown__form" x-on:submit.prevent="start" x-show="!isRunning">
                <label class="countdown__input">
                    <span class="countdown__label">Sekunden:</span>
                    <input class="countdown__number" type="number" name="seconds" x-model="seconds" />
                </label>
                <input class="countdown__start" type="submit" value="start" />
            </form>
            <div class="countdown__output" x-show="isRunning">
                <h2 class="countdown__outputnumber" x-text="timeLeft"></h2>
                <button class="countdown__reset" x-on:click="stop">reset</button>
            </div>
        </div>

        <script>
            function @this.countdown() {
                return {
                    seconds: 5,
                    timeLeft: this.seconds,
                    timer: undefined,
                    isRunning: false,
                    start() {
                        if (!@this.seconds || @this.seconds == 0) {
                            return;
                        }

                        @this.timeLeft = @this.seconds;
                        @this.isRunning = true;

                        @this.timer = setInterval(() => {
                            @this.timeLeft--;

                            if (@this.timeLeft === 0) {
                                // play alarm sound
                            }

                            if (@this.timeLeft < 0) {
                                @this.stop();
                            }
                        }, 1000);

                    },
                    stop() {
                        clearInterval(@this.timer);
                        @this.isRunning = false;
                    }
                }
            }
        </script>
    </div>

</x-dynamic-component>


