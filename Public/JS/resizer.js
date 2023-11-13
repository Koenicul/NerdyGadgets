var originalSize = [];
var originalTotalSize = 0;
$(window).on('load', function () {
    for (var v = 0; v < document.getElementById("Header").children[1].children[0].children.length; v++) {
        originalSize[v] = document.getElementById("Header").children[1].children[0].children[v].offsetWidth;
        if (originalSize[v] > 300) {
            originalSize[v] = (document.getElementById("Header").children[1].children[0].children[v].offsetHeight / 2) + (document.getElementById("Header").children[1].children[0].children[v].scrollWidth / 2);
        }
        originalTotalSize += originalSize[v];
    }
});

$(window).on('load resize', function () {
    var maxResultSize = document.getElementById("ul-class").offsetWidth - 300;
    var t = 0;
    var intermediate = originalSize[originalSize.length - 1];
    for (var c = 0; c < originalSize.length - 1; c++) {
        if (intermediate <= maxResultSize) {
            intermediate += originalSize[c];
            t++;
        }
    }
    var toBeHidden = ((document.getElementById("Header").children[1].children[0].children.length - 1) - t) + 2;

    for (var x = 2; x < toBeHidden; x++) {
        document.getElementById("Header").children[1].children[0].children[(document.getElementById("Header").children[1].children[0].children.length) - x].style.display = "none";
    }
    for (var y = toBeHidden; y < document.getElementById("Header").children[1].children[0].children.length + 1; y++) {
        document.getElementById("Header").children[1].children[0].children[(document.getElementById("Header").children[1].children[0].children.length) - y].style.display = "";
    }
});

