const registerForm = document.getElementById('register-form');
const passwordInput = document.getElementById('password');
const passwordRepeatInput = document.getElementById('password-repeat');

function checkPassword() {
    passwordRepeatInput.style.border = (passwordInput.value === passwordRepeatInput.value) ? '' : '3pt solid red';
}

function registerUser() {
    let xhr = new XMLHttpRequest();
    xhr.open(registerForm.method, registerForm.action, false);
    let data = new FormData(registerForm);
    xhr.send(data);
    console.log('RESPONSE: ' + xhr.responseText);
    if (xhr.status === 200) {
        location.href = '?confirm';
    } else {
        if (xhr.responseText != null) {
            alert(JSON.parse(xhr.responseText).message);
        } else {
            alert("Неизвестная ошибка!")
            console.log(xhr);
        }
    }
    return false;
}

function login(login, password) {
    let xhr = new XMLHttpRequest();
    xhr.open('post', 'api/login.php', false);
    let data = new FormData(registerForm);
    xhr.send(data);
    if (xhr.status === 200) {
        console.log(JSON.parse(xhr.responseText));
        return true;
    } else {
        if (xhr.responseText != null) {
            alert(JSON.parse(xhr.responseText).message);
        } else {
            alert("Неизвестная ошибка!")
            console.log(xhr);
        }
        return false;
    }
}