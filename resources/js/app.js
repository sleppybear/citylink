import './bootstrap';
import './test1';
import './test2';

window.showModal = function (data) {
    let modal = new bootstrap.Modal(document.getElementById('modal'));
    $('#modal-text').text(JSON.stringify(data, null, 4));
    modal.show();
}
