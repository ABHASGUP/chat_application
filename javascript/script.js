const form = document.querySelector(".signup form"),
continueBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-text");

form.onsubmit = (e)=>{
    e.preventDefault();
}

continueBtn.onclick = ()=>{
    // Adding loading state
    continueBtn.value = "Please Wait...";
    continueBtn.disabled = true;
    errorText.style.display = "none";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/signup.php", true);
    xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                try {
                    let data = JSON.parse(xhr.response);
                    console.log("Server response:", data);
                    
                    if(data.status === "success"){
                        console.log("Signup successful, redirecting...");
                        location.href = "users.php";
                    } else {
                        errorText.textContent = data.message;
                        errorText.style.display = "block";
                        continueBtn.value = "Continue to Chat";
                        continueBtn.disabled = false;
                    }
                } catch (e) {
                    console.error("Error parsing response:", e);
                    errorText.textContent = "An error occurred while processing the response";
                    errorText.style.display = "block";
                    continueBtn.value = "Continue to Chat";
                    continueBtn.disabled = false;
                }
            } else {
                console.log("Server error:", xhr.status);
                errorText.textContent = "An error occurred. Please try again.";
                errorText.style.display = "block";
                continueBtn.value = "Continue to Chat";
                continueBtn.disabled = false;
            }
        }
    }

    xhr.onerror = ()=>{
        console.log("Network error occurred");
        errorText.textContent = "Network error occurred. Please check your connection.";
        errorText.style.display = "block";
        continueBtn.value = "Continue to Chat";
        continueBtn.disabled = false;
    }

    let formData = new FormData(form);
    xhr.send(formData);
}
