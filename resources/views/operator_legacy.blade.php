@extends('layout')

@section('content')
    <div class="row">
        <div class="col-6">
            <h2>Hra o kontrolní body</h2>

            <div id="calm-interface">

                <a id="start-btn"
                   class="btn btn-primary"
                   onclick="startGame()">Spustit hru č. <span
                        id="new-game-number">1</span></a>

                <hr>

                <h3>Výběr soundtracku na pozadí</h3>

                <a class="btn btn-primary"
                   onclick="chooseAudio(1)">1) Mix 1</a>
                <a class="btn btn-primary"
                   onclick="chooseAudio(2)">2) Lasermaxx</a>
                <a class="btn btn-primary"
                   onclick="chooseAudio(3)">3) CoD MW</a>

                <h2>Archiv sehrávek kontrolních bodů</h2>

                <table>
                    <tr>
                        <td>Zvolte pro zobrazení detailu sehrávky</td>

                    </tr>
                    @foreach($games as $game)
                        <tr>
                            <td>
                                <a href="{{route('controlPoints.game',['id'=>$game->game_id])}}">Sehrávka kontrolních
                                    bodů
                                    č.{{$game->game_id}}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>


            <div id="prepare-interface">
                <p>Příprava na hru.</p>
            </div>

            <div id="play-interface">
                <a class="btn btn-primary"
                   onclick="callSwat()">Zásahová jednotka</a>
                <a class="btn btn-primary"
                   onclick="stopGame()">Konec hry za 2:00</a>
            </div>

            <div id="ending-interface">
                Spuštěn odpočet do konce hry.

                <a class="btn btn-primary"
                   onclick="callSwat()">Zásahová jednotka</a>
            </div>

            <div id="done-interface">
                Hra byla ukončena.

                <a class="btn btn-primary"
                   href="{{route('controlPoints.operator')}}">Nová sehrávka</a>
            </div>
        </div>
        <div class="col-6">
            <h2>Aktuální stav hry</h2>
            <table class="table-bordered table">
                <tr>
                    <td>Odehraný čas</td>
                    <td><span id="timer">-</span></td>
                </tr>
                <tr>
                    <td>Bod A</td>
                    <td id="current_a">-</td>
                </tr>
                <tr>
                    <td>Bod B</td>
                    <td id="current_b">-</td>
                </tr>
                <tr>
                    <td>Bod C</td>
                    <td id="current_c">-</td>
                </tr>
                <tr>
                    <td>Bod D</td>
                    <td id="current_d">-</td>
                </tr>
                <tr>
                    <td>Bod E</td>
                    <td id="current_e">-</td>
                </tr>
                <tr>
                    <td>Bod F</td>
                    <td id="current_f">-</td>
                </tr>
            </table>

            <h2>Aktuální žebříček</h2>
            <table class="table-bordered table">
                <tr>
                    <td>1.</td>
                    <td id="rank_1_name">-</td>
                    <td id="rank_1_sec">-</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td id="rank_2_name">-</td>
                    <td id="rank_2_sec">-</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td id="rank_3_name">-</td>
                    <td id="rank_3_sec">-</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td id="rank_4_name">-</td>
                    <td id="rank_4_sec">-</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td id="rank_5_name">-</td>
                    <td id="rank_5_sec">-</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td id="rank_6_name">-</td>
                    <td id="rank_6_sec">-</td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td id="rank_7_name">-</td>
                    <td id="rank_7_sec">-</td>
                </tr>
            </table>


        </div>


    </div>

@endsection


