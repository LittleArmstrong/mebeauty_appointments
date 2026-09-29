# API Reference: Google_sync.php

**Language**: PHP

**Source**: `application/libraries/Google_sync.php`

---

## Classes

### Google_sync

**Inherits from**: (none)

#### Methods

##### __construct()


##### get_client_id() → string

**Returns**: `string`


##### get_client_secret() → string

**Returns**: `string`


##### initialize_clients() → void

**Returns**: `void`


##### get_auth_url(state: ?string = null) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| state | ?string | null | - |

**Returns**: `string`


##### authenticate(code: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| code | string | - | - |

**Returns**: `array`


##### refresh_token(refresh_token: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| refresh_token | string | - | - |

**Returns**: `void`


##### add_appointment(appointment: array, provider: array, service: array, customer: array, settings: array) → Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |
| service | array | - | - |
| customer | array | - | - |
| settings | array | - | - |

**Returns**: `Event`


##### update_appointment(appointment: array, provider: array, service: array, customer: array, settings: array) → Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |
| service | array | - | - |
| customer | array | - | - |
| settings | array | - | - |

**Returns**: `Event`


##### delete_appointment(provider: array, google_event_id: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| google_event_id | string | - | - |

**Returns**: `void`


##### add_unavailability(provider: array, unavailability: array) → Google_Service_Calendar_Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| unavailability | array | - | - |

**Returns**: `Google_Service_Calendar_Event`


##### update_unavailability(provider: array, unavailability: array) → Google_Service_Calendar_Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| unavailability | array | - | - |

**Returns**: `Google_Service_Calendar_Event`


##### delete_unavailability(provider: array, google_event_id: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| google_event_id | string | - | - |

**Returns**: `void`


##### get_event(provider: array, google_event_id: string) → Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| google_event_id | string | - | - |

**Returns**: `Event`


##### get_sync_events(google_calendar: string, start: string, end: string) → Events

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| google_calendar | string | - | - |
| start | string | - | - |
| end | string | - | - |

**Returns**: `Events`


##### get_google_calendars() → array

**Returns**: `array`


##### get_add_to_google_url(appointment_id: int) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | int | - | - |

**Returns**: `string`


##### is_all_day_event(start_datetime: string, end_datetime: string) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start_datetime | string | - | - |
| end_datetime | string | - | - |

**Returns**: `bool`


##### build_event_datetime(datetime: string, timezone: DateTimeZone, is_all_day: bool, is_end: bool = false) → Google_Service_Calendar_EventDateTime

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| datetime | string | - | - |
| timezone | DateTimeZone | - | - |
| is_all_day | bool | - | - |
| is_end | bool | false | - |

**Returns**: `Google_Service_Calendar_EventDateTime`




## Functions

### __construct()

**Returns**: (none)



### get_client_id() → string

**Returns**: `string`



### get_client_secret() → string

**Returns**: `string`



### initialize_clients() → void

**Returns**: `void`



### get_auth_url(state: ?string = null) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| state | ?string | null | - |

**Returns**: `string`



### authenticate(code: string) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| code | string | - | - |

**Returns**: `array`



### refresh_token(refresh_token: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| refresh_token | string | - | - |

**Returns**: `void`



### add_appointment(appointment: array, provider: array, service: array, customer: array, settings: array) → Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |
| service | array | - | - |
| customer | array | - | - |
| settings | array | - | - |

**Returns**: `Event`



### update_appointment(appointment: array, provider: array, service: array, customer: array, settings: array) → Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |
| service | array | - | - |
| customer | array | - | - |
| settings | array | - | - |

**Returns**: `Event`



### delete_appointment(provider: array, google_event_id: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| google_event_id | string | - | - |

**Returns**: `void`



### add_unavailability(provider: array, unavailability: array) → Google_Service_Calendar_Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| unavailability | array | - | - |

**Returns**: `Google_Service_Calendar_Event`



### update_unavailability(provider: array, unavailability: array) → Google_Service_Calendar_Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| unavailability | array | - | - |

**Returns**: `Google_Service_Calendar_Event`



### delete_unavailability(provider: array, google_event_id: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| google_event_id | string | - | - |

**Returns**: `void`



### get_event(provider: array, google_event_id: string) → Event

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider | array | - | - |
| google_event_id | string | - | - |

**Returns**: `Event`



### get_sync_events(google_calendar: string, start: string, end: string) → Events

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| google_calendar | string | - | - |
| start | string | - | - |
| end | string | - | - |

**Returns**: `Events`



### get_google_calendars() → array

**Returns**: `array`



### get_add_to_google_url(appointment_id: int) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | int | - | - |

**Returns**: `string`



### is_all_day_event(start_datetime: string, end_datetime: string) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start_datetime | string | - | - |
| end_datetime | string | - | - |

**Returns**: `bool`



### build_event_datetime(datetime: string, timezone: DateTimeZone, is_all_day: bool, is_end: bool = false) → Google_Service_Calendar_EventDateTime

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| datetime | string | - | - |
| timezone | DateTimeZone | - | - |
| is_all_day | bool | - | - |
| is_end | bool | false | - |

**Returns**: `Google_Service_Calendar_EventDateTime`


