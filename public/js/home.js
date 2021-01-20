$(document).ready(function () {


    $('#video_modal').on('shown.bs.modal', function () {
        $('#video_player')[0].play();
    })


    $('#video_modal').on('hidden.bs.modal', function () {
        $('#video_player')[0].pause();
    })

    $('.category_button').on("click", function () {
        $('.all_media').hide();
        $('.' + this.id).show();
        console.log(this.id);
    });

    $('#video_modal').on('hidden.bs.modal', function () {
        stopVideo();
    })

});


function setVideoModal(url, title)
{
    $("#video_modal_title").text(title);
    $("#video_player_src").attr('src', url);


    let video = document.getElementById('video_player');

    stopAudio();

    video.load();
    video.play();

}


function playAudio(title, url)
{

    let audio = document.getElementById('audio_control');
    audio.pause();

    $("#audio_now_playing").text(title);
    $("#audio_src").attr('src', url);

    $("#audio_footer").show();


    audio.load();
    audio.play();

    Swal.fire({
        icon: 'success',
        title: 'Now playing ' + title,
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    })

}


function stopAudio()
{
    let audio = document.getElementById('audio_control');
    audio.pause();
}


function stopVideo() {

    let video = document.getElementById('video_player');
    video.pause();
    video.removeAttribute('src'); // empty source
    video.load();
}
