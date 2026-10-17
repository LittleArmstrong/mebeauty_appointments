/**
 * Booking refusal utility.
 *
 * Expected refusals of the booking form (spec 0023: the customer already has an appointment at that time, the
 * requested hour is no longer available) are marked by the server (Booking_refusal library). This module tells them
 * from unexpected errors and shows them in the message dialog with the title and text from the server, without the
 * "unexpected problem" heading. The wizard stays as it is, so the entered data is kept.
 */
window.App.Utils.BookingRefusal = (function () {
    /**
     * Check whether a response is a marked refusal.
     *
     * @param {Object} response Parsed JSON body of a failed request.
     *
     * @returns {Boolean} True if the response is an expected booking refusal.
     */
    function isRefusal(response) {
        return Boolean(response && response.booking_refused === true && response.title && response.message);
    }

    /**
     * Show the refusal dialog for a failed booking request.
     *
     * @param {Object} jqXHR The failed request.
     *
     * @returns {Boolean} True if the request was an expected refusal and the dialog is shown.
     */
    function show(jqXHR) {
        let response;

        try {
            response = JSON.parse(jqXHR.responseText);
        } catch (error) {
            return false;
        }

        if (!isRefusal(response)) {
            return false;
        }

        App.Utils.Message.show(
            App.Utils.String.escapeHtml(response.title),
            App.Utils.String.escapeHtml(response.message),
        );

        return true;
    }

    return {
        isRefusal,
        show,
    };
})();
