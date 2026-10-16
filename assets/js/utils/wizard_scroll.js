/**
 * Wizard scroll utility.
 *
 * Pure scroll rule that keeps the booking Wizard-Card in view after a step change (spec 0020). The booking page
 * script measures the card and applies the result; this module has no DOM access.
 */
window.App.Utils.WizardScroll = (function () {
    /**
     * Distance between the top of the viewport and the top of the wizard card after scrolling (in px).
     *
     * @type {Number}
     */
    const WIZARD_SCROLL_OFFSET = 16;

    /**
     * Get the scroll position that keeps the wizard card in view after a step change.
     *
     * If the card is fully inside the viewport nothing scrolls, otherwise the top of the card is placed
     * WIZARD_SCROLL_OFFSET px below the top of the viewport.
     *
     * @param {Number} cardTop Top edge of the wizard card relative to the viewport.
     * @param {Number} cardHeight Height of the wizard card.
     * @param {Number} viewportHeight Height of the viewport.
     * @param {Number} scrollTop Current scroll position of the page.
     *
     * @returns {Number|null} The target scroll position or null if no scrolling is needed.
     */
    function getWizardScrollTarget(cardTop, cardHeight, viewportHeight, scrollTop) {
        if (cardTop >= 0 && cardTop + cardHeight <= viewportHeight) {
            return null;
        }

        const target = Math.max(0, Math.round(scrollTop + cardTop - WIZARD_SCROLL_OFFSET));

        return target === Math.round(scrollTop) ? null : target;
    }

    return {
        WIZARD_SCROLL_OFFSET,
        getWizardScrollTarget,
    };
})();
