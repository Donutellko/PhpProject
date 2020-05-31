const form = document.getElementById('create-form');

const title = document.getElementById('title');
const descr = document.getElementById('descr');
const category = document.getElementById('category');
const item = document.getElementById('item');
const start_bet = document.getElementById('start-bet');
const time_end = document.getElementById('time-end');
const future = document.getElementById('future');

time_end.value = toInputDateValue(plusMonth(1));
future.value = toInputDateValue(plusMonth(1));

resetItems();


function plusMonth(month, date = new Date()) {
    date.setMonth(date.getMonth() + month);
    return date;
}

function toInputDateValue(date) {
    return date.toISOString().substr(0, 10);
}

function resetItems() {
    item.disabled = true;
    item.innerHTML = `<option value="" disabled selected>-- Выберите товар --</option>`;
}

function setItems() {
    resetItems();

    let x = new XMLHttpRequest();
    x.open('GET', `api/items.php?category=${category.value}`, false);
    x.send();
    let items = JSON.parse(x.responseText);
    for (let it of items) {
        let option = document.createElement('option');
        option.value = it.id;
        option.innerText = it.title;
        item.add(option);
    }
    item.disabled = false;
}

function create() {
    let error = false;
    let inputs = form.querySelectorAll('input, select, textarea');
    for (let input of inputs) {
        if (!input.value) {
            input.classList.add("w3-border-red");
            error = true;
        } else {
            input.classList.remove("w3-border-red");
            input.classList.add("w3-border");
        }
    }

    if (error) {
        alert('Заполните все обязательные поля!');
        return false;
    } else {
        return true;
    }
}