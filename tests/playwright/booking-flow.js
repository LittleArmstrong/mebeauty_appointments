/**
 * Booking-Flow measurement (docs/visual-measurement.md, spec 0022/02).
 *
 * Walks the Booking-Flow in Chromium: load, "next" 1→2→3→4, "back" 4→3→2→1, then the preselected
 * service/provider link. After every change it reports the Wizard-Card (#book-appointment-wizard) edges relative
 * to the viewport, the scroll position and the active step.
 *
 * Do not run it directly: `npm run measure:booking` (scripts/measure-booking-flow.sh) creates the test customer
 * and appointments through tests/smoke/fixtures.php, passes them in FIXTURE / FIXTURE_CUSTOMER, runs this script
 * in the Playwright image and removes the test data afterwards.
 *
 * Options (`--name=value` or `--name value`; --viewport/--theme may be given more than once, defaults in brackets):
 *   --viewport=<w>x<h>   [1280x800 and 390x844]
 *   --theme=<name>       [mebeauty and default]
 *   --timezone=<tz>      browser timezone [Europe/Berlin]
 *   --locale=<locale>    browser locale [de-DE]
 *   --json               print one JSON line per measurement instead of the tables
 */

const {chromium} = require('playwright');

const BASE = (process.env.BASE_URL || 'http://localhost').replace(/\/$/, '');
const FIXTURE = JSON.parse(process.env.FIXTURE || 'null');
const CUSTOMER = JSON.parse(process.env.FIXTURE_CUSTOMER || 'null');

const OPTION_NAMES = ['viewport', 'theme', 'timezone', 'locale', 'json'];

function parseOptions(argv) {
    const options = {viewport: [], theme: [], timezone: 'Europe/Berlin', locale: 'de-DE', json: false};

    for (let index = 0; index < argv.length; index++) {
        const argument = argv[index];
        let [, name, value] = argument.match(/^--([a-z]+)(?:=(.*))?$/) || [];

        // Reject an unknown option before it can take the next argument as its value.
        if (!OPTION_NAMES.includes(name)) {
            throw new Error('Unknown option ' + argument + ' (see the header of tests/playwright/booking-flow.js)');
        }

        // `--name value`: the next argument is the value unless it is an option itself (--json takes none).
        if (
            name !== 'json' &&
            value === undefined &&
            index + 1 < argv.length &&
            !argv[index + 1].startsWith('--')
        ) {
            value = argv[++index];
        }

        if (name === 'viewport' && /^\d+x\d+$/.test(value || '')) {
            const [width, height] = value.split('x').map(Number);
            options.viewport.push({width, height});
        } else if (name === 'theme' && value) {
            options.theme.push(value);
        } else if ((name === 'timezone' || name === 'locale') && value) {
            options[name] = value;
        } else if (name === 'json' && value === undefined) {
            options.json = true;
        } else {
            throw new Error('Unknown option ' + argument + ' (see the header of tests/playwright/booking-flow.js)');
        }
    }

    if (!options.viewport.length) {
        options.viewport = [
            {width: 1280, height: 800},
            {width: 390, height: 844},
        ];
    }

    if (!options.theme.length) {
        options.theme = ['mebeauty', 'default'];
    }

    return options;
}

/** Wizard-Card edges, scroll position and active step (runs in the page). */
function measureCard() {
    const card = document.querySelector('#book-appointment-wizard');
    const rect = card.getBoundingClientRect();
    const active = document.querySelector('.active-step');

    return {
        top: Math.round(rect.top),
        bottom: Math.round(rect.bottom),
        scrollY: Math.round(window.scrollY),
        step: active ? Number(active.id.replace('step-', '')) : null,
    };
}

/** Waits until no jQuery fade runs and the given frame is shown. */
async function settle(page, frameIndex) {
    await page.waitForFunction(
        (index) =>
            window.jQuery &&
            window.jQuery(':animated').length === 0 &&
            (index === null || window.jQuery('#wizard-frame-' + index).is(':visible')),
        frameIndex,
        {timeout: 15000},
    );
    await page.waitForTimeout(100);
}

/** Clicks a "next"/"back" button like a customer (scrolled into view first) and measures the result. */
async function change(page, selector, targetIndex) {
    const button = page.locator(selector);
    await button.scrollIntoViewIfNeeded();
    const before = await page.evaluate(measureCard);
    await button.click();
    await settle(page, targetIndex);
    const after = await page.evaluate(measureCard);

    return {...after, moved: after.scrollY !== before.scrollY, before};
}

