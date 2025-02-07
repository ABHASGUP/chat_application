const form = document.querySelector(".forgot-password form"),
continueBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-text");

form.onsubmit = (e)=>{
    e.preventDefault();
}

continueBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/forgot-password.php", true);
    xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                try {
                    let data = JSON.parse(xhr.response);
                    if(data.status === "success"){
                        errorText.classList.remove("error");
                        errorText.classList.add("success");
                        errorText.innerHTML = data.message;
                    } else {
                        errorText.classList.remove("success");
                        errorText.classList.add("error");
                        errorText.textContent = data.message;
                    }
                    errorText.style.display = "block";
                } catch(e) {
                    console.error("Error parsing response:", e);
                    errorText.textContent = "An error occurred while processing the response";
                    errorText.style.display = "block";
                }
            }
        }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}
