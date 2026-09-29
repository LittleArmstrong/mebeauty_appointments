# API Reference: Availability.php

**Language**: PHP

**Source**: `application/libraries/Availability.php`

---

## Classes

### Availability

**Inherits from**: (none)

#### Methods

##### __construct()


##### get_available_hours(date: string, service: array, provider: array, exclude_appointment_id: ?int = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| service | array | - | - |
| provider | array | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `array`


##### consider_multiple_attendants(date: string, service: array, provider: array, exclude_appointment_id: ?int = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| service | array | - | - |
| provider | array | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `array`


##### remove_breaks(date: string, periods: array, breaks: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| periods | array | - | - |
| breaks | array | - | - |

**Returns**: `array`


##### remove_unavailability_events(periods: array, unavailability_events: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| periods | array | - | - |
| unavailability_events | array | - | - |

**Returns**: `array`


##### get_available_periods(date: string, provider: array, exclude_appointment_id: ?int = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| provider | array | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `array`


##### generate_available_hours(date: string, service: array, empty_periods: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| service | array | - | - |
| empty_periods | array | - | - |

**Returns**: `array`


##### consider_book_advance_timeout(date: string, available_hours: array, provider: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| available_hours | array | - | - |
| provider | array | - | - |

**Returns**: `array`


##### consider_future_booking_limit(selected_date: string, available_hours: array, provider: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| selected_date | string | - | - |
| available_hours | array | - | - |
| provider | array | - | - |

**Returns**: `array`




## Functions

### __construct()

**Returns**: (none)



### get_available_hours(date: string, service: array, provider: array, exclude_appointment_id: ?int = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| service | array | - | - |
| provider | array | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `array`



### consider_multiple_attendants(date: string, service: array, provider: array, exclude_appointment_id: ?int = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| service | array | - | - |
| provider | array | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `array`



### remove_breaks(date: string, periods: array, breaks: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| periods | array | - | - |
| breaks | array | - | - |

**Returns**: `array`



### remove_unavailability_events(periods: array, unavailability_events: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| periods | array | - | - |
| unavailability_events | array | - | - |

**Returns**: `array`



### get_available_periods(date: string, provider: array, exclude_appointment_id: ?int = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| provider | array | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `array`



### generate_available_hours(date: string, service: array, empty_periods: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| service | array | - | - |
| empty_periods | array | - | - |

**Returns**: `array`



### consider_book_advance_timeout(date: string, available_hours: array, provider: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| date | string | - | - |
| available_hours | array | - | - |
| provider | array | - | - |

**Returns**: `array`



### consider_future_booking_limit(selected_date: string, available_hours: array, provider: array) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| selected_date | string | - | - |
| available_hours | array | - | - |
| provider | array | - | - |

**Returns**: `array`


