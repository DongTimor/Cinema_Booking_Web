$(document).on("change","#image-input", function(){
    let file = this.files[0];
    let reader = new FileReader();
    reader.onload = function(e){
        document.getElementById('image-preview').src = e.target.result;
    }
    reader.readAsDataURL(file);
})
