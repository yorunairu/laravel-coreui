$('.prevent-zero').on('input', function(event) {
    let value = $(this).val().trim();

    // Remove leading zeros (optional step)
    if (value.startsWith('0')) {
        value = value.replace(/^0+/, '');
    }

    // Check for negative sign and remove if present
    if (value.startsWith('-')) {
        value = value.replace('-', '');
    }

    // Update the input value with the sanitized version
    $(this).val(value);
});
function thousandSeparator(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$('.price-thousand').on('keyup', function() {
    var value = $(this).val();

    // Remove any characters that are not digits or dots
    value = value.replace(/[^0-9]/g, '');

    if (value.length > 0) {
        var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        $(this).val(formattedValue);
    } else {
        $(this).val('');
    }
});
// Define the global function
function fetchDataAndShowModal(button) {
    var url = $(button).data('url');

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data);
            // Assuming you want to populate the modal with the data
            var modalBody = $('.modal-content-journey');
            modalBody.html(data.html); // Format JSON data for display

            // Show the modal after data is fetched
            $('#tenderLogModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching data:', textStatus, errorThrown);
            // Handle errors gracefully
        }
    });
}
// Use event delegation to attach the click event to the button
$(document).on('click', '#tender-log-modal', function(e) {
    e.preventDefault(); // Prevent default behavior
    fetchDataAndShowModal(this);
});
// Initialize AutoNumeric on the input field
// new AutoNumeric('.auto-thousand', {
//     // Options for AutoNumeric
//     digitGroupSeparator: '.', // Use dot as thousand separator
//     decimalPlaces: 0, // No decimal places
//     minimumValue: '0' // Minimum value allowed (optional)
// });
