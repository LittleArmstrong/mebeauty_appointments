# API Reference: Synchronization.php

**Language**: PHP

**Source**: `application/libraries/Synchronization.php`

---

## Classes

### Synchronization

**Inherits from**: (none)

#### Methods

##### __construct()


##### sync_appointment_saved(appointment: array, service: array, provider: array, customer: array, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |
| settings | array | - | - |

**Returns**: `void`


##### sync_appointment_deleted(appointment: array, provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |

**Returns**: `void`


##### sync_unavailability_saved(unavailability: array, provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `void`


##### sync_unavailability_deleted(unavailability: array, provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `void`


##### remove_appointment_on_provider_change(appointment_id) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | None | - | - |

**Returns**: `void`




## Functions

### __construct()

**Returns**: (none)



### sync_appointment_saved(appointment: array, service: array, provider: array, customer: array, settings: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| service | array | - | - |
| provider | array | - | - |
| customer | array | - | - |
| settings | array | - | - |

**Returns**: `void`



### sync_appointment_deleted(appointment: array, provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment | array | - | - |
| provider | array | - | - |

**Returns**: `void`



### sync_unavailability_saved(unavailability: array, provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `void`



### sync_unavailability_deleted(unavailability: array, provider: array) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| unavailability | array | - | - |
| provider | array | - | - |

**Returns**: `void`



### remove_appointment_on_provider_change(appointment_id) → void

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| appointment_id | None | - | - |

**Returns**: `void`


