(function(){
    // Self invoking function
    var video = document.getElementById('video');
    // var vendorUrl = window.URL || window.webkitURL;
    
    navigator.getMedia = navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia;

    navigator.getMedia({
        video: true,
        audio: false
    }, function(stream){
        //Success
        video.srcObject = stream;
        video.play();
    }, function(error){
        //An error occured
    });

    var canvas = document.getElementById('canvas');
    var image = canvas.getContext('2d');
    document.getElementById('capture').addEventListener('click', function(){
        image.drawImage(video, 0, 0, 400, 300);
    });
})();