@section('scripts')
    <script>
        let announcementAudio = document.createElement('audio');

        let startCountdownAudio = document.createElement('audio');
        let backgroundAudio = document.createElement('audio');
        let endCountdownAudio = document.createElement('audio');

        let choosedAudioId = 1;
        let currentGame = 1;

        /**
         * Přehrávání hlášek.
         */
        function playCurrentAnnouncement() {
            $.get({
                url: '/api/control-points/audio/current',
                success: function (response) {
                    if (response === '0') {
                        console.log('Žádná hláška k přehrání.')
                    }
                    else {

                        announcementAudio.src = '/sound/' + response;

                        console.log('Přehrávám aktuální hlášku:' + response);

                        announcementAudio.play();

                        dimBackground();

                        setTimeout(function () {
                            raiseBackground();
                        }, 3000);
                    }
                }
            });
        }

        /**
         * Hudba na pozadí.
         */
        // Výběr OST
        function chooseAudio(id) {
            choosedAudioId = id;

            console.log('Zvoleno: ' + id);
        }

        function playStartCountdown() {
            startCountdownAudio.src = '/sound/start_countdown.mp3';
            startCountdownAudio.play();
        }

        function playBackground() {
            let num = choosedAudioId;
            backgroundAudio.src = '/sound/cp_bg' + num + '.mp3';
            backgroundAudio.play();

            console.log('Spouštím background music num: ' + num);
        }

        function fadeOutBackground() {
            setInterval(function () {

                if (backgroundAudio.volume > 0.2) {
                    backgroundAudio.volume -= 0.1;
                }

                else {
                    backgroundAudio.pause();
                }
            }, 50);
        }

        // Trochu utlumí background
        function dimBackground() {
            [1, 2, 3, 4].forEach(function (i) {
                if (backgroundAudio.volume > 0) {
                    setTimeout(function () {
                        backgroundAudio.volume -= 0.1;
                    }, 300);
                }
            });
        }

        function raiseBackground() {
            [1, 2, 3, 4].forEach(function (i) {
                if (backgroundAudio.volume > 0) {
                    setTimeout(function () {
                        backgroundAudio.volume += 0.1;
                    }, 300);
                }
            });
        }

        function playEndCountdown() {
            endCountdownAudio.src = '/sound/EndCountdown.mp3';
            endCountdownAudio.play();

            console.log('Spouštím odpočet do konce hry.');
        }

        /**
         * Dom rozhraní.
         */
        function renderCalmInterface() {
            document.getElementById('calm-interface').style.display = 'block';
            document.getElementById('prepare-interface').style.display = 'none';
            document.getElementById('play-interface').style.display = 'none';
            document.getElementById('done-interface').style.display = 'none';
            document.getElementById('ending-interface').style.display = 'none';
        }

        function renderPrepare() {
            document.getElementById('calm-interface').style.display = 'none';
            document.getElementById('prepare-interface').style.display = 'block';
        }

        function renderPlayInterface() {
            document.getElementById('prepare-interface').style.display = 'none';
            document.getElementById('play-interface').style.display = 'block';
        }

        function renderEndingInterface() {
            document.getElementById('play-interface').style.display = 'none';
            document.getElementById('ending-interface').style.display = 'block';
        }

        function renderDoneInterface() {
            document.getElementById('ending-interface').style.display = 'none';
            document.getElementById('done-interface').style.display = 'block';
        }

        /**
         * Aktualizuje aktuální captures
         */
        function updateActual() {
            $.getJSON({
                url: '/api/control-points/game/' + currentGame + '/points',
                success: function (response) {
                    document.getElementById('current_a').innerText = response[0];
                    document.getElementById('current_b').innerText = response[1];
                    document.getElementById('current_c').innerText = response[2];
                    document.getElementById('current_d').innerText = response[3];
                    document.getElementById('current_e').innerText = response[4];
                    document.getElementById('current_f').innerText = response[5];
                }
            });
        }

        function updateRank() {
            $.getJSON({
                url: '/api/control-points/game/' + currentGame + '/rank',
                success: function (response) {
                    document.getElementById('rank_1_name').innerText = response[0][0];
                    document.getElementById('rank_2_name').innerText = response[0][1];
                    document.getElementById('rank_3_name').innerText = response[0][2];
                    document.getElementById('rank_4_name').innerText = response[0][3];
                    document.getElementById('rank_5_name').innerText = response[0][4];
                    document.getElementById('rank_6_name').innerText = response[0][5];
                    document.getElementById('rank_7_name').innerText = response[0][6];

                    document.getElementById('rank_1_sec').innerText = response[1][0] + ' s';
                    document.getElementById('rank_2_sec').innerText = response[1][1] + ' s';
                    document.getElementById('rank_3_sec').innerText = response[1][2] + ' s';
                    document.getElementById('rank_4_sec').innerText = response[1][3] + ' s';
                    document.getElementById('rank_5_sec').innerText = response[1][4] + ' s';
                    document.getElementById('rank_6_sec').innerText = response[1][5] + ' s';
                    document.getElementById('rank_7_sec').innerText = response[1][6] + ' s';
                }
            });
        }

        /**
         * Stav
         */
        function setState() {
            $.ajax('/api/state/set/rezacka');


            console.log('Nastavuji stav základny na 3.')
        }

        function setCalm() {
            $.ajax('/api/control-points/stopGame');
            console.log('Nastavuji stav základny na 0.')
        }


        /**
         * Ovládání hry.
         *
         * Countdown trvá 0:43:068
         */
        let timer = -43.068;

        function startTimer() {
            let timerInterval = setInterval(function () {
                timer = timer + 0.1;

                let timerSpan = document.getElementById('timer');

                if (timer < 0) {
                    let timerSeconds = Math.floor(timer);

                    timerSpan.innerText = timerSeconds + 's';
                }
                else {
                    let timerMinutes = Math.floor(timer / 60);
                    let timerSeconds = Math.floor(timer - (timerMinutes * 60));

                    timerSpan.innerText = timerMinutes + 'm, ' + timerSeconds + 's';
                }

                //console.log('Časovač hry: ' + timer);
            }, 100);
        }

        /**
         * Spustí sekvenci nové sehrávky.
         */
        $.ready(function () {
            renderCalmInterface();

            // Nové ID hry
            $.get({
                url: '/api/control-points/setNewGame',
                success: function (response) {
                    currentGame = response;
                    document.getElementById('new-game-number').innerText = currentGame;
                }
            });
        });

        function startGame() {
            startTimer();

            renderPrepare();

            playStartCountdown();
            console.log('Spouštím countdown.');

            setTimeout(function () {
                // Infografika
                updateActual();
                updateRank();

                setInterval(function () {
                    // Přehrávání hlášek
                    playCurrentAnnouncement();

                    // Infografika
                    updateActual();
                    updateRank();
                }, 3000);

                setState();
                playBackground();
                renderPlayInterface();
            }, 43068);


        }

        function callSwat() {
            $.ajax('/api/control-points/callSwat');
        }

        function stopGame() {

            playEndCountdown();

            announcementAudio.volume = 0;


            renderEndingInterface();

            setTimeout(function () {
                fadeOutBackground();
            }, 200);


            setTimeout(function () {
                renderDoneInterface();
                // stopTimer();

                setCalm();
            }, 120035);


        }


    </script>
@endsection
