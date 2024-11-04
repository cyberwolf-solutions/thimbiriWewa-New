<style>
    body {
        background: black;
    }

    .bg {
        width: 100%;
        /* max-width: 1200px; */
        margin: auto;
        height: 100vh;
        /* max-height: 600px; */
        overflow: hidden;
        position: absolute;
        padding-top: 13%
    }

    body .bg .aur_cont {
        margin: 35px auto 0;
        display: table;
        height: auto;
    }

    body .bg .aur_cont .aur {
        transform: skew(-0.06turn, 18deg);
        display: block;
        width: 0;
        min-height: 122px;
        float: left;
        margin-left: 100px;
        border-radius: 5% 52% 30px 20px;
        opacity: 1;
    }

    body .bg .aur_cont .aur.aur_1 {
        box-shadow: #4bff8b 0px 0px 100px 40px;
        margin-top: 2px;
        animation: topup 7031ms infinite linear;
    }

    body .bg .aur_cont .aur.aur_2 {
        box-shadow: #4b718c 0px 0px 100px 40px;
        margin-top: 27px;
        animation: topup 10359ms infinite linear;
    }

    body .bg .aur_cont .aur.aur_3 {
        box-shadow: #4bb044 0px 0px 100px 40px;
        margin-top: 27px;
        animation: topup 5515ms infinite linear;
    }

    body .bg .aur_cont .aur.aur_4 {
        box-shadow: #4bd4ff 0px 0px 100px 40px;
        margin-top: -30px;
        animation: topup 11580ms infinite linear;
    }

    body .bg .aur_cont .aur.aur_5 {
        box-shadow: #4bffa6 0px 0px 100px 40px;
        margin-top: 0px;
        animation: topup 6773ms infinite linear;
    }

    body .bg .aur_cont .aur.aur_6 {
        box-shadow: #4b724a 0px 0px 100px 40px;
        margin-top: 45px;
        animation: topup 8622ms infinite linear;
    }

    body .bg .aur_cont .aur.aur_7 {
        box-shadow: #4bb044 0px 0px 100px 40px;
        margin-top: 0px;
        animation: topup 11510ms infinite linear;
    }

    body .bg .aur_cont .aur.aur_8 {
        box-shadow: #4be56f 0px 0px 100px 40px;
        margin-top: 5px;
        animation: topup 10258ms infinite linear;
    }

    body .bg .aur_cont .aur.aur_9 {
        box-shadow: #4bb7ff 0px 0px 100px 40px;
        margin-top: 9px;
        animation: topup 12160ms infinite linear;
    }

    body .bg .aur_cont .aur.aur_10 {
        box-shadow: #4bffff 0px 0px 100px 40px;
        margin-top: -27px;
        animation: topup 12931ms infinite linear;
    }

    @keyframes drift {
        from {
            transform: rotate(0deg);
        }

        from {
            transform: rotate(360deg);
        }
    }

    @keyframes topup {

        0%,
        100% {
            transform: translatey(0px);
            opacity: 0;
        }

        50% {
            transform: translatey(150px);
            opacity: 0.1;
        }

        25%,
        75% {
            opacity: 1;
        }
    }

    @keyframes northern {
        0% {
            transform: translate(5%, -2%);
        }

        25% {
            transform: translate(10%, 7%);
        }

        40% {
            transform: rotate(-10deg);
        }

        60% {
            transform: translate(7%, -2%);
        }

        85% {
            transform: translate(6%, 3%) rotate(12deg);
        }

        100% {
            transform: none;
        }
    }
</style>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
</style>
<style>
    .poppins-regular {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<style>
    .active {
        font-weight: bold
    }
</style>


<style>
    /* Apply frosty glass effect on scroll */
    .frosty {
        border-radius: 25rem;
        background-color: rgba(255, 255, 255, 0.028) !important;
        backdrop-filter: blur(10px) !important;
    }
</style>




