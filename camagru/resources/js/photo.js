(function(){
    // Self invoking function
    var video = document.getElementById('video');
        overlay = document.getElementById('overlay');
        fcb = document.getElementById('Barcelona');
        sticker = document.getElementById('Sticker');
        liv = document.getElementById('Liverpool');
        mcf = document.getElementById('City');
        rma = document.getElementById('Madrid');
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

    fcb.addEventListener('click', function(){
        overlay.src = "../resources/images/overlays/FCB.png";
    });

    sticker.addEventListener('click', function(){
        overlay.src = "../resources/images/overlays/sticker1.png";
    });

    liv.addEventListener('click', function(){
        overlay.src = "../resources/images/overlays/LIV.png";   
    });

    mcf.addEventListener('click', function(){
        overlay.src = "../resources/images/overlays/MCF.png";        
    });

    rma.addEventListener('click', function(){
        overlay.src = "../resources/images/overlays/RMA.png";
    });

    var canvas = document.getElementById('canvas');
        canvasOverlay = document.getElementById('canvasOverlay');
        baseimage = canvas.getContext('2d');
        overlayimage = canvasOverlay.getContext('2d');
    document.getElementById('capture').addEventListener('click', function(){
        overlayimage.clearRect(0, 0, 100, 100);
        baseimage.drawImage(video, 0, 0, 400, 300);
        overlayimage.drawImage(overlay, 0, 0, 100, 100);
        console.log(canvas.toDataURL('image/png')); //my image URL
    });

    document.getElementById('save').addEventListener('click', function(){
        // baseimage.drawImage(video, 0, 0, 400, 300);
        // console.log(canvas.toDataURL('image/png')); //my image URL
        var layer1 = canvas.toDataURL('image/png');
            layer2 = null;
            if(document.getElementById("overlay").hasAttribute("src")){
                layer2 = canvasOverlay.toDataURL('image/png');
            }
            const url = "../resources/functions/saveImage.php";
                var xhttp = new XMLHttpRequest();
                var values = "baseimage="+layer1+"&overlayimage="+layer2;
                xhttp.open("POST", url, true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.onreadystatechange = function(){
                    if(xhttp.readyState == 4 && xhttp.status == 200){
                        var response = xhttp.responseText;
                        console.log(response);
                    }
                }
                xhttp.send(values);
    });
})();