document.getElementById('price-calculator-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    var squareMeters = parseFloat(document.getElementById('square-meters').value);
    var add10Percent = document.getElementById('add-10-percent').checked;
    var resultDiv = document.getElementById('price-calculator-result');

    if (add10Percent) {
        squareMeters = squareMeters * 1.10;
    }

    var packsNeeded = Math.ceil(squareMeters / 2); // The assumption that one package covers 2 m2

    resultDiv.innerHTML = 'Needed ' + packsNeeded + ' vinyl packs.';
});
