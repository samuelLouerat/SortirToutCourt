function activate() {
    document.getElementById('endingDate').disabled = false;
}
function desactivate() {
    if (document.getElementById('notRegistered').checked === true) {
        document.getElementById('registered').disabled = true
        document.getElementById('notRegistered').disabled = false
    }
    else if (document.getElementById('registered').checked === true) {
        document.getElementById('registered').disabled = false
        document.getElementById('notRegistered').disabled = true
    }
    else
    {
        document.getElementById('registered').disabled = false
        document.getElementById('notRegistered').disabled = false
    }
}
window.onload = function () {
    document.getElementById('registered').disabled = false
    document.getElementById('notRegistered').disabled = false
    document.getElementById('endingDate').disabled = true;
}