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

            <div class="card"
                 v-if="internal_state === 'playing'">
                <div class="card-body">
                    <div style="font-size: 40px">
                        Čas hry: {{ timer }} s
                    </div>
                </div>
            </div>


            <div class="card"
                 v-if="internal_state === 'countdown'">
                <div class="card-body">
                    <div>
                        Čas hry: {{ timer }} s
                    </div>
                    <div style="font-size: 40px">
                        Odpočet: {{ end_countdown }} s
                    </div>
                </div>
            </div>


            <div v-if="internal_state === 'finished'">
                <h5>Skóre</h5>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td style="width: 30px">1.</td>
                        <td>Alfa</td>
                        <td>17:23</td>
                    </tr>
                    </tbody>
                </table>
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

                    <div v-if="game.status === 'force_ended' || game.status === 'finished'">
                        <a href="/">Návrat na seznam her</a>
                    </div>

                    <div v-if="game.status !== 'force_ended' && game.status !== 'finished'">
                    <hr>
                    <a @click="forceQuitGame">Vynutit ukončení hry</a>
                    </div>

                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    Kontrolní body
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr v-for="point in control_points">
                            <td>{{ point.name }}</td>
                            <td>{{ point.ownerName || '-' }}</td>
                        </tr>
                        </tbody>
                    </table>
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
            control_points: [],
            bg_audio: 1,

            // Music objects
            announcementAudio: null,
            startCountdownAudio: null,
            backgroundAudio: null,
            endCountdownAudio: null,

            // Timers
            timer: null,
            timer_interval: null,
            end_countdown: null,
            end_countdown_interval: null,

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

                    if (response.data.status !== this.internal_state) {
                        this.changeAndProceedInternalState(response.data.status)
                    }
                })

            axios.get('/api/control-points')
                .then(response => {
                    this.control_points = response.data
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
            if (confirm('Opravdu chcete ukončit tuto sehrávku? Sehrávka bude zastavena okamžitě a bez odpočtu.')) {
                this.sendStatusToServer('force_ended')
                this.changeAndProceedInternalState('force_ended')
            }
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

                return true;
            }, 43068);
        },

        statusPlaying() {
            this.playBackground()
            if (this.timer === null) {
                this.startTimerByServerStart()
            }

            setInterval(() => {
                // Přehrávání hlášek
                this.playCurrentAnnouncement();
            }, 3000);
        },

        statusCountdown() {
            this.playEndCountdown()
            this.startEndTimer()

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

        playCurrentAnnouncement() {
            axios.get('/api/games/' + this.game_id + '/audio')
                .then(response => {
                    if (!response.data) {
                        console.debug('Žádná hláška k přehrání.')
                    }
                    else {
                        this.announcementAudio.src = '/sound/' + response.data.filename;

                        console.debug('Přehrávám aktuální hlášku:' + response.data.filename);

                        this.announcementAudio.play();

                        this.dimBackground();

                        setTimeout(() => {
                            this.raiseBackground();
                        }, 3000);
                    }
                })
        },

        dimBackground() {
            console.debug('Dimming bg.');

            if (this.backgroundAudio.volume > 0) {
                setTimeout(() => {
                    this.backgroundAudio.volume -= 0.5;
                }, 300);
            }

        },

        raiseBackground() {
            console.debug('Raising bg.');

            if (this.backgroundAudio.volume > 0) {
                setTimeout(() => {
                    this.backgroundAudio.volume += 0.5;
                }, 300);
            }

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

        startEndTimer() {
            this.end_countdown = 120;

            this.end_countdown_interval = setInterval(() => {
                this.end_countdown = this.end_countdown - 1;

                if (this.end_countdown === 0) {
                    clearInterval(this.end_countdown_interval)
                }
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
