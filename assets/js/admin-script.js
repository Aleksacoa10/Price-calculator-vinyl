document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('price-calculator-modal');
    var btn = document.getElementById('price-calculator-view-details');
    var span = document.getElementsByClassName('price-calculator-modal-close')[0];

    btn.onclick = function() {
        modal.style.display = 'block';
    }

    span.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
