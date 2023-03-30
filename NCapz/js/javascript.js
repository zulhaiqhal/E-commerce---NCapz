var formStatus = false;
//0 = closed, 1 = opened

function openForm() {
    if (formStatus == 0) {
        animationOpen();
        document.getElementById("myForm").style.display = "block";
        formStatus = true;
    } else {
        closeForm();
        formStatus = false;
    }
}

function closeForm() {
    animationOpen();
    document.getElementById("myForm").style.display = "none";
    formStatus = 0;
}

function animationOpen() {
    document.getElementById("myForm").style.transition = "all 2s";
}



// Need import jQuery
// function previewFile(input){
//     var file = $("input[type=file]").get(0).files[0];

//     if(file){
//         var reader = new FileReader();

//         reader.onload = function(){
//             $("#previewImg").attr("src",reader.result);
//             $("#previewImg").hide();
//             $("#previewImg").fadeIn(650);
//         }

//         reader.readAsDataURL(file);
//     }
// }