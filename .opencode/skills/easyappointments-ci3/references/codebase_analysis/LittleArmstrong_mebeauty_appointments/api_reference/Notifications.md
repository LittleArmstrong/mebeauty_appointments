# API Reference: Notifications.php

**Language**: PHP

**Source**: `application/libraries/Notifications.php`

---

## Classes

### Notifications

**Inherits from**: (none)

#### Methods

##### __construct()


##### notify_appointment_saved(appointment: array, service: array, provider: array, customer: array, settings: array, manage_mode: bool = false) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |
| settings | array | - | - |
| manage_mode | bool | false | - |

**Returns**: `void`


##### notify_appointment_deleted(appointment: array, service: array, provider: array, customer: array, settings: array, cancellation_reason: string = '') → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |
| settings | array | - | - |
| cancellation_reason | string | '' | - |

**Returns**: `void`


##### log_exception(e: Throwable, message: string, appointment_id: ?int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| e | Throwable | - | - |
| message | string | - | - |
| appointment_id | ?int | - | - |

**Returns**: `void`




## Functions

### __construct()

**Returns**: (none)



### notify_appointment_saved(appointment: array, service: array, provider: array, customer: array, settings: array, manage_mode: bool = false) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |
| settings | array | - | - |
| manage_mode | bool | false | - |

**Returns**: `void`



### notify_appointment_deleted(appointment: array, service: array, provider: array, customer: array, settings: array, cancellation_reason: string = '') → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |
| settings | array | - | - |
| cancellation_reason | string | '' | - |

**Returns**: `void`



### log_exception(e: Throwable, message: string, appointment_id: ?int) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| e | Throwable | - | - |
| message | string | - | - |
| appointment_id | ?int | - | - |

**Returns**: `void`


