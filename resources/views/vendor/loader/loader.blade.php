<style>
    .loader-container{
        background: #EEE url({{asset('assets/img/bg_pattern_batik.jpg')}});
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
    }
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #ffa500;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 0.5s linear infinite;
        animation: spin 0.5s linear infinite;
        position: absolute;
        top: calc(50% - 100px);
        left: calc(50% - 80px);
        transform: translate(-50%, -50%);
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg)  }
        100% { -webkit-transform: rotate(360deg) }
    }

    @keyframes spin {
        0% { transform: rotate(0deg) }
        100% { transform: rotate(360deg) }
    }
</style>
<section class="loader-container">
        <center class="well-lg"><label class="control-label">{{$phase}}</label></center>
        <div class="loader"></div>
</section>
