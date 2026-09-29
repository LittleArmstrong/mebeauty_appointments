# API Reference: Appointments_model.php

**Language**: PHP

**Source**: `application/models/Appointments_model.php`

---

## Classes

### Appointments_model

**Inherits from**: EA_Model

#### Methods

##### save(appointment: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `int`


##### validate(appointment: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `void`


##### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### insert(appointment: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `int`


##### update(appointment: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `int`


##### find(appointment_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | int | - | - |

**Returns**: `array`


##### value(appointment_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`


##### clear_google_sync_ids(provider_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `void`


##### clear_caldav_sync_ids(provider_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `void`


##### delete_caldav_recurring_events(start_date_time: string, end_date_time: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start_date_time | string | - | - |
| end_date_time | string | - | - |

**Returns**: `void`


##### delete(appointment_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | int | - | - |

**Returns**: `void`


##### get_attendants_number_for_period(start: DateTime, end: DateTime, service_id: int, provider_id: int, exclude_appointment_id: ?int = null) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start | DateTime | - | - |
| end | DateTime | - | - |
| service_id | int | - | - |
| provider_id | int | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `int`


##### get_other_service_attendants_number(start: DateTime, end: DateTime, service_id: int, provider_id: int, exclude_appointment_id: ?int = null) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start | DateTime | - | - |
| end | DateTime | - | - |
| service_id | int | - | - |
| provider_id | int | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `int`


##### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`


##### search(keyword: string, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| keyword | string | - | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`


##### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`


##### load(&$appointment: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$appointment | array | - | - |
| resources | array | - | - |

**Returns**: `void`


##### api_encode(&$appointment: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$appointment | array | - | - |

**Returns**: `void`


##### api_decode(&$appointment: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$appointment | array | - | - |
| base | ?array | null | - |

**Returns**: `void`


##### calculate_end_datetime(appointment: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `string`


##### has_provider_conflict(provider_id: int, start_datetime: string, end_datetime: string, exclude_appointment_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| start_datetime | string | - | - |
| end_datetime | string | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `bool`




## Functions

### save(appointment: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `int`



### validate(appointment: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `void`



### get(where: array|string|null = null, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### insert(appointment: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `int`



### update(appointment: array) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `int`



### find(appointment_id: int) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | int | - | - |

**Returns**: `array`



### value(appointment_id: int, field: string) → mixed

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | int | - | - |
| field | string | - | - |

**Returns**: `mixed`



### clear_google_sync_ids(provider_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `void`



### clear_caldav_sync_ids(provider_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |

**Returns**: `void`



### delete_caldav_recurring_events(start_date_time: string, end_date_time: string) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start_date_time | string | - | - |
| end_date_time | string | - | - |

**Returns**: `void`



### delete(appointment_id: int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | int | - | - |

**Returns**: `void`



### get_attendants_number_for_period(start: DateTime, end: DateTime, service_id: int, provider_id: int, exclude_appointment_id: ?int = null) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start | DateTime | - | - |
| end | DateTime | - | - |
| service_id | int | - | - |
| provider_id | int | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `int`



### get_other_service_attendants_number(start: DateTime, end: DateTime, service_id: int, provider_id: int, exclude_appointment_id: ?int = null) → int

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| start | DateTime | - | - |
| end | DateTime | - | - |
| service_id | int | - | - |
| provider_id | int | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `int`



### query() → CI_DB_query_builder

**Returns**: `CI_DB_query_builder`



### search(keyword: string, limit: ?int = null, offset: ?int = null, order_by: ?string = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| keyword | string | - | - |
| limit | ?int | null | - |
| offset | ?int | null | - |
| order_by | ?string | null | - |

**Returns**: `array`



### to_options(where: array|string|null = null) → array

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| where | array|string|null | null | - |

**Returns**: `array`



### load(&$appointment: array, resources: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$appointment | array | - | - |
| resources | array | - | - |

**Returns**: `void`



### api_encode(&$appointment: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$appointment | array | - | - |

**Returns**: `void`



### api_decode(&$appointment: array, base: ?array = null) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$appointment | array | - | - |
| base | ?array | null | - |

**Returns**: `void`



### calculate_end_datetime(appointment: array) → string

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |

**Returns**: `string`



### has_provider_conflict(provider_id: int, start_datetime: string, end_datetime: string, exclude_appointment_id: ?int = null) → bool

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| provider_id | int | - | - |
| start_datetime | string | - | - |
| end_datetime | string | - | - |
| exclude_appointment_id | ?int | null | - |

**Returns**: `bool`