/** Fills the required customer fields (only those, like a hurried customer) with the fixture customer. */
async function fillCustomerForm(page) {
    const values = {
        'first-name': CUSTOMER.first_name,
        'last-name': CUSTOMER.last_name,
        'email': CUSTOMER.email,
        'phone-number': CUSTOMER.phone_number,
        'address': 'Teststraße 1',
        'city': 'Köln',
        'zip-code': '50667',
        'notes': 'measure-booking-flow',
    };

    for (const [id, value] of Object.entries(values)) {
        const field = page.locator('#wizard-frame-3 #' + id + '.required');

        if (await field.count()) {
            await field.fill(String(value));
        }
    }
}

async function walk(browser, options, viewport, theme) {
    const context = await browser.newContext({viewport, timezoneId: options.timezone, locale: options.locale});
    const page = await context.newPage();
    const url = BASE + '/index.php/booking?theme=' + encodeURIComponent(theme);
    const results = {};

    try {
        await page.goto(url, {waitUntil: 'networkidle', timeout: 60000});
        await settle(page, 1);
        results.load = await page.evaluate(measureCard);

        await page.selectOption('#select-service', String(FIXTURE.service_id));
        await page.selectOption('#select-provider', String(FIXTURE.provider_id));
        results['1→2'] = await change(page, '#button-next-1', 2);

        // The fixture date has free slots: pick it, then the first free hour.
        await page.evaluate((date) => {
            const [year, month, day] = date.split('-').map(Number);
            document.querySelector('#select-date')._flatpickr.setDate(new Date(year, month - 1, day), true);
        }, FIXTURE.date);
        await page.waitForSelector('#available-hours .available-hour', {timeout: 15000});
        await settle(page, 2);
        await page.locator('#available-hours .available-hour').first().click();
        results['2→3'] = await change(page, '#button-next-2', 3);

        await fillCustomerForm(page);
        results['3→4'] = await change(page, '#button-next-3', 4);
        results['4→3'] = await change(page, '#button-back-4', 3);
        results['3→2'] = await change(page, '#button-back-3', 2);
        results['2→1'] = await change(page, '#button-back-2', 1);

        const preselected = url + '&service=' + FIXTURE.service_id + '&provider=' + FIXTURE.provider_id;
        await page.goto(preselected, {waitUntil: 'networkidle', timeout: 60000});
        await settle(page, 2);
        results.preselected = await page.evaluate(measureCard);
    } finally {
        await context.close();
    }

    return results;
}

function cell(name, result) {
    if (name === 'load') {
        return `${result.top}/${result.bottom}, y ${result.scrollY}`;
    }

    if (name === 'preselected') {
        return `step ${result.step}, y ${result.scrollY}, top ${result.top}`;
    }

    return `${result.top}/${result.bottom}` + (result.moved ? '' : ' (no move)');
}

(async () => {
    if (!FIXTURE || !CUSTOMER) {
        throw new Error('FIXTURE/FIXTURE_CUSTOMER missing: run `npm run measure:booking`, not this file directly');
    }

    const options = parseOptions(process.argv.slice(2));
    const browser = await chromium.launch();
    const columns = ['load', '1→2', '2→3', '3→4', '4→3', '3→2', '2→1', 'preselected'];
    const rows = [];

    try {
        for (const theme of options.theme) {
            for (const viewport of options.viewport) {
                const results = await walk(browser, options, viewport, theme);
                const label = `${theme} ${viewport.width}×${viewport.height}`;

                if (options.json) {
                    for (const name of columns) {
                        console.log(JSON.stringify({theme, viewport, change: name, ...results[name]}));
                    }
                } else {
                    rows.push(`| ${label} | ` + columns.map((name) => cell(name, results[name])).join(' | ') + ' |');
                }
            }
        }
    } finally {
        await browser.close();
    }

    if (!options.json) {
        console.log(`Wizard-Card top/bottom (px, viewport-relative), ${options.timezone}, ${options.locale}, ${BASE}`);
        console.log('');
        console.log('| Theme / viewport | Load | 1→2 | 2→3 | 3→4 | 4→3 | 3→2 | 2→1 | Preselected link |');
        console.log('|---|---|---|---|---|---|---|---|---|');
        rows.forEach((row) => console.log(row));
    }
})().catch((error) => {
    console.error('measure-booking-flow: ' + error.message);
    process.exit(1);
});
