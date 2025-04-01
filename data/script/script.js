function showPassword(number, isConfirm) {
    if (isConfirm) {
        var x = document.getElementById("confirm_password");
    }
    else {
        var x = document.getElementById("password");
    }
    var eye_icon = number == 1 ? document.querySelector(".password:nth-of-type(1) .eye img") : document.querySelector(".confirm:nth-of-type(1) .eye img");
    if (x.type === "password") {
        x.type = "text";
        eye_icon.src = "../images/hide.png";
    } else {
        x.type = "password";
        eye_icon.src = "../images/view.png";
    }
}