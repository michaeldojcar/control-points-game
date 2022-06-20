<template>
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <h3>Řízení hry</h3>

                Stav hry (server): {{ game.status_string || '-' }}<br>
                Interní stav: {{ internal_state }}
            </div>

            <div v-if="internal_state === 'preparing'"
                 style="font-size: 40px">
                Do začátku hry: {{ timer }} s
            </div>

            <div v-if="internal_state === 'playing'"
                 style="font-size: 40px">
                Čas hry: {{ timer }} s
            </div>

            <div v-if="internal_state === 'countdown'"
                 style="font-size: 40px">
                Čas hry: {{ timer }} s
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Ovládání hry
                </div>
                <div class="card-body">
                    <div v-if="internal_state === 'init'">

                        <h5>Výběr soundtracku</h5>

                        <a class="btn btn-primary"
                           @click="chooseAudio(1)">1) Adventure</a>
                        <a class="btn btn-primary"
                           @click="chooseAudio(2)">2) Lasermaxx</a>
                        <a class="btn btn-primary"
                           @click="chooseAudio(3)">3) Call of Duty</a>
                        <hr>

                        <div class="btn btn-primary mt-3"
                             @click="startGame">
                            Spustit hru
                        </div>
                    </div>

                    <div v-if="internal_state === 'playing'">
                        <div class="btn btn-primary"
                             @click="endGame">
                            Konec hry
                        </div>
                    </div>

                    <hr>
                    <a @click="forceQuitGame"
                       v-if="game.status !== 'force_ended'">Vynutit ukončení hry</a>


                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Operator",

    data() {
        return {
            game: {},
            bg_audio: 1,

            // Music objects
            announcementAudio: null,
            startCountdownAudio: null,
            backgroundAudio: null,
            endCountdownAudio: null,

            timer: null,
            timer_interval: null,

            internal_state: 'loading'
        }
    },

    props: [
        'game_id'
    ],

    mounted() {
        console.debug('Načítám prvotní data o sehrávce.')

        this.announcementAudio = document.createElement('audio');
        this.startCountdownAudio = document.createElement('audio');
        this.backgroundAudio = document.createElement('audio');
        this.endCountdownAudio = document.createElement('audio');

        this.updateInternalStateByServer()
    },

    methods: {
        updateInternalStateByServer() {
            console.debug('Aktualizuji interní stav podle serveru.')

            // Update internal state
            axios.get('/api/games/' + this.game_id)
                .then(response => {
                    this.game = response.data;

                    this.changeAndProceedInternalState(response.data.status)
                })
        },

        changeAndProceedInternalState(state) {
            console.debug('Zpracování nového interního stavu: ' + state)

            this.internal_state = state;

            if (state === 'init') {

            }
            if (state === 'preparing') {
                this.startPreparing()
            }
            if (state === 'playing') {
                this.statusPlaying()
            }
            if (state === 'countdown') {
                this.statusCountdown()
            }
            if (state === 'finished') {

            }
            if (state === 'force-ended') {

            }

        },


        /**
         * User manual actions.
         */
        chooseAudio(number) {
            console.debug('Výběr soundtracku: ' + number)
            this.bg_audio = number;
        },

        startGame() {
            console.log('Spuštění hry pomocí tlačítka.')

            this.sendStatusToServer('preparing').then(response =>
                this.changeAndProceedInternalState('preparing')
            )
        },

        endGame() {
            console.log('Ukončení hry pomocí tlačítka.')

            this.sendStatusToServer('countdown')
            this.changeAndProceedInternalState('countdown')
        },

        forceQuitGame() {
            confirm('Opravdu chcete ukončit tuto sehrávku? Sehrávka bude zastavena okamžitě a bez odpočtu.')

            this.sendStatusToServer('force_ended')
            this.changeAndProceedInternalState('force_ended')
        },


        /**
         * Internal status callbacks.
         */
        startPreparing() {
            console.debug('Procedura pro nový stav: preparing.')

            this.playStartCountdown()
            this.startTimer()

            // Switch status to playing
            setTimeout(() => {
                this.sendStatusToServer('playing')
                this.changeAndProceedInternalState('playing')

                setInterval(function () {
                    // TODO: Announcement
                }, 3000);

                return true;
            }, 43068);
        },

        statusPlaying() {
            this.playBackground()
            if (this.timer === null) {
                this.startTimerByServerStart()
            }
        },

        statusCountdown() {
            this.playEndCountdown()

            if (!this.backgroundAudio.paused) {
                setTimeout(() => {
                    this.fadeOutBackground();
                }, 200);
            }

            setTimeout(() => {
                this.sendStatusToServer('finished')
                this.changeAndProceedInternalState('finished')
            }, 120035);
        },


        /**
         * Music methods.
         */
        playStartCountdown() {
            this.startCountdownAudio.src = '/sound/start_countdown.mp3';
            this.startCountdownAudio.play();
        },

        playBackground() {
            console.log('Spouštím soundtrack č. ' + this.bg_audio);

            this.backgroundAudio.src = '/sound/cp_bg' + this.bg_audio + '.mp3';
            this.backgroundAudio.play();
        },

        playEndCountdown() {
            this.announcementAudio.volume = 0;

            this.endCountdownAudio.src = '/sound/EndCountdown.mp3';
            this.endCountdownAudio.play();
        },

        fadeOutBackground() {
            setInterval(() => {

                if (this.backgroundAudio.volume > 0.2) {
                    this.backgroundAudio.volume -= 0.1;
                }

                else {
                    this.backgroundAudio.pause();
                }
            }, 50);
        },

        /**
         * Timer methods.
         */
        startTimer() {
            this.timer = -43;

            this.timer_interval = setInterval(() => {
                this.timer = this.timer + 1;
            }, 1000);
        },

        startTimerByServerStart() {
            this.timer = this.game.seconds_elapsed;

            this.timer_interval = setInterval(() => {
                this.timer = this.timer + 1;
            }, 1000);
        },

        /**
         * API state callbacks.
         */
        sendStatusToServer(state) {
            return axios.get('/api/games/' + this.game_id + '/' + state)
                .then(response => {
                    this.game = response.data;
                })
        }
    }
}
</script>

<style scoped>

</style>
